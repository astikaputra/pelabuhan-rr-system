<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use App\Services\Reporting\ReportingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ReportingController extends Controller
{
    public function summary(Request $request, ReportingService $service)
    {
        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'status' => 'success',
            'data' => $service->dailySummary($date),
        ], Response::HTTP_OK);
    }

    public function drivers(Request $request, ReportingService $service)
    {
        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'status' => 'success',
            'data' => $service->driverPerformance($date),
        ]);
    }

    public function vip(Request $request, ReportingService $service)
    {
        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'status' => 'success',
            'data' => $service->vipReport($date),
        ]);
    }

    public function peakHours(Request $request, ReportingService $service)
    {
        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'status' => 'success',
            'data' => $service->peakHours($date),
        ]);
    }

    public function violations(Request $request, ReportingService $service)
    {
        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'status' => 'success',
            'data' => $service->violations($date),
        ]);
    }
}