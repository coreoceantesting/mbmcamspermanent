<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Ward extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'initial'];


    public function users()
    {
        return $this->hasMany(User::class, 'ward_id', 'id');
    }

    public static function booted()
    {
        static::created(function (Ward $ward)
        {
            self::where('id', $ward->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($ward->name)),
                'created_by'=> Auth::user()->id,
            ]);
        });
        static::updated(function (Ward $ward)
        {
            self::where('id', $ward->id)->update([
                'initial'=> preg_filter('/[^A-Z]/', '', ucwords($ward->name)),
                'updated_by'=> Auth::user()->id,
            ]);
        });
        static::deleted(function (Ward $ward)
        {
            self::where('id', $ward->id)->update([
                'deleted_by'=> Auth::user()->id,
            ]);
        });
    }
}
