<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ['id']; 

    public function dues()
    {
        return $this->hasMany(CustomerDue::class);
    }

    public function duePayments()
    {
        return $this->hasMany(CustomerDuePayment::class);
    }
}
