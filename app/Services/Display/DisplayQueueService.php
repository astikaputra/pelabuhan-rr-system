<?php

declare(strict_types=1);

namespace App\Services\Display;

use App\Models\Assignment;

final class DisplayQueueService
{
    /**
     * Ambil data antrian aktif untuk display
     */
    public function getActiveQueues(int $limit = 10): array
    {
        $assignments = Assignment::query()
            ->with([
                'passengerQueue.passengerType',
                'driver.vehicleCategory',
            ])
            ->where('status', 'assigned')
            ->orderByDesc('assigned_at')
            ->limit($limit)
            ->get();

        return $assignments->map(function ($assignment) {
            return [
                'queue_number'    => $assignment->passengerQueue->queue_number,
                'passenger_type'  => $assignment->passengerQueue->passengerType->name,
                'priority_level'  => $assignment->passengerQueue->passengerType->priority_level,
                'driver_name'     => $assignment->driver->name,
                'driver_code'     => $assignment->driver->driver_code,
                'vehicle'         => $assignment->driver->vehicleCategory->name,
                'plate_number'    => $assignment->driver->plate_number,
                'photo_driver'    => $assignment->driver->photo_driver,
                'photo_vehicle'   => $assignment->driver->photo_vehicle,
                'assigned_at'     => $assignment->assigned_at->toDateTimeString(),
            ];
        })->toArray();
    }
}
