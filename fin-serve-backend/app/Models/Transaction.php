<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id','transaction_type_id','transaction_status_id',
        'amount','transaction_date','created_by','notes'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loanPayment()
    {
        return $this->hasOne(LoanPayment::class);
    }
}
