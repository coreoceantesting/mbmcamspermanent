<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreDeviceRequest;
use App\Http\Requests\Admin\Masters\UpdateDeviceRequest;
use App\Models\Device;
use App\Models\Ward;
use App\Repositories\DeviceRepository;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $deviceRepository;
    public function __construct()
    {
        $this->deviceRepository = new DeviceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wards = Ward::orderBy('name')->get();
        $devices = Device::with('wardDevice.ward')->orderByDesc('DeviceId')->get();
        
        return view('admin.masters.devices')->with(['devices'=> $devices, 'wards'=> $wards]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request)
    {
        try
        {
            $this->deviceRepository->store($request->validated());
            return response()->json(['success'=> 'Device added successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Device');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return $device;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return $this->deviceRepository->editDevice($device);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        try
        {
            $this->deviceRepository->updateDevice($request->validated(), $device);
            return response()->json(['success'=> 'Device updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Device');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
