<?php

declare(strict_types=1);

namespace App\Http\Controllers\Allocation;

use App\Http\Controllers\Controller;
use App\Services\Allocation\RoundRobinService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class AssignPassengerController extends Controller
{
    public function __invoke(
        Request $request,
        RoundRobinService $roundRobinService
    ) {
        /**
         * 1️⃣ Validasi request
         */
        $validated = $request->validate([
            'vehicle_category_id' => [
                'required',
                'integer',
                'exists:vehicle_categories,id',
            ],
        ]);

        try {
            /**
             * 2️⃣ Panggil core business logic
             */
            $assignment = $roundRobinService->assign(
                (int) $validated['vehicle_category_id']
            );

            /**
             * 3️⃣ Response sukses (dipakai Display + Kiosk)
             */
            return response()->json([
                'status' => 'success',
                'data' => [
                    'queue_number' => $assignment->passengerQueue->queue_number,
                    'passenger_type' => $assignment
                        ->passengerQueue
                        ->passengerType
                        ->name,
                    'driver' => [
                        'id'    => $assignment->driver->id,
                        'name'  => $assignment->driver->name,
                        'code'  => $assignment->driver->driver_code,
                        'plate' => $assignment->driver->plate_number,
                    ],
                    'assigned_at' => $assignment->assigned_at,
                ],
            ], Response::HTTP_OK);

        } catch (RuntimeException $e) {

            /**
             * 4️⃣ Error logika (tidak ada driver / antrian)
             */
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);

        } catch (\Throwable $e) {

            /**
             * 5️⃣ Error tak terduga
             */
            report($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}