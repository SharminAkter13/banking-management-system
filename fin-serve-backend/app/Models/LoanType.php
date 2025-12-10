<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $fillable = ['name','description','interest_rate','max_amount'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
