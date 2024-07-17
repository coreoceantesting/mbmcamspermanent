<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class Department extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'department_id', 'name', 'initial', 'level' ];

    public function parentDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function childDepartments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function booted()
    {
        static::created(function (Department $department)
        {
            self::where('id', $department->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($department->name)),
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Department $department)
        {
            self::where('id', $department->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($department->name)),
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Department $department)
        {
            self::where('id', $department->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }


}
