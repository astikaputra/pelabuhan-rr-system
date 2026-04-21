<?php

declare(strict_types=1);

namespace App\Services\Reporting;

use Illuminate\Support\Facades\DB;

final class ReportingService
{
    /**
     * Summary operasional harian
     */
    public function dailySummary(string $date): array
    {
        return [
            'date' => $date,

            'total_passengers' => DB::table('assignments')
                ->whereDate('assigned_at', $date)
                ->count(),

            'completed' => DB::table('assignments')
                ->whereDate('assigned_at', $date)
                ->where('status', 'completed')
                ->count(),

            'timeout' => DB::table('assignments')
                ->whereDate('assigned_at', $date)
                ->where('status', 'timeout')
                ->count(),
        ];
    }

    /**
     * Driver performance
     */
    public function driverPerformance(string $date): array
    {
        return DB::table('assignments')
            ->join('drivers', 'drivers.id', '=', 'assignments.driver_id')
            ->select(
                'drivers.name',
                DB::raw('COUNT(assignments.id) as total_assignments'),
                DB::raw("SUM(assignments.status = 'timeout') as total_timeout")
            )
            ->whereDate('assignments.assigned_at', $date)
            ->groupBy('drivers.id', 'drivers.name')
            ->orderByDesc('total_assignments')
            ->get()
            ->toArray();
    }

    /**
     * VIP report
     */
    public function vipReport(string $date): array
    {
        return DB::table('assignments')
            ->join('passenger_queues', 'passenger_queues.id', '=', 'assignments.passenger_queue_id')
            ->join('passenger_types', 'passenger_types.id', '=', 'passenger_queues.passenger_type_id')
            ->whereDate('assignments.assigned_at', $date)
            ->select(
                'passenger_types.name',
                'passenger_types.priority_level',
                DB::raw('COUNT(assignments.id) as total')
            )
            ->groupBy('passenger_types.id', 'passenger_types.name', 'passenger_types.priority_level')
            ->orderByDesc('passenger_types.priority_level')
            ->get()
            ->toArray();
    }

    /**
     * Peak hour analysis
     */
    public function peakHours(string $date): array
    {
        return DB::table('assignments')
            ->select(
                DB::raw('HOUR(assigned_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('assigned_at', $date)
            ->groupBy(DB::raw('HOUR(assigned_at)'))
            ->orderBy('hour')
            ->get()
            ->toArray();
    }

    /**
     * Driver violations (timeout)
     */
    public function violations(string $date): array
    {
        return DB::table('driver_logs')
            ->join('drivers', 'drivers.id', '=', 'driver_logs.driver_id')
            ->whereDate('driver_logs.log_time', $date)
            ->where('driver_logs.action', 'timeout')
            ->select(
                'drivers.name',
                DB::raw('COUNT(driver_logs.id) as total_timeout')
            )
            ->groupBy('drivers.id', 'drivers.name')
            ->having('total_timeout', '>', 0)
            ->orderByDesc('total_timeout')
            ->get()
            ->toArray();
    }
}