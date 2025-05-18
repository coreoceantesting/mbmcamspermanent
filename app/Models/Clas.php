<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Clas extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'initial', 'retirement_age'];

    public static function booted()
    {
        static::created(function (Clas $clas)
        {
            self::where('id', $clas->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($clas->name)),
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Clas $clas)
        {
            self::where('id', $clas->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($clas->name)),
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Clas $clas)
        {
            self::where('id', $clas->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
