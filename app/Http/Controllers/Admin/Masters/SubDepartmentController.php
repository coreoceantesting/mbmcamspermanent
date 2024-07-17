<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreSubDepartmentRequest;
use App\Http\Requests\Admin\Masters\UpdateDepartmentRequest;
use App\Http\Requests\Admin\Masters\UpdateSubDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SubDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subDepartments = Department::whereNot('department_id', null)->with('parentDepartment')->latest()->get();
        $departments = Department::whereDepartmentId(null)->latest()->get();

        return view('admin.masters.sub-departments')->with(['subDepartments'=> $subDepartments, 'departments'=> $departments]);
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
    public function store(StoreSubDepartmentRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['level'] = 2;
            Department::create( Arr::only( $input, Department::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Sub Department created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Sub Department');
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
    public function edit(Department $sub_department)
    {
        $departments = Department::whereDepartmentId(null)->latest()->get();
        $sub_department->load('parentDepartment')->first();

        if ($sub_department)
        {
            $departmentHtml = '<span>
                <option value="">--Select Department--</option>';
                foreach($departments as $dep):
                    $is_select = $dep->id == $sub_department->department_id ? "selected" : "";
                    $departmentHtml .= '<option value="'.$dep->id.'" '.$is_select.'>'.$dep->name.'</option>';
                endforeach;
            $departmentHtml .= '</span>';

            $response = [
                'result' => 1,
                'department' => $sub_department,
                'departmentHtml' => $departmentHtml,
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
    public function update(UpdateSubDepartmentRequest $request, Department $sub_department)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $input['department_id'] = $input['edit_department_id'];
            $sub_department->update( Arr::only( $input, Department::getFillables() ) );
            DB::commit();
            User::where('sub_department_id', $sub_department->id)->update([ 'department_id'=> $input['department_id'] ]);

            return response()->json(['success'=> 'Sub Department updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Department');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $sub_department)
    {
        try
        {
            DB::beginTransaction();
            $sub_department->delete();
            DB::commit();
            return response()->json(['success'=> 'Sub Department deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Department');
        }
    }
}
