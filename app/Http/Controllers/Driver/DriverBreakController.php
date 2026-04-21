<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverBreakService;
use Illuminate\Http\Request;
use RuntimeException;

final class DriverBreakController extends Controller
{
    public function __invoke(Request $request, DriverBreakService $service)
    {
        try {
            $validated = $request->validate([
                'driver_id' => 'required|integer|exists:drivers,id',
            ]);

            $driver = $service->break($validated['driver_id']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'driver_id' => $driver->id,
                    'status' => 'break',
                    'message' => 'Driver istirahat sementara',
                ],
            ], 200);

        } catch (RuntimeException $e) {
            // ✅ ERROR OPERASIONAL → JSON
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            // ✅ ERROR TAK TERDUGA → JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Kesalahan sistem',
            ], 500);
        }
    }
}