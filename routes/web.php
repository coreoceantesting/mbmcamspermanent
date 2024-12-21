<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    return redirect()->route('login');
})->name('/');


// Guest Users
Route::middleware(['guest', 'PreventBackHistory'])->group(function () {
    // Route::get('/', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('/');
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('signin');
});



// Authenticated users
Route::middleware(['auth', 'PreventBackHistory'])->group(function () {

    // Auth Routes
    Route::get('edit-profile', [App\Http\Controllers\Admin\DashboardController::class, 'editProfile'])->name('edit-profile');
    Route::get('home', fn() => redirect()->route('dashboard'))->name('home');
    Route::get('change-employee-type/{type}', [App\Http\Controllers\Admin\AuthController::class, 'changeEmployeeType'])->name('change-employee-type');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'Logout'])->name('logout');
    Route::get('show-change-password', [App\Http\Controllers\Admin\AuthController::class, 'showChangePassword'])->name('show-change-password');
    Route::post('change-password', [App\Http\Controllers\Admin\AuthController::class, 'changePassword'])->name('change-password');



    // Dashboard routes
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/employees/list', [App\Http\Controllers\Admin\EmployeeController::class, 'list'])->name('employees.list');
    Route::get('/employees-new/list', [App\Http\Controllers\Admin\EmployeeNewController::class, 'list'])->name('employees-new.list');
    Route::get('dashboard/tabular-view-statistics', [App\Http\Controllers\Admin\DashboardController::class, 'tabularViewStatistics'])->name('dashboard.tabular-view-statistics');


    // Manual Sync Attendance
    Route::get('manual-sync', [App\Http\Controllers\Admin\ManualSyncController::class, 'index'])->name('manual-sync.index');
    Route::post('manual-sync', [App\Http\Controllers\Admin\ManualSyncController::class, 'addManualSync'])->name('manual-sync.store');
    Route::post('check-sync-status', [App\Http\Controllers\Admin\ManualSyncController::class, 'checkSyncStatus'])->name('check-sync-status');



    // Masters routes
    Route::resource('departments', App\Http\Controllers\Admin\Masters\DepartmentController::class);
    Route::get('departments/{department}/sub_departments', [App\Http\Controllers\Admin\Masters\DepartmentController::class, 'getSubDepartments'])->name('departments.sub_departments');
    Route::resource('sub-departments', App\Http\Controllers\Admin\Masters\SubDepartmentController::class);
    Route::resource('wards', App\Http\Controllers\Admin\Masters\WardController::class);
    Route::get('wards/{ward}/departments', [App\Http\Controllers\Admin\Masters\DepartmentController::class, 'getWardDepartments'])->name('wards.departments');
    Route::resource('clas', App\Http\Controllers\Admin\Masters\ClasController::class);
    Route::resource('designations', App\Http\Controllers\Admin\Masters\DesignationController::class);
    Route::resource('holidays', App\Http\Controllers\Admin\Masters\HolidayController::class);
    Route::resource('leave_types', App\Http\Controllers\Admin\Masters\LeaveTypeController::class);
    Route::resource('leaves', App\Http\Controllers\Admin\Masters\LeaveController::class);
    Route::resource('shifts', App\Http\Controllers\Admin\Masters\ShiftController::class);
    Route::resource('devices', App\Http\Controllers\Admin\Masters\DeviceController::class);
    Route::resource('contractors', App\Http\Controllers\Admin\Masters\ContractorController::class);


    // Users Roles n Permissions
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::get('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    Route::get('users/{user}/retire', [App\Http\Controllers\Admin\UserController::class, 'retire'])->name('users.retire');
    Route::put('users/{user}/change-password', [App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
    Route::get('users/{user}/get-role', [App\Http\Controllers\Admin\UserController::class, 'getRole'])->name('users.get-role');
    Route::put('users/{user}/assign-role', [App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('users.assign-role');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);


    // Employees Routes
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
    Route::resource('employees-new', App\Http\Controllers\Admin\EmployeeNewController::class);

    Route::get('employees/{employee:emp_code}/info', [App\Http\Controllers\Admin\EmployeeController::class, 'fetchInfo'])->name('employees.info');


    // Punches Routes
    Route::resource('punches', App\Http\Controllers\Admin\PunchController::class);
    Route::get('punches/{punch}/toggle', [App\Http\Controllers\Admin\PunchController::class, 'toggle'])->name('punches.toggle');


    // Leaves Routes
    Route::resource('leave-requests', App\Http\Controllers\Admin\LeaveRequestController::class);
    Route::put('leave-requests/{leave_request}/change-request', [App\Http\Controllers\Admin\LeaveRequestController::class, 'changeRequest'])->name('leave-requests.change-request');
    Route::get('active-medical-leave-requests', [App\Http\Controllers\Admin\LeaveRequestController::class, 'activeMedicalLeaveRequest'])->name('leave-requests.active-medical-leave');
    Route::get('completed-medical-leave-requests', [App\Http\Controllers\Admin\LeaveRequestController::class, 'completedMedicalLeaveRequest'])->name('leave-requests.completed-medical-leave');
    Route::get('leave-applications', [App\Http\Controllers\Admin\LeaveRequestController::class, 'pendingLeaveRequest'])->name('leave-requests.application');


    // Employee Shifts
    Route::resource('rosters', App\Http\Controllers\Admin\RosterController::class);
    // Route::get('sample_roster', [App\Http\Controllers\Admin\RosterController::class, 'downloadSample'] )->name('rosters.sample');
    // Route::post('import_shift_roster', [App\Http\Controllers\Admin\RosterController::class, 'importShiftRoster'] )->name('rosters.import');


    // Reports
    Route::resource('reports', App\Http\Controllers\Admin\ReportController::class);
    Route::resource('vendor-reports', App\Http\Controllers\Admin\VendorReportController::class);
    Route::get('muster-report', [App\Http\Controllers\Admin\ReportController::class, 'musterReport'])->name('reports.muster');
    Route::get('leave-report', [App\Http\Controllers\Admin\ReportController::class, 'empLeaveCounts'])->name('reports.leave-report');
    Route::get('reports/get/month-wise-date', [App\Http\Controllers\Admin\ReportController::class, 'monthWiseDate'])->name('reports.dates');
    Route::get('device_log_report', [App\Http\Controllers\Admin\ReportController::class, 'deviceLogReport'])->name('dashboard.device-log-report');
    Route::get('department_wise_report', [App\Http\Controllers\Admin\ReportController::class, 'departmentWiseReport'])->name('dashboard.department-wise-report');
    Route::get('todays_present_report', [App\Http\Controllers\Admin\ReportController::class, 'todaysPresentReport'])->name('dashboard.todays-present-report');
    Route::get('todays_absent_report', [App\Http\Controllers\Admin\ReportController::class, 'todaysAbsentReport'])->name('dashboard.todays-absent-report');
    Route::get('shift_wise_employee/{shift_id}', [App\Http\Controllers\Admin\ReportController::class, 'shiftWiseEmployees'])->name('dashboard.shift-wise-employee');
    Route::get('todays_leave_bifurcation', [App\Http\Controllers\Admin\ReportController::class, 'todaysLeaveBifurcation'])->name('dashboard.todays-leave-bifurcation');
    Route::get('month_wise_latemark', [App\Http\Controllers\Admin\ReportController::class, 'monthWiseLatemark'])->name('dashboard.month-wise-latemark');
    Route::get('employee_wise_report', [App\Http\Controllers\Admin\ReportController::class, 'employeeWiseReport'])->name('dashboard.employee-wise-report');


    // fetch contractor
    Route::get('/fetch-contractors/{departmentid}', [App\Http\Controllers\Admin\DashboardController::class, 'fetchContractor']);
    Route::get('/fetch-designation/{contractorid}', [App\Http\Controllers\Admin\DashboardController::class, 'fetchdesignation']);
    Route::get('/employees-detail/list', [App\Http\Controllers\Admin\DashboardController::class, 'list'])->name('employees-detail.list');
    Route::get('fetch-contractor/{department}', [App\Http\Controllers\Admin\ReportController::class, 'getContractorsNames'])->name('fetch-contractor');
});




Route::prefix('employee')->name('employee.')->group(function () {

    // Guest employees
    Route::middleware(['employee.guest', 'PreventBackHistory'])->group(function () {
        Route::get('/', fn() => redirect()->route('login', ['device_type' => 'mobile']))->name('login');
        Route::get('/register', [App\Http\Controllers\Employee\AuthController::class, 'showRegister'])->name('register');
        Route::post('/emp-info', [App\Http\Controllers\Employee\AuthController::class, 'searchEmployeeCode'])->name('emp-info');
        Route::post('/register', [App\Http\Controllers\Employee\AuthController::class, 'register'])->name('signup');
    });

    // Authenticated employees
    Route::middleware(['employee.auth', 'PreventBackHistory'])->group(function () {
        Route::get('/home', [App\Http\Controllers\Employee\HomeController::class, 'index'])->name('home');
        Route::post('/employee-logout', [App\Http\Controllers\Employee\HomeController::class, 'logout'])->name('logout');
        Route::get('/delete-account', [App\Http\Controllers\Employee\HomeController::class, 'deleteAccount'])->name('delete-account');

        Route::get('show-change-password', [App\Http\Controllers\Employee\HomeController::class, 'showChangePassword'])->name('show-change-password');
        Route::post('change-password', [App\Http\Controllers\Employee\HomeController::class, 'changePassword'])->name('change-password');
    });
});

Route::get('/privacy-policy', [App\Http\Controllers\Employee\HomeController::class, 'privacyPolicy'])->name('privacy-policy');


Route::resource('file-test', App\Http\Controllers\FileUploadTestController::class);




Route::get('/import-department', function () {
    Artisan::call('department:import');
    return dd(Artisan::output());
});
Route::get('/import-punches', function () {
    Artisan::call('punches:import');
    return dd(Artisan::output());
});
Route::get('/import-employees', function () {
    Artisan::call('employees:import');
    return dd(Artisan::output());
});

Route::get('/php', function (Request $request) {
    if (!auth()->check())
        return 'Unauthorized request';

    Artisan::call($request->artisan);
    return dd(Artisan::output());
});


Route::get('/test-code', function () {

    $data = App\Models\EmployeeShift::query()
        // ->whereDate('updated_at', Carbon\Carbon::today()->subDays(15))
        ->with('punch')
        ->orderByDesc('id')
        ->take(200)
        ->get();


    return $data;
});


Route::get('/add-comp-leave', function () {

    $holidays = App\Models\Holiday::where('year', date('Y'))->first();

    App\Models\Punch::whereIn('punch_date', $holidays->pluck('date')->toArray())->orderByDesc('id')->chunk(50, function ($punches) {

        foreach ($punches as $punch) {
            App\Models\Punch::create([
                'emp_code' => $punch->emp_code,
                'device_id' => 0,
                'check_in' => Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon\Carbon::parse($punch->punch_date)->toDateString() . ' 10:00:00')->toDateTimeString(),
                'check_out' => Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon\Carbon::parse($punch->punch_date)->toDateString() . ' 19:00:00')->addSeconds(16200)->toDateTimeString(),
                'punch_date' => Carbon\Carbon::parse($punch->punch_date)->toDateString(),
                'duration' => 32400,
                'punch_by' => App\Models\Punch::PUNCH_BY_ADJUSTMENT,
                'type' => App\Models\Punch::PUNCH_TYPE_LEAVE,
                'leave_type_id' => 3,
                'is_latemark' => '0',
                'is_latemark_updated' => '1',
                'is_duration_updated' => '1',
                'is_paid' => App\Models\Punch::PUNCH_IS_PAID,
            ]);
        }
    });

    return 'done';
});


Route::get('/delete-data', function () {

    \DB::table('punches')->whereDate('punch_date', '2023-08-25')->where('punch_by', '0')->delete();

    return 'done';
});


Route::get('/remove-duplicate', function () {
    DB::table('punches as p1')
        ->where('punch_by', '2')
        ->join(DB::raw('
            (SELECT punch_date, emp_code, MIN(id) AS min_id
            FROM punches
            GROUP BY punch_date, emp_code
            HAVING COUNT(*) > 1
            ) as p2'), function ($join) {
            $join->on('p1.punch_date', '=', 'p2.punch_date')
                ->on('p1.emp_code', '=', 'p2.emp_code')
                ->where('p1.id', '>', DB::raw('p2.min_id'));
        })
        ->delete();

    return 'done';
});
