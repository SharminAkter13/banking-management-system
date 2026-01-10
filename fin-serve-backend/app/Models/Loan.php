<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
   protected $fillable = [
    'customer_id',
    'branch_id',
    'loan_type_id',
    'principal_amount',
    'interest_rate',
    'emi_amount',
    'total_payable',
    'remaining_balance',
    'duration_months',
    'issued_date',
    'status'
];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class);
    }
}
