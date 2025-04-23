<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalHierarchy extends Model
{
    use HasFactory;

    protected $fillable = [
        'hierarchy_id',
        'leave_request_id',
        'requester_user_id',
        'requester_designation_id',
        'requester_department_id',
        'approver_user_id',
        'approver_designation_id',
        'approver_department_id',
        'status',
    ];

    public function requestHierarchy()
    {
        return $this->belongsTo(LeaveRequestHierarchy::class, 'hierarchy_id', 'id');
    }


}
