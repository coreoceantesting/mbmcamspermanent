<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserLeave;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'app_users';

    protected $appends = [ 'tenant_name', 'gender_text' ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'device_id',
        'ward_id',
        'department_id',
        'sub_department_id',
        'emp_code',
        'in_time',
        'name',
        'email',
        'mobile',
        'aadhaar_no',
        'dob',
        'gender',
        'password',
        'is_app_registered',
        'is_employee',
        'employee_type',
        'contractor_name',
        'shift_id',
        'designation_id',
        'clas_id',
        'doj',
        'is_ot',
        'is_divyang',
        'is_rotational',
        'work_duration',
        'sa_duration',
        'permanent_address',
        'present_address',
        'contractor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames();
    }
    public function getGenderTextAttribute()
    {
        return $this->gender == 'm' ? 'Male' : 'Female';
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getTenantNameAttribute()
    {
        return $this->tenant?->name ?? 'Mbmc';
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subDepartment()
    {
        return $this->belongsTo(Department::class, 'sub_department_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class)->withTrashed();
    }

    public function empShift()
    {
        return $this->hasOne(EmployeeShift::class)->latestOfMany();
    }

    public function empShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function clas()
    {
        return $this->belongsTo(Clas::class)->withTrashed();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class)->withTrashed();
    }

    public function punches()
    {
        return $this->hasMany(Punch::class, 'emp_code', 'emp_code');
    }

    public function weekoff()
    {
        return $this->hasOne(EmployeeWeekoff::class);
    }

    public function latestWeekoff()
    {
        return $this->hasOne(EmployeeWeekoff::class)->latestOfMany();
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'DeviceId');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function contractor()
    {
        return $this->hasOne(Contractor::class , 'id', 'contractor_id');
    }


    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_departments', 'user_id', 'department_id');
    }


    public function userLeaves()
    {
        return $this->hasMany(UserLeave::class, 'user_id');
    }



    public static function booted()
    {
        static::created(function (self $user)
        {
            if(Auth::check())
            {
                self::where('id', $user->id)->update([
                    'created_by'=> Auth::user()->id,
                ]);
            }
        });
        static::updated(function (self $user)
        {
            if(Auth::check())
            {
                self::where('id', $user->id)->update([
                    'updated_by'=> Auth::user()->id,
                ]);
            }
        });
        static::deleting(function (self $user)
        {
            if(Auth::check())
            {
                $user->update([
                    'deleted_by'=> Auth::user()->id,
                ]);
            }
        });
    }
}
