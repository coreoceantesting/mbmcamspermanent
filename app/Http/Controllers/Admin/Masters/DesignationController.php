<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreDesignationRequest;
use App\Http\Requests\Admin\Masters\UpdateDesignationRequest;
use App\Models\Designation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::latest()->get();

        return view('admin.masters.designations')->with(['designations'=> $designations]);
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
    public function store(StoreDesignationRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            Designation::create( Arr::only( $input, Designation::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Designation created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Designation');
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
    public function edit(Designation $designation)
    {
        if ($designation)
        {
            $response = [
                'result' => 1,
                'designation' => $designation,
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
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $designation->update( Arr::only( $input, Designation::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Designation updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Designation');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        try
        {
            DB::beginTransaction();
            $designation->delete();
            DB::commit();
            return response()->json(['success'=> 'Designation deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Designation');
        }
    }
}
