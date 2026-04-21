<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PassengerType;
use Illuminate\Http\Request;

class PassengerTypeAdminController extends Controller
{
    public function index()
    {
        return PassengerType::orderByDesc('priority_level')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:passenger_types,name',
            'priority_level' => 'required|integer|min:0|max:100',
        ]);

        return PassengerType::create($data);
    }

    public function update(Request $request, int $id)
    {
        $type = PassengerType::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:passenger_types,name,' . $type->id,
            'priority_level' => 'required|integer|min:0|max:100',
        ]);

        $type->update($data);

        return $type;
    }

    public function destroy(int $id)
    {
        PassengerType::findOrFail($id)->delete();
        return response()->json(['status' => 'deleted']);
    }
}
