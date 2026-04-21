<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\PassengerQueue;
use App\Models\PassengerType;
use App\Services\Allocation\RoundRobinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class PassengerQueueController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'vehicle_category_id' => 'required|integer|exists:vehicle_categories,id',
            'passenger_type_id'   => 'required|integer|exists:passenger_types,id',
        ]);

        $today = now()->toDateString();

        $prefixMap = [
            1 => 'A', // Motor
            2 => 'B', // Mobil
            3 => 'C', // Bus
        ];

        $prefix = $prefixMap[$validated['vehicle_category_id']] ?? 'A';

        /**
         * ✅ TRANSACTION KHUSUS BUAT CREATE ANTRIAN
         * DAN RETURN NILAINYA
         */
        $queue = DB::transaction(function () use ($validated, $prefix, $today) {

            $lastQueue = PassengerQueue::query()
                ->where('vehicle_category_id', $validated['vehicle_category_id'])
                ->where('queue_date', $today)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $nextNumber = $lastQueue
                ? intval(preg_replace('/\D/', '', $lastQueue->queue_number)) + 1
                : 1;

            if ($nextNumber > 999) {
                $nextNumber = 1;
            }

            $queueNumber = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            return PassengerQueue::create([
                'queue_number'        => $queueNumber,
                'vehicle_category_id' => $validated['vehicle_category_id'],
                'passenger_type_id'   => $validated['passenger_type_id'],
                'queue_date'          => $today,   // ✅ WAJIB
                'status'              => 'waiting',
            ]);
        });

        /**
         * ✅ AUTO ASSIGN DRIVER (DI LUAR TRANSACTION)
         * TIDAK BOLEH MENGGAGALKAN AMBIL NOMOR
         */
        try {
            app(RoundRobinService::class)
                ->assign($validated['vehicle_category_id']);
        } catch (\Throwable $e) {
            // aman, belum ada driver ready
        }

        $type = PassengerType::find($validated['passenger_type_id']);

        return response()->json([
            'status'         => 'success',
            'queue_number'   => $queue->queue_number,
            'passenger_type' => $type->name,
            'message'        => 'Silakan menunggu panggilan',
        ], Response::HTTP_CREATED);
    }
}