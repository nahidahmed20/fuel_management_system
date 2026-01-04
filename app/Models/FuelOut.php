<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelOut extends Model
{
    protected $guarded = ['id'];
    public function fuelType() { return $this->belongsTo(FuelType::class); }
    public function nozzle() { return $this->belongsTo(Nozzle::class); }
}
