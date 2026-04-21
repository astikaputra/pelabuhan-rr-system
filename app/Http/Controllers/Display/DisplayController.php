<?php

declare(strict_types=1);

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use App\Services\Display\DisplayQueueService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class DisplayController extends Controller
{
    public function __invoke(
        Request $request,
        DisplayQueueService $service
    ) {
        $limit = (int) $request->query('limit', 10);

        $data = $service->getActiveQueues($limit);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'server_time' => now()->toDateTimeString(),
        ], Response::HTTP_OK);
    }
}
