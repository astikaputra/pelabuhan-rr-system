<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverOffDutyService
{
    public function offDuty(int $driverId): Driver
    {
        return DB::transaction(function () use ($driverId) {

            $driver = Driver::lockForUpdate()->find($driverId);

            if (! $driver) {
                throw new RuntimeException('Driver tidak ditemukan');
            }

            if (!in_array($driver->status, ['ready', 'break'])) {
                throw new RuntimeException('Driver tidak bisa keluar saat ini');
            }

            $driver->update([
                'status' => 'off_duty',
            ]);

            DriverLog::create([
                'driver_id' => $driver->id,
                'action' => 'off_duty',
                'notes' => 'Driver keluar / pulang',
            ]);

            return $driver;
        });
    }
}