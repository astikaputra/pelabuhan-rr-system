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
     * Assign 1 passenger (VIP aware) to next available driver (Round Robin)
     */
    public function assign(int $vehicleCategoryId): Assignment
    {
        return DB::transaction(function () use ($vehicleCategoryId) {

            /**
             * 1️⃣ Ambil penumpang paling prioritas (VIP/VVIP dulu)
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
             * 2️⃣ Ambil pointer Round Robin untuk kategori
             */
            $pointer = RRPointer::query()
                ->where('vehicle_category_id', $vehicleCategoryId)
                ->lockForUpdate()
                ->first();

            $lastDriverId = $pointer?->last_driver_id;

            /**
             * 3️⃣ Cari driver READY berikutnya (Round Robin)
             */
            $driver = Driver::query()
                ->where('vehicle_category_id', $vehicleCategoryId)
                ->where('status', 'ready')
                ->where('active', true)
                ->when(
                    $lastDriverId,
                    fn ($q) => $q->where('id', '>', $lastDriverId)
                )
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            /**
             * Jika di atas last_driver_id tidak ada → kembali ke awal
             */
            if (! $driver) {
                $driver = Driver::query()
                    ->where('vehicle_category_id', $vehicleCategoryId)
                    ->where('status', 'ready')
                    ->where('active', true)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->first();
            }

            if (! $driver) {
                throw new RuntimeException('Tidak ada driver yang siap');
            }

            /**
             * 4️⃣ Buat assignment
             */
            $assignment = Assignment::create([
                'passenger_queue_id' => $passenger->id,
                'driver_id'          => $driver->id,
                'status'             => 'assigned',
            ]);

            /**
             * 5️⃣ Update passenger & driver status
             */
            $passenger->update([
                'status' => 'assigned',
            ]);

            $driver->update([
                'status' => 'on_trip',
            ]);

            /**
             * 6️⃣ Update / create RR pointer
             */
            RRPointer::updateOrCreate(
                ['vehicle_category_id' => $vehicleCategoryId],
                ['last_driver_id' => $driver->id]
            );

            /**
             * 7️⃣ Audit log
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