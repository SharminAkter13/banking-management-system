<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestRate extends Model
{
    protected $fillable = ['account_type_id','rate','effective_date'];

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }
}
