<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LeaveType extends BaseModel
{
    use HasFactory, SoftDeletes;

    const IS_MEDICAL_LEAVE_PAID = '1';

    protected $fillable = ['name', 'initial', 'is_paid'];

    public function leave()
    {
        return $this->hasOne(Leave::class);
    }


    public static function booted()
    {
        static::created(function (LeaveType $leaveType)
        {
            self::where('id', $leaveType->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($leaveType->name)),
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (LeaveType $leaveType)
        {
            self::where('id', $leaveType->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($leaveType->name)),
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (LeaveType $leaveType)
        {
            self::where('id', $leaveType->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
