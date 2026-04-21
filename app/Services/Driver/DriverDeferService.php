<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverLog;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverDeferService
{
    public function deferOnce(int $driverId): Driver
    {
        return DB::transaction(function () use ($driverId) {

            $driver = Driver::lockForUpdate()->find($driverId);

            if (! $driver) {
                throw new RuntimeException('Driver tidak ditemukan');
            }

            if ($driver->status !== 'ready') {
                throw new RuntimeException('Tunda giliran hanya bisa saat READY');
            }

            if ($driver->defer_once) {
                throw new RuntimeException('Tunda giliran sudah digunakan');
            }

            $driver->update([
                'defer_once' => true,
            ]);

            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'defer_once',
                'notes'     => 'Driver menunda giliran satu kali',
            ]);

            return $driver;
        });
    }
}