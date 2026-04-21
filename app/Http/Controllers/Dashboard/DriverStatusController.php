<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverStatusController extends Controller
{
    public function index()
    {
        return Driver::select('id', 'name', 'status', 'active')
            ->with('vehicleCategory')
            ->get();
    }
}