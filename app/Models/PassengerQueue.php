<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerQueue extends Model
{
    protected $fillable  = [
        'queue_number',
        'vehicle_category_id',
        'passenger_type_id',
        'queue_date', 
        'status',
    ];

    public function passengerType()
    {
        return $this->belongsTo(PassengerType::class);
    }

    public function vehicleCategory()
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }
}