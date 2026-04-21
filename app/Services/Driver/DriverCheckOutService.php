<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Models\Assignment;
use App\Models\Driver;
use App\Models\DriverLog;
use App\Services\RoundRobinService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverCheckOutService
{
    public function checkOut(int $driverId): Driver
    {
        return DB::transaction(function () use ($driverId) {

            /**
             * 1️⃣ Ambil driver dan lock
             */
            $driver = Driver::query()
                ->where('id', $driverId)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (! $driver) {
                throw new RuntimeException('Driver tidak ditemukan');
            }

            if ($driver->status !== 'on_trip') {
                throw new RuntimeException('Driver tidak dalam status antar');
            }

            /**
             * 2️⃣ Ambil assignment aktif driver
             */
            $assignment = Assignment::query()
                ->where('driver_id', $driver->id)
                ->where('status', 'assigned')
                ->lockForUpdate()
                ->first();

            if (! $assignment) {
                throw new RuntimeException('Assignment aktif tidak ditemukan');
            }

            /**
             * 3️⃣ Selesaikan assignment
             */
            $assignment->update([
                'status'        => 'completed',
                'completed_at'  => now(),
            ]);

            /**
             * 4️⃣ Driver kembali READY
             */
            $driver->update([
                'status' => 'ready',
            ]);

            /**
             * 5️⃣ Audit log
             */
            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'completed',
                'notes'     => 'Driver selesai antar penumpang',
            ]);

            
            /**
             * 6️⃣ === AUTO ASSIGN SETELAH CHECKOUT ===
             * Assign penumpang berikutnya jika ada
             */
            try {
                app(RoundRobinService::class)
                    ->assign($driver->vehicle_category_id);
            } catch (\Throwable $e) {
                // 🔕 Tidak ada penumpang / driver siap
            }


            return $driver;
        });
    }
}