<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreHolidayRequest;
use App\Http\Requests\Admin\Masters\UpdateHolidayRequest;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::latest()->get();

        return view('admin.masters.holidays')->with(['holidays'=> $holidays]);
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
    public function store(StoreHolidayRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['year'] = Carbon::parse($input['date'])->format('Y');
            Holiday::create( Arr::only( $input, Holiday::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Holiday created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Holiday');
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
    public function edit(Holiday $holiday)
    {
        if ($holiday)
        {
            $response = [
                'result' => 1,
                'holiday' => $holiday,
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
    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $input['date'] = $input['edit_date'];
            $input['remark'] = $input['edit_remark'];
            $input['year'] = Carbon::parse($input['date'])->format('Y');
            $holiday->update( Arr::only( $input, Holiday::getFillables() ) );
            DB::commit();

            return response()->json(['success'=> 'Holiday updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Holiday');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        try
        {
            DB::beginTransaction();
            $holiday->delete();
            DB::commit();
            return response()->json(['success'=> 'Holiday deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Holiday');
        }
    }
}
