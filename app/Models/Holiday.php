<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Holiday extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'year', 'date', 'remark' ];


    public static function booted()
    {
        static::created(function (Holiday $holiday)
        {
            self::where('id', $holiday->id)->update([
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Holiday $holiday)
        {
            self::where('id', $holiday->id)->update([
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Holiday $holiday)
        {
            self::where('id', $holiday->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
