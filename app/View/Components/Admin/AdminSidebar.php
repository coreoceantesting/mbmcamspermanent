<?php

namespace App\View\Components\Admin;

use App\Models\Clas;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Device;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Shift;
use App\Models\Ward;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminSidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // $departmentsCount = Department::whereDepartmentId(null)->count();
        // $subDepartmentsCount = Department::whereNot('department_id', null)->count();
        // $wardsCount = Ward::count();
        // $clasCount = Clas::count();
        // $designationsCount = Designation::count();
        // $holidaysCount = Holiday::count();
        // $leaveTypesCount = LeaveType::count();
        // $leavesCount = Leave::count();
        // $shiftsCount = Shift::count();
        // $devicesCount = Device::count();

        return view('components.Admin.admin-sidebar', [
            // 'departmentsCount'=> $departmentsCount,
            // 'subDepartmentsCount'=> $subDepartmentsCount,
            // 'wardsCount'=> $wardsCount,
            // 'clasCount'=> $clasCount,
            // 'designationsCount'=> $designationsCount,
            // 'holidaysCount'=> $holidaysCount,
            // 'leaveTypesCount'=> $leaveTypesCount,
            // 'leavesCount'=> $leavesCount,
            // 'shiftsCount'=> $shiftsCount,
            // 'devicesCount'=> $devicesCount,
        ]);
    }
}
