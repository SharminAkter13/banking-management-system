<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'loan_id','due_date','amount_due','amount_paid','status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
