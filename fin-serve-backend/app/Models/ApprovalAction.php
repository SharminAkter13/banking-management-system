<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalAction extends Model
{
    protected $fillable = [
        'approval_step_id','approved_by','action','comments'
    ];

    public function step()
    {
        return $this->belongsTo(ApprovalStep::class, 'approval_step_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
