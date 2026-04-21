<?php

declare(strict_types=1);

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverCheckOutService;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class DriverCheckOutController extends Controller
{
    public function __invoke(
        Request $request,
        DriverCheckOutService $service
    ) {
        /**
         * 1️⃣ Validasi input
         */
        $validated = $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
        ]);

        try {
            /**
             * 2️⃣ Proses check-out
             */
            $driver = $service->checkOut(
                (int) $validated['driver_id']
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'driver_id' => $driver->id,
                    'name'      => $driver->name,
                    'status'    => 'ready',
                ],
            ], Response::HTTP_OK);

        } catch (RuntimeException $e) {

            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Kesalahan sistem',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}