<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTypes extends Model
{
    protected $fillable = ['account_name','description'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function interestRates()
    {
        return $this->hasMany(InterestRate::class);
    }
}
