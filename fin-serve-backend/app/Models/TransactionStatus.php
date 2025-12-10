<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    protected $table = 'transaction_statuses';

    protected $fillable = ['name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
