<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'entity_type','entity_id','current_step','status','created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function steps()
    {
        return $this->hasMany(ApprovalStep::class);
    }
}
