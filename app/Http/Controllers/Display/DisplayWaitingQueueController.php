<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use App\Services\Display\DisplayWaitingQueueService;
use Illuminate\Http\Request;

final class DisplayWaitingQueueController extends Controller
{
    public function __invoke(
        Request $request,
        DisplayWaitingQueueService $service
    ) {
        $limit = (int) $request->query('limit', 10);

        return response()->json([
            'status' => 'success',
            'data'   => $service->getWaitingQueues($limit),
            'server_time' => now()->toDateTimeString(),
        ]);
    }
}