<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Designation extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'name', 'initial' ];


    public static function booted()
    {
        static::created(function (Designation $designation)
        {
            self::where('id', $designation->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($designation->name)),
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Designation $designation)
        {
            self::where('id', $designation->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($designation->name)),
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Designation $designation)
        {
            self::where('id', $designation->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
