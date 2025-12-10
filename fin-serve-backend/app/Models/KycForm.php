<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycForm extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'father_first_name',
        'father_last_name',
        'gender',
        'marital_status',
        'dob',
        'nationality',
        'status_type',
        'pan',
        'proof_identity',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'proof_address',
        'documents',
        'signature_path',
        'signed_date',
        'status',
    ];

    protected $casts = [
        'proof_address' => 'array',
        'documents' => 'array',
        'dob' => 'date',
        'signed_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
