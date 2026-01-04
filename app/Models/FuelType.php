<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    protected $guarded = ['id'];
    public function nozzles()
    { 
        return $this->hasMany(Nozzle::class); 
    }
    public function stocks()
    { 
        return $this->hasMany(FuelStock::class); 
    }
    public function outs()
    { 
        return $this->hasMany(FuelOut::class); 
    }
}