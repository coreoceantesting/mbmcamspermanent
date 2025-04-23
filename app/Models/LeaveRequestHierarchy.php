<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestHierarchy extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'requester_designation_id',
        '1_approver_designation_id',
        '2_approver_designation_id',
        '3_approver_designation_id',
        '4_approver_designation_id',
    ];
}
