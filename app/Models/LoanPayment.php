<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $guarded = ['id'];

    public function borrower() {
        return $this->belongsTo(Borrower::class);
    }
    
}
