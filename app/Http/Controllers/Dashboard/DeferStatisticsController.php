<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DeferStatisticsController extends Controller
{
    public function index()
    {
        return DB::table('driver_logs')
            ->select(
                'driver_id',
                DB::raw("SUM(action = 'defer_once') as requested"),
                DB::raw("SUM(action = 'defer_once_used') as used")
            )
            ->groupBy('driver_id')
            ->get();
    }
}