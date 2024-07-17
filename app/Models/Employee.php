<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Employee extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'app_employees';
    protected $fillable = ['user_id', 'shift_id', 'ward_id', 'clas_id', 'designation_id', 'doj', 'is_ot', 'is_divyang', 'permanent_address', 'present_address'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function clas()
    {
        return $this->belongsTo(Clas::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }




    public static function booted()
    {
        static::created(function (Employee $employee)
        {
            EmployeeWeekoff::create([
                'user_id' => $employee->user_id,
                'weekoff_1' => 'saturday',
                'weekoff_2' => 'sunday',
            ]);
        });
    }

}
