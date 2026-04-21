<?php

declare(strict_types=1);

namespace App\Services\Allocation;

use App\Models\PassengerQueue;
use App\Models\Driver;
use App\Models\Assignment;
use App\Models\RRPointer;
use App\Models\DriverLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class RoundRobinService
{
    /**
     * Assign 1 passenger (VIP aware) to next available driver
     * with Round Robin + Defer Once support
     */
    public function assign(int $vehicleCategoryId): Assignment
    {
        return DB::transaction(function () use ($vehicleCategoryId) {

            /**
             * 1️⃣ Ambil penumpang paling prioritas
             * (VIP / VVIP dulu, lalu FIFO)
             */
            $passenger = PassengerQueue::query()
                ->select('passenger_queues.*')
                ->join(
                    'passenger_types',
                    'passenger_types.id',
                    '=',
                    'passenger_queues.passenger_type_id'
                )
                ->where('passenger_queues.vehicle_category_id', $vehicleCategoryId)
                ->where('passenger_queues.status', 'waiting')
                ->orderByDesc('passenger_types.priority_level')
                ->orderBy('passenger_queues.created_at')
                ->lockForUpdate()
                ->first();

            if (! $passenger) {
                throw new RuntimeException('Tidak ada antrian penumpang');
            }

            /**
             * 2️⃣ Ambil RR Pointer
             */
            $pointer = RRPointer::query()
                ->where('vehicle_category_id', $vehicleCategoryId)
                ->lockForUpdate()
                ->first();

            $lastDriverId = $pointer?->last_driver_id;

            /**
             * 3️⃣ Cari driver READY berikutnya (fase 1: setelah last_driver_id)
             */
            $driver = null;

            $drivers = Driver::query()
                ->where('vehicle_category_id', $vehicleCategoryId)
                ->where('status', 'ready')
                ->where('active', true)
                ->when(
                    $lastDriverId,
                    fn ($q) => $q->where('id', '>', $lastDriverId)
                )
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($drivers as $candidate) {
                if ($candidate->defer_once) {
                    // 🚦 TUNDA SEKALI → RESET FLAG
                    $candidate->update(['defer_once' => false]);

                    DriverLog::create([
                        'driver_id' => $candidate->id,
                        'action'    => 'defer_once_used',
                        'notes'     => 'Driver dilewati satu kali sesuai tunda giliran',
                    ]);

                    continue;
                }

                $driver = $candidate;
                break;
            }

            /**
             * 4️⃣ Jika belum ada → fase 2 (wrap ke awal)
             */
            if (! $driver) {

                $drivers = Driver::query()
                    ->where('vehicle_category_id', $vehicleCategoryId)
                    ->where('status', 'ready')
                    ->where('active', true)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($drivers as $candidate) {
                    if ($candidate->defer_once) {
                        // 🚦 RESET FLAG SETELAH DIPAKAI
                        $candidate->update(['defer_once' => false]);

                        DriverLog::create([
                            'driver_id' => $candidate->id,
                            'action'    => 'defer_once_used',
                            'notes'     => 'Driver dilewati satu kali (wrap)',
                        ]);

                        continue;
                    }

                    $driver = $candidate;
                    break;
                }
            }

            if (! $driver) {
                throw new RuntimeException('Tidak ada driver yang siap');
            }

            /**
             * 5️⃣ Buat assignment
             */
            $assignment = Assignment::create([
                'passenger_queue_id' => $passenger->id,
                'driver_id'          => $driver->id,
                'status'             => 'assigned',
                'assigned_at'        => now(),
            ]);

            /**
             * 6️⃣ Update status passenger & driver
             */
            $passenger->update([
                'status' => 'assigned',
            ]);

            $driver->update([
                'status' => 'on_trip',
            ]);

            /**
             * 7️⃣ Update RR Pointer
             */
            RRPointer::updateOrCreate(
                ['vehicle_category_id' => $vehicleCategoryId],
                ['last_driver_id' => $driver->id]
            );

            /**
             * 8️⃣ Audit log
             */
            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'assigned',
                'notes'     => sprintf(
                    'Assigned queue %s',
                    $passenger->queue_number
                ),
            ]);

            return $assignment;
        });
    }
}