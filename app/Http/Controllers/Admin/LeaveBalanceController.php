<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveBalanceController extends Controller
{

    public function index(Request $request)
    {
        $authUser = Auth::user();

        $leaves = Leave::get();
        $leaveRequests = User::query()
                    ->whereIsEmployee('1')
                    ->where('employee_type', '1')
                    // ->whereHas('leaveRequests')
                    ->with(['subDepartment', 'department', 'designation', 'clas', 'leaveRequests.leaveType'])
                    ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($q) => $q->where('app_users.sub_department_id', $authUser->sub_department_id))
                    ->orderBy('created_at', 'DESC')
                    ->get();
                    // ->first();

        // dd($leaveRequests);

        return view('admin.leave-balances')->with(['leaveRequests' => $leaveRequests, 'leaves' => $leaves]);
    }
}
