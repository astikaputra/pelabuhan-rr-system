<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DriverStatsController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $date = $request->query('date', now()->toDateString());

            $drivers = Driver::with('vehicleCategory')->get();
            $result = [];

            foreach ($drivers as $driver) {

                /**
                 * =========================
                 * HITUNG TRIP SELESAI
                 * =========================
                 */
                $assignments = DB::table('assignments')
                    ->where('driver_id', $driver->id)
                    ->where('status', 'completed')
                    ->whereDate('assigned_at', $date)
                    ->whereNotNull('assigned_at')
                    ->whereNotNull('completed_at')
                    ->get();

                $tripCount   = 0;
                $tripSeconds = 0;

                foreach ($assignments as $a) {
                    $start = strtotime($a->assigned_at);
                    $end   = strtotime($a->completed_at);

                    if ($start !== false && $end !== false && $end > $start) {
                        $tripCount++;
                        $tripSeconds += ($end - $start);
                    }
                }

                /**
                 * =========================
                 * HITUNG BREAK (log_time)
                 * =========================
                 */
                $breakCount = DB::table('driver_logs')
                    ->where('driver_id', $driver->id)
                    ->where('action', 'break')
                    ->whereDate('log_time', $date) // ✅ SESUAI KONFIRMASI ANDA
                    ->count();

                $result[] = [
                    'driver_id'     => $driver->id,
                    'name'          => $driver->name,
                    'vehicle'       => $driver->vehicleCategory->name ?? '-',
                    'status'        => $driver->status,
                    'trip_count'    => $tripCount,
                    'trip_minutes'  => round($tripSeconds / 60),
                    'break_count'   => $breakCount,
                    'break_minutes' => $breakCount * 15, // asumsi
                ];
            }

            return response()->json([
                'status' => 'success',
                'date'   => $date,
                'data'   => $result,
            ]);

        } catch (\Throwable $e) {

            // ✅ WAJIB ADA UNTUK DEBUG
            Log::error('DriverStatsController error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'data'    => [],
                'message' => 'Gagal memuat statistik driver',
            ], 500);
        }
    }
}