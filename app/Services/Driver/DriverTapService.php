<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverCard;
use App\Models\DriverLog;
use App\Services\Allocation\RoundRobinService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DriverTapService
{
    public function tap(string $cardUid): array
    {
        return DB::transaction(function () use ($cardUid) {

            // 1️⃣ Validasi kartu
            $card = DriverCard::query()
                ->where('card_uid', $cardUid)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (! $card) {
                throw new RuntimeException('Kartu driver tidak valid');
            }

            // 2️⃣ Ambil driver
            $driver = Driver::query()
                ->where('id', $card->driver_id)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (! $driver) {
                throw new RuntimeException('Driver tidak aktif');
            }

            /**
             * 3️⃣ SMART SWITCH BASED ON STATUS
             */
            if ($driver->status === 'on_trip') {
                // ✅ CHECKOUT
                app(DriverCheckOutService::class)
                    ->checkOut($driver->id);

                return [
                    'action' => 'checkout',
                    'driver_id' => $driver->id,    
                    'status' => 'ready',
                    'message' => 'Perjalanan selesai, silakan menunggu antrian',
                ];
            }

            // ✅ CHECK-IN
            if ($driver->status !== 'ready') {
                $driver->update([
                    'status' => 'ready',
                ]);
            }

            DriverLog::create([
                'driver_id' => $driver->id,
                'action'    => 'tap_in',
                'notes'     => 'Driver tap kartu (check-in)',
            ]);

            // ✅ AUTO ASSIGN JIKA ADA PENUMPANG
            try {
                app(RoundRobinService::class)
                    ->assign($driver->vehicle_category_id);
            } catch (\Throwable $e) {
                // belum ada penumpang → aman
            }

            return [
                'action' => 'checkin',
                'driver_id' => $driver->id,
                'status' => 'ready',
                'message' => 'Anda siap menerima penumpang',
            ];
        });
    }
}