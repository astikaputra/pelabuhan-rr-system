<?php

namespace App\Services\Display;

use App\Models\PassengerQueue;

final class DisplayWaitingQueueService
{
    public function getWaitingQueues(int $limit = 10): array
    {
        return PassengerQueue::query()
            ->with(['passengerType', 'vehicleCategory'])
            ->where('status', 'waiting')
            ->orderByDesc('passenger_type_id') // VIP/VVIP dulu
            ->orderBy('created_at')           // FIFO
            ->limit($limit)
            ->get()
            ->map(function ($queue) {
                return [
                    'queue_number'   => $queue->queue_number,
                    'passenger_type' => $queue->passengerType->name,
                    'vehicle'        => $queue->vehicleCategory->name,
                    'status'         => 'MENUNGGU',
                    'created_at'     => $queue->created_at->toDateTimeString(),
                ];
            })
            ->toArray();
    }
}