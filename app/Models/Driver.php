<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'driver_code',
        'vehicle_category_id',
        'plate_number',
        'photo_driver',
        'photo_vehicle',
        'status',
        'active',
    ];

    public function vehicleCategory()
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function cards()
    {
        return $this->hasMany(DriverCard::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
