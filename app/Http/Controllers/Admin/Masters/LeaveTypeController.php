<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreLeaveTypeRequest;
use App\Http\Requests\Admin\Masters\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leave_types = LeaveType::whereNotIn('id', ['2','7'])->latest()->get();

        return view('admin.masters.leave_types')->with(['leave_types'=> $leave_types]);
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
    public function store(StoreLeaveTypeRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            LeaveType::create( Arr::only( $input, LeaveType::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'LeaveType created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'LeaveType');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leave_type)
    {
        if ($leave_type)
        {
            $response = [
                'result' => 1,
                'leave_type' => $leave_type,
            ];
        }
        else
        {
            $response = ['result' => 0];
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveTypeRequest $request, LeaveType $leave_type)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $leave_type->update( Arr::only( $input, LeaveType::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'LeaveType updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'LeaveType');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leave_type)
    {
        try
        {
            DB::beginTransaction();
            $leave_type->delete();
            DB::commit();
            return response()->json(['success'=> 'LeaveType deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'LeaveType');
        }
    }
}
