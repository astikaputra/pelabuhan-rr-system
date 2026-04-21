<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class VehicleCategoryAdminController extends Controller
{
    public function index()
    {
        return VehicleCategory::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:vehicle_categories,name',
        ]);

        return VehicleCategory::create($data);
    }

    public function update(Request $request, int $id)
    {
        $category = VehicleCategory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:vehicle_categories,name,' . $category->id,
        ]);

        $category->update($data);

        return $category;
    }

    public function toggle(int $id)
    {
        $category = VehicleCategory::findOrFail($id);
        $category->update(['active' => ! $category->active]);

        return $category;
    }
}