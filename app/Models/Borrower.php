<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $guarded = ['id'];

    public function loans() {
        return $this->hasMany(Loan::class);
    }

    public function payments() {
        return $this->hasMany(LoanPayment::class,'borrower_id');
    }
}
