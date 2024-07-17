<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Leave extends BaseModel
{
    use HasFactory, SoftDeletes;

    CONST LEAVE_TYPE_PAID = 0;
    CONST LEAVE_TYPE_UNPAID = 1;

    protected $fillable = ['leave_type_id', 'days'];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public static function booted()
    {
        static::created(function (Leave $leave)
        {
            self::where('id', $leave->id)->update([
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Leave $leave)
        {
            self::where('id', $leave->id)->update([
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Leave $leave)
        {
            self::where('id', $leave->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
