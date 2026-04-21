<?php

declare(strict_types=1);

namespace App\Services\Allocation;

use App\Models\Assignment;
use App\Models\Driver;
use App\Models\DriverLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverTimeoutService
{
    /**
     * Timeout driver yang tidak datang
     */
    public function timeout(int $assignmentId): void
    {
        DB::transaction(function () use ($assignmentId) {

            /**
             * 1️⃣ Ambil assignment aktif
             */
            $assignment = Assignment::query()
                ->where('id', $assignmentId)
                ->where('status', 'assigned')
                ->lockForUpdate()
                ->first();

            if (! $assignment) {
                throw new RuntimeException('Assignment tidak valid');
            }

            /**
             * 2️⃣ Ambil driver
             */
            $driver = Driver::query()
                ->where('id', $assignment->driver_id)
                ->lockForUpdate()
                ->first();

            if (! $driver) {
                throw new RuntimeException('Driver tidak ditemukan');
            }

            /**
             * 3️⃣ Tandai assignment TIMEOUT
             */
            $assignment->update([
                'status'     => 'timeout',
                'timeout_at' => now(),
            ]);

            /**
             * 4️⃣ Driver kembali READY
             * (tetap masuk RR, tapi sudah dipindah ke belakang via pointer)
             */
            $driver->update([
                'status' => 'ready',
            ]);

            /**
             * 5️⃣ Audit log
             */
            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'timeout',
                'notes'     => 'Driver timeout tidak datang',
            ]);
        });
    }
}