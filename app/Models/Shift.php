<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shift extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'name', 'from_time', 'to_time' ];

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public static function booted()
    {
        static::created(function (Shift $shift)
        {
            self::where('id', $shift->id)->update([
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Shift $shift)
        {
            self::where('id', $shift->id)->update([
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Shift $shift)
        {
            self::where('id', $shift->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
