<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverBreakService
{
    public function break(int $driverId): Driver
    {
        return DB::transaction(function () use ($driverId) {

            $driver = Driver::lockForUpdate()->find($driverId);

            if (! $driver) {
                throw new RuntimeException('Driver tidak ditemukan');
            }

            if ($driver->status !== 'ready') {
                throw new RuntimeException('Driver tidak bisa istirahat saat ini');
            }

            $driver->update([
                'status' => 'break',
            ]);

            DriverLog::create([
                'driver_id' => $driver->id,
                'action' => 'break',
                'notes' => 'Driver istirahat sementara',
            ]);

            return $driver;
        });
    }
}