<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = ['type_name','description'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function interestRates()
    {
        return $this->hasMany(InterestRate::class);
    }
}
