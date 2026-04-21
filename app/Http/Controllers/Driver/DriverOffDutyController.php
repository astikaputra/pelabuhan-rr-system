<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverOffDutyService;
use Illuminate\Http\Request;

final class DriverOffDutyController extends Controller
{
    public function __invoke(Request $request, DriverOffDutyService $service)
    {
        $validated = $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
        ]);

        $driver = $service->offDuty($validated['driver_id']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'driver_id' => $driver->id,
                'status' => 'off_duty',
                'message' => 'Driver keluar / pulang',
            ],
        ]);
    }
}
