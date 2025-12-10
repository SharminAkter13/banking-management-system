<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountStatus extends Model
{
    protected $table = 'account_statuses';

    protected $fillable = ['name'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
