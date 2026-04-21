<?php

declare(strict_types=1);

namespace App\Http\Controllers\Allocation;

use App\Http\Controllers\Controller;
use App\Services\Allocation\DriverTimeoutService;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class DriverTimeoutController extends Controller
{
    public function __invoke(
        Request $request,
        DriverTimeoutService $service
    ) {
        /**
         * 1️⃣ Validasi
         */
        $validated = $request->validate([
            'assignment_id' => 'required|integer|exists:assignments,id',
        ]);

        try {
            /**
             * 2️⃣ Timeout logic
             */
            $service->timeout(
                (int) $validated['assignment_id']
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Driver di-skip karena timeout',
            ], Response::HTTP_OK);

        } catch (RuntimeException $e) {

            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'status'  => 'error',
                'message' => 'Kesalahan sistem',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}