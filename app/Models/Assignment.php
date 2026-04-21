<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'passenger_queue_id',
        'driver_id',
        'assigned_at',
        'completed_at',
        'timeout_at',
        'status',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'completed_at' => 'datetime',
        'timeout_at'   => 'datetime',
    ];

    public function passengerQueue()
    {
        return $this->belongsTo(PassengerQueue::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}