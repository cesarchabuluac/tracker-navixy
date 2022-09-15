<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    public function trackings(): HasMany
    {
        return $this->hasMany(VehicleTracker::class, 'vehicle_id');
    }
}
