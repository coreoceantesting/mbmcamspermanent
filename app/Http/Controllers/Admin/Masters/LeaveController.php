<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreLeaveRequest;
use App\Http\Requests\Admin\Masters\UpdateLeaveRequest;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::latest()->get();
        $leaves = Leave::with('leaveType')->latest()->get();

        return view('admin.masters.leaves')->with(['leaves'=> $leaves, 'leaveTypes'=> $leaveTypes]);
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
    public function store(StoreLeaveRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            Leave::create( Arr::only( $input, Leave::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Leave created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Leave');
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
    public function edit(Leave $leaf)
    {
        $leaveTypes = LeaveType::latest()->get();
        $leaf->load('leaveType');

        if ($leaf)
        {
            $leaveTypeHtml = '<span>
                <option value="">--Select Leave Type--</option>';
                foreach($leaveTypes as $type):
                    $is_select = $type->id == $leaf->leave_type_id ? "selected" : "";
                    $leaveTypeHtml .= '<option value="'.$type->id.'" '.$is_select.'>'.$type->name.'</option>';
                endforeach;
            $leaveTypeHtml .= '</span>';

            $response = [
                'result' => 1,
                'leave' => $leaf,
                'leaveTypeHtml' => $leaveTypeHtml,
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
    public function update(UpdateLeaveRequest $request, Leave $leaf)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['days'] = $input['edit_days'];
            $input['leave_type_id'] = $input['edit_leave_type_id'];
            $leaf->update( Arr::only( $input, Leave::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Leave updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Leave');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leaf)
    {
        try
        {
            DB::beginTransaction();
            $leaf->delete();
            DB::commit();
            return response()->json(['success'=> 'Leave deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Leave');
        }
    }
}
