<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCard extends Model
{
    protected $table = 'driver_cards';

    protected $fillable = [
        'driver_id',
        'card_uid',
        'card_type',
        'active',
    ];

    public $timestamps = false;

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}