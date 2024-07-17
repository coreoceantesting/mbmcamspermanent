<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Device extends BaseModel
{
    use HasFactory;

    protected $table = 'Devices';
    protected $primaryKey = 'DeviceId';
    public $timestamps = false;

    protected $fillable = [ 'DeviceFName', 'DevicesName', 'DeviceDirection', 'SerialNumber', 'ConnectionType', 'IpAddress', 'CommKey', 'TransactionStamp', 'DeviceType', 'OpStamp', 'DownLoadType', 'TimeZone', 'DeviceLocation', 'TimeOut' ];

    // public function ward() : HasOneThrough
    // {
    //     return $this->hasOneThrough(Ward::class, WardDevice::class, 'ward_id', 'id', 'id', 'device_id');
    // }

    public function wardDevice() : HasOne
    {
        return $this->hasOne(WardDevice::class, 'device_id', 'DeviceId');
    }
}
