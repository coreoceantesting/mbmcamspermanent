<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WardDevice extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'ward_id', 'device_id' ];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'DeviceId');
    }
}
