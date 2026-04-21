<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverDashboardController extends Controller
{
    public function __invoke()
    {
        $drivers = Driver::with('vehicleCategory')
            ->orderBy('vehicle_category_id')
            ->orderBy('name')
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'code' => $d->driver_code,
                    'vehicle' => $d->vehicleCategory->name ?? '-',
                    'plate' => $d->plate_number,
                    'status' => $d->status, // ready | on_trip | inactive
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $drivers,
            'counts' => [
                'ready' => $drivers->where('status', 'ready')->count(),
                'on_trip' => $drivers->where('status', 'on_trip')->count(),
                'inactive' => $drivers->where('status', 'inactive')->count(),
            ],
        ]);
    }
}