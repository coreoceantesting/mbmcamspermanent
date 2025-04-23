<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\ChangeLeaveRequestStatusRequest;
use App\Http\Requests\Admin\StoreLeaveRequestRequest;
use App\Http\Requests\Admin\UpdateLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Punch;
use App\Models\User;
use App\Repositories\LeaveRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use function App\Helpers\caseMatchTable;

class LeaveRequestController extends Controller
{

    protected $leaveRepository;
    public function __construct()
    {
        $this->leaveRepository = new LeaveRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageType = $request->page_type ?? 'full_day';
        $type_const = strtoupper('LEAVE_FOR_TYPE_' . $pageType);
        $authUser = Auth::user();

        $leaveRequests = LeaveRequest::with('leaveType', 'document')
            ->withWhereHas(
                'user',
                fn($qr) => $qr
                    ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn($q) => $q
                        ->where('sub_department_id', $authUser->sub_department_id)->with('ward', 'clas', 'department'))
                    ->where('employee_type', '1')
            )
            ->whereRequestForType(constant("App\Models\LeaveRequest::$type_const"))
            ->when($pageType == 'full_day', fn($qr) => $qr->whereNotIn('leave_type_id', ['2', '7']))
            ->whereIsApproved('0')
            ->latest()->get();

        $leaveTypes = LeaveType::with('leave')
            ->when($pageType == 'full_day', fn($qr) => $qr->whereNotIn('id', ['2', '7']))
            ->get();

        return view('admin.leave-requests')->with(['leaveRequests' => $leaveRequests, 'pageType' => $pageType, 'leaveTypes' => $leaveTypes]);
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
    public function store(StoreLeaveRequestRequest $request)
    {
        $user = User::whereEmpCode($request->emp_code)->first();
        $input = $request->validated();
        $input['from_date'] = $input['from_date'] ?? $input['date'];

        if ($this->isLeaveAlreadyAppliedForThisDay($input, $user))
            return response()->json(['error2' => 'Leave request is already applied for this day, revoke existing leave and apply again!']);

        // $canApplyLeave = $this->leaveRepository->hasLeaveCountsAvailable($input);
        // if(!$canApplyLeave['status'])
        //     return response()->json(['error2'=> $canApplyLeave['message']]);

        try {
            $this->leaveRepository->storeLeaveRequest($input, $user);
            return response()->json(['success' => 'Leave request added successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'adding', 'Leave request');
        }
    }
    protected function isLeaveAlreadyAppliedForThisDay($input, $user)
    {
        if (array_key_exists('date', $input) && $input['date']) {
            if (DB::table('punches')->where(['emp_code' => $user->emp_code, 'punch_date' => $input['date'], 'punch_by' => 2])->exists())
                return true;
        } else {
            $dateRanges = CarbonPeriod::create(Carbon::parse($input['from_date']), Carbon::parse($input['to_date']))->toArray();
            foreach ($dateRanges as $dateRange) {
                if (DB::table('punches')->where(['emp_code' => $user->emp_code, 'punch_date' => $dateRange->toDateString(), 'punch_by' => 2])->exists())
                    return true;
            }
        }

        return false;
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
    public function edit(LeaveRequest $leave_request)
    {
        return $this->leaveRepository->editLeaveRequest($leave_request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leave_request)
    {
        try {
            $this->leaveRepository->updateLeaveRequest($request->validated(), $leave_request);
            return response()->json(['success' => 'Leave request updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Leave request');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leave_request)
    {
        if ($leave_request->leave_type_id != null && $leave_request->to_date == null)
            return response()->json(['error2' => 'Update "to date" of this leave to delete this leave request!']);

        $user = User::find($leave_request->user_id);
        $dateRanges = CarbonPeriod::create(Carbon::parse($leave_request->from_date), Carbon::parse($leave_request->to_date ?? $leave_request->from_date))->toArray();

        try {
            // DB::beginTransaction();
            foreach ($dateRanges as $dateRange) {
                $dateString = Carbon::parse($dateRange)->toDateString();
                $isDeleted = Punch::where(['punch_by' => '2', 'emp_code' => $user->emp_code, 'punch_date' => $dateString])->delete();
                $currDateRecord = DB::table(caseMatchTable('DeviceLogs_Processed'))
                    ->whereDate('LogDate', $dateString)
                    // ->whereDate('LogDate', '2023-12-12')
                    ->where('UserId', $user->emp_code)->get();

                if ($isDeleted && $currDateRecord->isNotEmpty()) {
                    $checkIn = $currDateRecord->sortBy('LogDate')->first()->LogDate;
                    $checkOut = $currDateRecord->count() > 1 ? $currDateRecord->sortBy('LogDate')->last()->LogDate : null;

                    Punch::create([
                        'emp_code' => $user->emp_code,
                        'device_id' => 0,
                        'check_in' => Carbon::parse($checkIn)->toDateTimeString(),
                        'check_out' => $checkOut ? Carbon::parse($checkOut)->toDateTimeString() : $checkOut,
                        'punch_date' => $dateString,
                        'duration' => $checkOut ? Carbon::parse($checkIn)->diffInSeconds(Carbon::parse($checkOut)) : 0,
                        'is_duration_updated' => '1',
                        'is_latemark' => '0',
                        'is_latemark_updated' => '1',
                        'punch_by' => '0',
                        'type' => '0',
                        'is_paid' => '1',
                    ]);
                }
            }
            $leave_request->delete();
            // DB::commit();
        } catch (\Exception $e) {
            Log::error("Error while deleting leave request");
        }

        return response()->json(['success' => 'Leave request deleted successfully!']);
    }


    public function changeRequest(ChangeLeaveRequestStatusRequest $request, LeaveRequest $leave_request)
    {
        $this->leaveRepository->changeRequest($request->validated(), $leave_request);
        return response()->json(['success' => 'Leave request ' . ($request->status == 1 ? 'approved' : 'rejected') . ' successfully!']);
    }


    public function activeMedicalLeaveRequest()
    {
        $pageType = 'active';
        $isAdmin = Auth::user()->hasRole(['Admin', 'Super Admin']);
        $leaveRequests = LeaveRequest::with('user.department', 'user.ward', 'leaveType', 'document')
            ->whereLeaveTypeId(7)
            ->when(
                !$isAdmin,
                fn($q) => $q->withWhereHas('user', fn($qr) => $qr->where('department_id', Auth::user()->department_id))
            )
            ->where('is_approved', '0')
            ->latest()->get();

        $leaveTypes = LeaveType::with('leave')->where('id', 7)->get();

        return view('admin.medical-leave-requests')->with(['leaveRequests' => $leaveRequests, 'pageType' => $pageType, 'leaveTypes' => $leaveTypes, 'isAdmin' => $isAdmin]);
    }

    public function completedMedicalLeaveRequest()
    {
        $pageType = 'completed';
        $isAdmin = Auth::user()->hasRole(['Admin', 'Super Admin']);
        $leaveRequests = LeaveRequest::with('user.department', 'user.ward', 'leaveType', 'document')
            ->whereLeaveTypeId(7)
            ->when(
                !$isAdmin,
                fn($q) => $q->withWhereHas('user', fn($qr) => $qr->where('department_id', Auth::user()->department_id))
            )
            ->whereNot('is_approved', '0')
            ->latest()->get();

        $leaveTypes = LeaveType::with('leave')->get();

        return view('admin.medical-leave-requests')->with(['leaveRequests' => $leaveRequests, 'pageType' => $pageType, 'leaveTypes' => $leaveTypes, 'isAdmin' => $isAdmin]);
    }

    public function pendingLeaveRequest(Request $request)
    {
        $pageType = $request->page_type ?? 'pending';

        return view('admin.leave-applications')->with(['pageType' => $pageType]);
    }
}
