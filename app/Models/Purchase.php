<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id'];

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

}
