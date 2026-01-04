<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded =['id'];

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function outs()
    {
        return $this->hasMany(ProductOut::class);
    }
}
