<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\Ward;
use App\Models\WardDevice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeviceRepository
{

    public function store($input)
    {
        DB::beginTransaction();
        $input['DeviceDirection'] = 'altinout';
        $input['ConnectionType'] = 'Tcp/IP';
        $input['CommKey'] = '0';
        $input['TransactionStamp'] = '9999';
        $input['DeviceType'] = 'Attendance';
        $input['OpStamp'] = '9999';
        $input['DownLoadType'] = '1';
        $input['TimeZone'] = '330';
        $input['TimeOut'] = '300';
        $device = Device::create( Arr::only( $input, Device::getFillables() ) );
        WardDevice::create([ 'ward_id'=> $input['ward_id'], 'device_id'=> $device->DeviceId ]);
        DB::commit();

        return $device;
    }

    public function editDevice($device)
    {
        $wards = Ward::orderBy('name')->get();
        $device->load('wardDevice');
        if ($device)
        {
            $wardHtml = '
                <option value="">--Select Office--</option>';
                foreach($wards as $ward):
                    $is_select = $ward->id == $device->wardDevice?->ward_id ? "selected" : "";
                    $wardHtml .= '<option value="'.$ward->id.'" '.$is_select.'>'.$ward->name.'</option>';
                endforeach;

            $response = [
                'wardHtml'=> $wardHtml,
                'result' => 1,
                'device' => $device
            ];
        }
        else
        {
            $response = ['result' => 0];
        }
        return $response;
    }

    public function updateDevice($input, $device)
    {
        DB::beginTransaction();
        $device->update( Arr::only( $input, Device::getFillables() ) );
        WardDevice::where('device_id', $device->id)->delete();
        WardDevice::create([ 'ward_id'=> $input['ward_id'], 'device_id'=> $device->DeviceId ]);
        DB::commit();

        return $device;
    }

}
