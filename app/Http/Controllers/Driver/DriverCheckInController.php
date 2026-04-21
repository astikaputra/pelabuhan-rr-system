<?php

declare(strict_types=1);

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverCheckInService;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class DriverCheckInController extends Controller
{
    public function __invoke(
        Request $request,
        DriverCheckInService $service
    ) {
        /**
         * 1️⃣ Validasi input kiosk
         */
        $validated = $request->validate([
            'card_uid' => 'required|string',
        ]);

        try {
            /**
             * 2️⃣ Proses check-in
             */
            $driver = $service->checkIn($validated['card_uid']);

            /**
             * 3️⃣ Response sukses
             */
            return response()->json([
                'status' => 'success',
                'data' => [
                    'driver_id' => $driver->id,
                    'name'      => $driver->name,
                    'code'      => $driver->driver_code,
                    'vehicle'   => $driver->vehicleCategory->name,
                    'plate'     => $driver->plate_number,
                    'status'    => 'ready',
                ],
            ], Response::HTTP_OK);

        } catch (RuntimeException $e) {

            /**
             * 4️⃣ Error operasional (kartu / status)
             */
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);

        } catch (\Throwable $e) {

            /**
             * 5️⃣ Error sistem
             */
            report($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Kesalahan sistem',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
