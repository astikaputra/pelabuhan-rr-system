<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'driver_id',
        'action',
        'log_time',
        'notes',
    ];

    protected $casts = [
        'log_time' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}