<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTracker extends Model
{
    use HasFactory;

    public function vehicle () {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
