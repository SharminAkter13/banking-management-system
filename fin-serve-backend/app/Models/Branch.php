<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'branch_name','location','manager_id','contact'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
