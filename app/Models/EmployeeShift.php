<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'shift_id', 'emp_code', 'from_date', 'to_date', 'in_time', 'weekday', 'is_night' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function punch()
    {
        return $this->hasOne(Punch::class, 'emp_code', 'emp_code')->where('punch_date', $this->from_date);
    }
}
