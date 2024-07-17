<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Punch extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'punch_date' => 'date',
    ];

    const PUNCH_BY_SYSTEM = '0';
    const PUNCH_BY_MANUAL = '1';
    const PUNCH_BY_ADJUSTMENT = '2';
    const PUNCH_TYPE_PRESENT = '0';
    const PUNCH_TYPE_LEAVE = '1';
    const PUNCH_TYPE_HALFDAY_LEAVE = '2';
    const PUNCH_TYPE_HOLIDAY = '3';
    const PUNCH_TYPE_SAT_SUN = '4';
    const PUNCH_IS_PAID = '1';
    const PUNCH_IS_UNPAID = '0';

    protected $appends = ['duration_in_minute'];

    protected $fillable = [ 'emp_code', 'device_id', 'check_in', 'check_out', 'punch_date', 'duration', 'punch_by', 'type', 'is_paid', 'is_latemark', 'is_latemark_updated', 'is_duration_updated', 'leave_type_id' ];


    public function user()
    {
        return $this->belongsTo(User::class, 'emp_code', 'emp_code');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'DeviceId');
    }

    public function empShift()
    {
        return $this->belongsTo(EmployeeShift::class, 'emp_code', 'emp_code')->where('from_date', $this->punch_date);
    }

    public function getDurationInMinuteAttribute()
    {
        return gmdate("H:i", $this->duration);
    }


}
