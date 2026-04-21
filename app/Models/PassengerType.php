<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerType extends Model
{
    protected $fillable = ['name', 'priority_level', 'active'];
}