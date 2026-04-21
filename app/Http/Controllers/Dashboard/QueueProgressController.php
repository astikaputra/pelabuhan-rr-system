<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PassengerQueue;

class QueueProgressController extends Controller
{
    public function index()
    {
        return PassengerQueue::where('status', 'waiting')
            ->orderBy('queue_number')
            ->get();
    }
}
