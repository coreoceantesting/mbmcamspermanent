<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();

        $contractors = Contractor::get();
        $settings = Setting::getValues($authUser->tenant_id)->pluck('value', 'key');

        $fromDate = Carbon::parse($request->from_date)->toDateString();
        $toDate = Carbon::parse($request->to_date)->toDateString();

        $dateRanges = CarbonPeriod::create(Carbon::parse($fromDate), Carbon::parse($toDate))->toArray();

        // $empList = [];

        $total_emp = false;
        if ($request->month) {
            // $empList = User::whereNot('id', $authUser->id)
            //             ->with(['department', 'shift', 'empShifts' => function($q) use ($fromDate, $toDate) {
            //                 $q->whereBetween('from_date', [$fromDate, $toDate]);
            //             }])
            //             ->where('contractor_id', $request->contractor)
            //             ->where('is_employee', 1)
            //             ->orderBy('emp_code')
            //             ->with(['punches' => function($q) use ($fromDate, $toDate) {
            //                 $q->whereBetween('punch_date', [$fromDate, $toDate]);
            //             }])
            //             ->get();

            // $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
            // $totalDays = Carbon::parse($fromDate)->diffInDays($toDate) + 1;

            $total_emp = Contractor::where('id',$request->contractor)->first();
        }

        return view('admin.reports.vendor-working-hour-report')->with([
            'dateRanges' => $dateRanges,
            'settings' => $settings,
            'contractors' => $contractors,
            // 'empList' => $empList,
            'contractor_id' => $request->contractor,
            'authUser'      => $authUser,
            'fromDate'      => $fromDate,
            'toDate'        => $toDate,
            'total_emp'     => $total_emp
        ]);

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
