<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NozzleMeter extends Model
{
    protected $guarded = ['id'];

    public function nozzle()
    {
        return $this->belongsTo(Nozzle::class);
    }
}
