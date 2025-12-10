<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $fillable = [
        'customer_id','branch_id','account_type_id','account_status_id',
        'balance','opened_date','closed_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function status()
    {
        return $this->belongsTo(AccountStatus::class, 'account_status_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
