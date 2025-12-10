<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = [
        'approval_id','step_number','role_id','status',
        'required','approved_at'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function actions()
    {
        return $this->hasMany(ApprovalAction::class);
    }
}
