<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends BaseModel
{
    use HasFactory, SoftDeletes;

    const LEAVE_FOR_TYPE_FULL_DAY = 1;
    const LEAVE_FOR_TYPE_HALF_DAY = 2;
    const LEAVE_FOR_TYPE_OUTPOST = 3;
    const LEAVE_STATUS_IS_PENDING = '0';
    const LEAVE_STATUS_IS_APPROVED = '1';
    const LEAVE_STATUS_IS_REJECTED = '2';

    protected $fillable = ['user_id', 'leave_type_id', 'from_date', 'to_date', 'no_of_days', 'remark', 'approver_remark', 'is_approved', 'approved_by', 'request_for_type', 'half_day_type'];


    public function document()
    {
        return $this->hasOne(LeaveRequestDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function path(): Attribute
    {
        return new Attribute(
            get: fn($value) => asset('storage/' . $value),
        );
    }
}
