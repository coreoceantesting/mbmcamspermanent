<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreClasRequest;
use App\Http\Requests\Admin\Masters\UpdateClasRequest;
use App\Models\Clas;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ClasController extends Controller
{
    public function index()
    {
        $class = Clas::latest()->get();

        return view('admin.masters.class')->with(['class'=> $class]);
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
    public function store(StoreClasRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            Clas::create( Arr::only( $input, Clas::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Clas created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Clas');
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
    public function edit(Clas $cla)
    {
        if ($cla)
        {
            $response = [
                'result' => 1,
                'clas' => $cla,
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
    public function update(UpdateClasRequest $request, Clas $cla)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $cla->update( Arr::only( $input, Clas::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Class updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Clas');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clas $cla)
    {
        try
        {
            DB::beginTransaction();
            $cla->delete();
            DB::commit();
            return response()->json(['success'=> 'Class deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Clas');
        }
    }
}
