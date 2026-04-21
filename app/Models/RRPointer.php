<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RRPointer extends Model
{
    
    // ✅ TAMBAHKAN BARIS INI (INTI FIX)
    protected $table = 'rr_pointers';

    public $timestamps = false;

    protected $fillable = [
        'vehicle_category_id',
        'last_driver_id',
    ];
}