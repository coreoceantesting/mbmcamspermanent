<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Models\Clas;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\LeaveRequestHierarchy;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreLeaveRequest;
use App\Http\Requests\Admin\Masters\UpdateLeaveRequest;
use App\Http\Requests\Admin\Masters\StoreLeaveRequestHierarchiesRequest;
use App\Http\Requests\Admin\Masters\UpdateLeaveRequestHierarchiesRequest;

class LeaveRequestHierarchiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $leave_request_hierarchies = LeaveRequestHierarchy::latest()->get();
    $classes = Clas::all(); // assuming your model is named Clas
    $departments = Department::where('is_permanent',1)->get();
    $designations = Designation::all();
        return view('admin.masters.leave_request_hierarchies')->with(
            [
            'leave_request_hierarchies'=> $leave_request_hierarchies,
            'classes'=>$classes,
            'departments'=>$departments,
            'designations'=>$designations,

            ]
        );
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
    public function store(StoreLeaveRequestHierarchiesRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $fillableFields = (new LeaveRequestHierarchy)->getFillable();
            LeaveRequestHierarchy::create(Arr::only($input, $fillableFields));
            DB::commit();

            return response()->json(['success'=> 'Leave Request Hierarchy created successfully!']);
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
    public function edit(LeaveRequestHierarchy $leaveRequestHierarchy)
    {

        $response = [
                'result' => 1,
                'leaveRequestHierarchy' => $leaveRequestHierarchy,
        ];
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequestHierarchiesRequest $request, LeaveRequestHierarchy $leaveRequestHierarchy)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $fillableFields = (new LeaveRequestHierarchy)->getFillable();
            $leaveRequestHierarchy->update( Arr::only( $input,$fillableFields));
            DB::commit();
            return response()->json(['success'=> 'Leave Request Hierarchy updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Leave');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequestHierarchy $leaveRequestHierarchy)
    {
        try
        {
            DB::beginTransaction();
            $leaveRequestHierarchy->delete();
            DB::commit();
            return response()->json(['success'=> 'Leave Request Hierarchy deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Leave');
        }
    }
}
