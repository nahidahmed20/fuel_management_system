<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelStock extends Model
{
    protected $guarded =['id'];

    public function fuelType() 
    { 
        return $this->belongsTo(FuelType::class); 
    }
}
