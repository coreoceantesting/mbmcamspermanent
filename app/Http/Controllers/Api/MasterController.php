<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ClassResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\DesignationResource;
use App\Http\Resources\WardResource;
use App\Models\Clas;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Ward;
use Illuminate\Http\Request;

class MasterController extends ApiController
{
    public function departments()
    {
        return  $this->respondWith( DepartmentResource::collection(Department::whereDepartmentId(null)->orderBy('name')->get()) );
    }

    public function classes()
    {
        return  $this->respondWith( ClassResource::collection(Clas::orderBy('name')->get()) );
    }

    public function wards()
    {
        return  $this->respondWith( WardResource::collection(Ward::orderBy('name')->get()) );
    }

    public function designations()
    {
        return  $this->respondWith( DesignationResource::collection(Designation::orderBy('name')->get()) );
    }

    public function subDepartments(Request $request)
    {
        return  $this->respondWith(
            DepartmentResource::make(
                Department::whereDepartmentId(null)
                    ->when($request->department_id, fn($q)=> $q->where('department_id', $request->department_id))
                    ->orderBy('name')->get()
            )
        );
    }

}
