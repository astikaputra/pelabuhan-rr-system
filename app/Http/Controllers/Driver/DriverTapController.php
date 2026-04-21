<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverTapService;
use Illuminate\Http\Request;

final class DriverTapController extends Controller
{
    public function __invoke(Request $request, DriverTapService $service)
    {
        $validated = $request->validate([
            'card_uid' => 'required|string',
        ]);

        $result = $service->tap($validated['card_uid']);

        return response()->json([
            'status' => 'success',
            'data'   => $result,
        ]);
    }
}