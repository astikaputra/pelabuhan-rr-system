<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverAdminController extends Controller
{
    public function index()
    {
        return response()->json(
            Driver::with('vehicleCategory')->orderBy('name')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'driver_code' => 'required|string|unique:drivers,driver_code',
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
            'plate_number' => 'nullable|string',
        ]);

        return response()->json(
            Driver::create([
                ...$data,
                'status' => 'inactive',
                'active' => true
            ]),
            201
        );
    }

    public function update(Request $request, int $id)
    {
        $driver = Driver::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
            'plate_number' => 'nullable|string',
        ]);

        $driver->update($data);

        return response()->json($driver);
    }

    public function toggleStatus(int $id)
    {
        $driver = Driver::findOrFail($id);

        if ($driver->status === 'on_trip') {
            return response()->json([
                'message' => 'Driver sedang mengantar'
            ], 422);
        }

        $driver->update([
            'active' => ! $driver->active
        ]);

        return response()->json($driver);
    }
}