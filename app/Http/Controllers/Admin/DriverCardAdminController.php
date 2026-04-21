<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverCard;
use Illuminate\Http\Request;

class DriverCardAdminController extends Controller
{
    public function index($driverId)
    {
        return Driver::findOrFail($driverId)
            ->cards()
            ->orderByDesc('id')
            ->get();
    }

    public function store(Request $request, $driverId)
    {
        $data = $request->validate([
            'card_uid'  => 'required|string|unique:driver_cards,card_uid',
            'card_type' => 'required|in:RFID,BARCODE',
        ]);

        $data['driver_id'] = $driverId;
        $data['active'] = true;

        return DriverCard::create($data);
    }

    public function toggle($id)
    {
        $card = DriverCard::findOrFail($id);
        $card->update(['active' => ! $card->active]);

        return $card;
    }

    public function destroy($id)
    {
        DriverCard::findOrFail($id)->delete();
        return response()->json(['status' => 'deleted']);
    }
}
