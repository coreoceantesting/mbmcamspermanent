<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreShiftRequest;
use App\Http\Requests\Admin\Masters\UpdateShiftRequest;
use App\Models\Shift;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::latest()->get();

        return view('admin.masters.shifts')->with(['shifts'=> $shifts]);
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
    public function store(StoreShiftRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            Shift::create( Arr::only( $input, Shift::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Shift created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Shift');
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
    public function edit(Shift $shift)
    {
        if ($shift)
        {
            $response = [
                'result' => 1,
                'shift' => $shift,
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
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['name'] = $input['edit_name'];
            $input['from_time'] = $input['edit_from_time'];
            $input['to_time'] = $input['edit_to_time'];
            $shift->update( Arr::only( $input, Shift::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Shift updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Shift');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        try
        {
            DB::beginTransaction();
            $shift->delete();
            DB::commit();
            return response()->json(['success'=> 'Shift deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Shift');
        }
    }
}
