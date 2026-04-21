<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverDeferService;
use Illuminate\Http\Request;
use RuntimeException;

final class DriverDeferController extends Controller
{
    public function __invoke(Request $request, DriverDeferService $service)
    {
        try {
            $validated = $request->validate([
                'driver_id' => 'required|integer|exists:drivers,id',
            ]);

            $driver = $service->deferOnce($validated['driver_id']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'driver_id' => $driver->id,
                    'status'    => 'ready',
                    'message'   => 'Giliran ditunda satu kali',
                ],
            ], 200);

        } catch (RuntimeException $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kesalahan sistem',
            ], 500);
        }
    }
}