<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StorePunchRequest;
use App\Http\Requests\Admin\UpdatePunchRequest;
use App\Models\Department;
use App\Models\Device;
use App\Models\Holiday;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use App\Models\Ward;
use App\Repositories\PunchRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PunchController extends Controller
{

    protected $punchRepository;
    public function __construct()
    {
        $this->punchRepository = new PunchRepository();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::orderByDesc('DeviceId')->get();
        $departments = Department::whereDepartmentId(null)->orderBy('name')->get();
        $wards = Ward::latest()->get();

        return view('admin.punches')->with(['devices'=> $devices, 'departments'=> $departments, 'wards'=> $wards]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePunchRequest $request)
    {
        try
        {
            $this->punchRepository->store($request->validated());
            return response()->json(['success'=> 'Attendace added successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Attendance');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Punch $punch)
    {
        $punch->load('user', 'user.department', 'user.ward', 'user.clas');
        $devices = Device::orderByDesc('DeviceId')->get();
        $punch_date = Carbon::parse($punch->punch_date)->format('Y-m-d');
        $punch->check_in = Carbon::parse($punch->check_in)->format('H:i:s');
        $punch->check_out = Carbon::parse($punch->check_out)->format('H:i:s');
        // dd($punch_date);
        $deviceHtml = '<span>
            <option value="">--Select Machine --</option>';
            foreach($devices as $device):
                $is_select = $device->DeviceId == $punch->device_id ? "selected" : "";
                $deviceHtml .= '<option value="'.$device->DeviceId.'" '.$is_select.'>'.$device->DeviceLocation.'</option>';
            endforeach;
        $deviceHtml .= '</span>';

        return [
            'result' => 1,
            'punch' => $punch,
            'punch_date' => $punch_date,
            'deviceHtml' => $deviceHtml,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePunchRequest $request, Punch $punch)
    {
        try
        {
            $this->punchRepository->update($request->validated(), $punch);
            return response()->json(['success'=> 'Attendace updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'adding', 'Attendance');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function toggle(Request $request, Punch $punch)
    {
        $is_latemark = DB::table('punches')->where('id', $punch->id)->value('is_latemark');
        try
        {
            DB::beginTransaction();
            if($is_latemark == '1')
                Punch::where('id', $punch->id)->update([ 'is_latemark' => '0' ]);
            else
                Punch::where('id', $punch->id)->update([ 'is_latemark' => '1' ]);
            DB::commit();
            return response()->json(['success'=> 'Latemark updated successfully']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Employee\'s latemark');
        }
    }
}
