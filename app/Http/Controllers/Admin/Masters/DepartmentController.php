<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreDepartmentRequest;
use App\Http\Requests\Admin\Masters\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::whereDepartmentId(null)->latest()->get();

        return view('admin.masters.departments')->with(['departments'=> $departments]);
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
    public function store(StoreDepartmentRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            Department::create( Arr::only( $input, Department::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Department created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Department');
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
    public function edit(Department $department)
    {
        if ($department)
        {
            $response = [
                'result' => 1,
                'department' => $department,
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
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $department->update( Arr::only( $input, Department::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Department updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Department');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try
        {
            DB::beginTransaction();
            $department->delete();
            DB::commit();
            return response()->json(['success'=> 'Department deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Department');
        }
    }


    public function getSubDepartments(Department $department)
    {
        $authUser = Auth::user();
        $sub_departments = Department::whereDepartmentId($department->id)
                            ->when( !$authUser->hasRole(['Admin', 'Super Admin']), fn($q)=> $q->where('id', $authUser->sub_department_id) )
                            ->latest()->get();

        if ($sub_departments)
        {
            $subDepartmentHtml = '<span>
                <option value="">--Select Sub Department--</option>';
                foreach($sub_departments as $dep):
                    $subDepartmentHtml .= '<option value="'.$dep->id.'" >'.$dep->name.'</option>';
                endforeach;
            $subDepartmentHtml .= '</span>';

            $response = [
                'result' => 1,
                'subDepartmentHtml' => $subDepartmentHtml,
            ];
        }
        else
        {
            $response = ['result' => 0];
        }
        return $response;
    }


    public function getWardDepartments(Ward $ward)
    {
        $departments = Department::whereWardId($ward->id)->orderBy('name')->get();

        if ($departments)
        {
            $departmentHtml = '<span>
                <option value="">--Select Department--</option>';
                foreach($departments as $dep):
                    $departmentHtml .= '<option value="'.$dep->id.'" >'.$dep->name.'</option>';
                endforeach;
            $departmentHtml .= '</span>';

            $response = [
                'result' => 1,
                'departmentHtml' => $departmentHtml,
            ];
        }
        else
        {
            $response = ['result' => 0];
        }
        return $response;
    }
}
