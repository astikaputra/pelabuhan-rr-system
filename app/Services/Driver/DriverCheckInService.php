<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverCard;
use App\Models\DriverLog;
//use App\Services\RoundRobinService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverCheckInService
{
    public function checkIn(string $cardUid): Driver
    {
        return DB::transaction(function () use ($cardUid) {

            /**
             * 1️⃣ Cari kartu driver
             */
            $card = DriverCard::query()
                ->where('card_uid', $cardUid)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (! $card) {
                throw new RuntimeException('Kartu driver tidak valid');
            }

            /**
             * 2️⃣ Ambil driver
             */
            $driver = Driver::query()
                ->where('id', $card->driver_id)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (! $driver) {
                throw new RuntimeException('Driver tidak aktif');
            }

            /**
             * 3️⃣ Cegah double check-in
             */
            if ($driver->status === 'ready') {
                throw new RuntimeException('Driver sudah READY');
            }

            if ($driver->status === 'on_trip') {
                throw new RuntimeException('Driver sedang mengantar');
            }

            /**
             * 4️⃣ Update status driver → READY
             */
            $driver->update([
                'status' => 'ready',
            ]);

            /**
             * 5️⃣ Audit log
             */
            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'tap_in',
                'notes'     => 'Driver check-in via kiosk',
            ]);
            
            /**
             * 5️⃣ === AUTO ASSIGN (OPSI 1) ===
             * Jika ada penumpang menunggu, langsung assign
             */
            // try {
            //     app(RoundRobinService::class)
            //         ->assign($driver->vehicle_category_id);
            // } catch (\Throwable $e) {
            //     // 🔕 Diamkan:
            //     // - belum ada penumpang
            //     // - atau kondisi RR belum siap
            // }


            return $driver;
        });
    }
}
