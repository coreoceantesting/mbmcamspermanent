<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Dashboard</x-slot>

    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec">

            <div class="row">
                <div class="col-12 px-0">
                    <div class="row">
                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-blue b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Employees</span>
                                            <h4 class="mb-0 counter"> {{ $totalEmployees }} </h4><i class="icon-bg" data-feather="user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-success b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Department</span>
                                            <h4 class="mb-0 counter"> {{ $totalDepartments }} </h4><i class="icon-bg" data-feather="book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-warning b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Holidays</span>
                                            <h4 class="mb-0 counter"> {{ $totalHolidays }} </h4><i class="icon-bg" data-feather="home"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3 col-lg-6">
                            <div class="card o-hidden border-0">
                                <div class="bg-danger b-r-4 card-body">
                                    <div class="media static-top-widget">
                                        <div class="media-body"><span class="m-0">Total Office</span>
                                            <h4 class="mb-0 counter"> {{ $totalWards->count() }} </h4><i class="icon-bg" data-feather="briefcase"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12  px-0">
                    <div class="row">
                        <div class="col-9">
                            <div class="row">
                                {{-- Todays present --}}
                                <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's Present</h6>
                                        <div class="card-body px-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    @php
                                                        $todaysPresentCount = $todayPunchData->where('check_in', '!=', '0000-00-00 00:00:00')->count();
                                                        $todaysPresentPercent = $totalEmployees ? round(($todaysPresentCount/$totalEmployees)*100) : '0';
                                                        $todaysAbsentCount = $totalEmployees-$todaysPresentCount;
                                                    @endphp
                                                    <label for=""> <a href="{{ route('dashboard.todays-present-report') }}">{{ $todaysPresentPercent }}%</a> </label>
                                                    <div class="progress">
                                                        <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{$todaysPresentPercent}}%" aria-valuenow="{{$todaysPresentPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <a href="{{ route('dashboard.todays-present-report') }}"> <strong style="font-size:22px">{{$todaysPresentCount}} </strong>({{ $todaysPresentPercent }}%)  </a> <br>
                                                    <strong>Present Count</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer row">
                                            @php
                                                $till10AMCount = $todayPunchData->countBy( fn($item) => Carbon\Carbon::parse($item->check_in)->gte($todaysDate.' 10:00:00') );
                                                $after10AMCount = array_key_exists('1', $till10AMCount->toArray()) ? $till10AMCount['1'] : 0;
                                                $till10AMCount = array_key_exists('0', $till10AMCount->toArray()) ? $till10AMCount['0'] : 0;
                                            @endphp
                                            <div class="col-6 col-sm-6">
                                                <h6>Till 10AM</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $till10AMCount }}</span><span style="font-size:14px">({{ $totalEmployees ? round(($till10AMCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                            </div>
                                            <div class="col-6 col-sm-6">
                                                <h6>After 10AM</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $after10AMCount }}</span><span style="font-size:14px">({{ $totalEmployees ? round(($after10AMCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Todays absent --}}
                                <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's Absent</h6>
                                        <div class="card-body px-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for=""> <a href="{{ route('dashboard.todays-absent-report') }}">{{ $totalEmployees ? round(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%</a> </label>
                                                    <div class="progress">
                                                        <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? round(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? round(($todaysAbsentCount/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <a href="{{ route('dashboard.todays-absent-report') }}"> <strong style="font-size:22px">{{ max($todaysAbsentCount, 0) }} </strong>({{ $totalEmployees ? round(($todaysAbsentCount/$totalEmployees)*100) : '0' }}%)</a> <br>
                                                    <strong>Absent Count</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer row">
                                            @php
                                                $permittedLeaveCount = $todayPunchData->where('check_in', '0000-00-00 00:00:00')->where('punch_by', '2')->count();
                                            @endphp
                                            <div class="col-6 col-sm-6">
                                                <h6>Permitted Leave</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ $permittedLeaveCount }}</span><span style="font-size:14px">({{ $totalEmployees ? round( ($permittedLeaveCount/$totalEmployees)*100) : '0' }}%)</span></h3>
                                            </div>
                                            <div class="col-6 col-sm-6">
                                                <h6>Non Permitted Leave</h6>
                                                <h3><span class="counter" style="font-size:22px">{{ max($todaysAbsentCount-$permittedLeaveCount, 0) }}</span><span style="font-size:14px">({{ $totalEmployees ? round((($todaysAbsentCount-$permittedLeaveCount)/$totalEmployees)*100) : '0' }}%)</span></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Leave bifurcation --}}
                                <div class="col-md-12 col-lg-12 col-xl-12 box-col-12">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Leave Bifurcation</h6>
                                        <div class="card-footer row">
                                            <div class="col-2 col-sm-2">
                                                <h6>CL</h6>
                                                <h3 class="counter"> <a href="{{ route('dashboard.todays-leave-bifurcation', ['leave_type_id'=> '6' ]) }}"> {{ $todayPunchData->where('leave_type_id', '6')->count() }}</a></h3>
                                            </div>
                                            <div class="col-2 col-sm-2">
                                                <h6>EL</h6>
                                                <h3><span class="counter"> <a href="{{ route('dashboard.todays-leave-bifurcation', ['leave_type_id'=> '5' ]) }}"> {{ $todayPunchData->where('leave_type_id', '5')->count() }}</a></span></h3>
                                            </div>
                                            <div class="col-2 col-sm-2">
                                                <h6>ML</h6>
                                                <h3><span class="counter"> <a href="{{ route('dashboard.todays-leave-bifurcation', ['leave_type_id'=> '7' ]) }}"> {{ $todayPunchData->where('leave_type_id', '7')->count() }}</a></span></h3>
                                            </div>
                                            <div class="col-3 col-sm-3">
                                                <h6>Other Leave</h6>
                                                <h3><span class="counter"><a href="{{ route('dashboard.todays-leave-bifurcation', ['leave_type_id'=> '4' ]) }}">{{ $todayPunchData->where('leave_type_id', '4')->count() }}</a></span></h3>
                                            </div>
                                            <div class="col-3 col-sm-3">
                                                <h6>Half Day</h6>
                                                <h3><span class="counter"><a href="{{ route('dashboard.todays-leave-bifurcation', ['leave_type_id'=> '0' ]) }}">{{ $todayPunchData->where('leave_type_id', '0')->count() }}</a></span></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Repeatedly latemark / absent --}}
                            {{-- <div class="row">
                                @php
                                    $repeatedlyLateMark = $punchData->groupBy('emp_code')->countBy( fn($item) => $item->where('is_latemark', '>', '0')->count() > 1 );
                                    $repeatedlyLateMark = array_key_exists('1', $repeatedlyLateMark->toArray()) ? $repeatedlyLateMark['1'] : 0;
                                @endphp

                                <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Repeatedly Late Mark</h6>
                                        <div class="card-body px-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="">{{ $totalEmployees ? round(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%</label>
                                                    <div class="progress">
                                                        <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? round(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? round(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <strong style="font-size:22px">{{ $repeatedlyLateMark }} </strong>({{ $totalEmployees ? round(($repeatedlyLateMark/$totalEmployees)*100) : '0' }}%) <br>
                                                    <strong>Repeatedly Late Mark</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $regularEmployeeCount = $punchData->groupBy('emp_code')->count();
                                    $repeatedlyAbsent = $totalEmployees-$regularEmployeeCount;
                                @endphp

                                <div class="col-md-6 col-lg-6 col-xl-6 box-col-6">
                                    <div class="card custom-card rounded">
                                        <h6 class="card-header rounded bg-primary py-2 px-3  text-center">Repeatedly Absent</h6>
                                        <div class="card-body px-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="">{{ $totalEmployees ? round(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%</label>
                                                    <div class="progress">
                                                        <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $totalEmployees ? round(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%" aria-valuenow="{{ $totalEmployees ? round(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <strong style="font-size:22px">{{ max($repeatedlyAbsent, 0) }} </strong>({{ $totalEmployees ? round(($repeatedlyAbsent/$totalEmployees)*100) : '0' }}%) <br>
                                                    <strong>Repeatedly Absent</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                        </div>


                        {{-- Side recent 6 attendance list --}}
                        <div class="col-3">

                            <h6 class="mb-4">Today's Latest 6 Records</h6>
                            {{-- @php
                                $latestFives = $todayPunchData->where('punch_by', '0')->reverse()->take(6);
                            @endphp --}}
                            @foreach ($todayPunchData->sortByDesc('check_in')->take(6) as $latest)
                                <div class="col-12 card rounded latest-update-sec mb-2">
                                    <div class="media py-2">
                                        <div class="col-12">
                                            <div class="media-body">
                                                <span>{{ Str::limit(ucwords($latest->user?->name), 25) }}</span> <br>
                                                <span>#{{ $latest->emp_code }}</span> &nbsp;&nbsp; <span class="text-danger"> {{  Carbon\Carbon::parse($latest->check_in)->format('d-m-Y h:i A') }} </span>
                                                <p style="font-size: 10px">{{ Str::limit(ucfirst($latest->user?->department?->name), 25) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12">
                                <a href="{{ route('dashboard.device-log-report') }}" class="btn btn-primary w-100 py-1">View more </a>
                            </div>

                            <div class="col-12">
                                <a href="{{ route('dashboard.tabular-view-statistics') }}"><button class="btn btn-primary w-100 px-2 py-1 mt-3 mb-3" title="DepartmentWise Report">Tabular View  Statistics</button></a>
                            </div>

                        </div>
                    </div>

                </div>


                {{-- Contractor wise details --}}

                <div class="col-12 px-0">
                    @if ( $is_admin && request()->department && !$totalContractors->isEmpty())
                        <div class="row">
                            <div class="card rounded">
                                <div class="card-header px-2 py-3">
                                    <h6>Contractor Wise Details</h6>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table-bordered" id="datatable-tabletools">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary">Sr No</th>
                                                    {{-- <th class="bg-primary">Department</th> --}}
                                                    <th class="bg-primary">Contractor</th>
                                                    <th class="bg-primary">Designation</th>
                                                    <th class="bg-primary">Total Employee</th>
                                                    <th class="bg-primary">Today's Present</th>
                                                    <th class="bg-primary">Today's Absent</th>
                                                    <th class="bg-primary">Progress Bar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                        $i = 1;
                                                    @endphp
                                                @foreach ($totalContractors as $key=> $totalContractor)

                                                @foreach($designations as $val=> $designation)

                                                    @php
                                                        $usersWithDesignation = $totalContractor->users
                                                                                ->where('designation_id', $designation->id)
                                                                                ->where('department_id', request()->department);

                                                        $usersCountWithDesignation = $usersWithDesignation->count();

                                                        // Filter punch data for current contractor, department, and employee type
                                                        $currentContractorData = $todayPunchData->filter(function ($item) use ($totalContractor, $designation, $employeeType) {
                                                            return $item->user->contractor_id == $totalContractor->id
                                                                && $item->user->department_id == request()->department
                                                                && $item->user->employee_type == $employeeType
                                                                && $item->user->designation_id == $designation->id;
                                                        });
                                                        $presentCount = $currentContractorData->count();
                                                        $absentCount = $usersCountWithDesignation - $presentCount;
                                                    @endphp

                                                    @if($usersCountWithDesignation > 0)
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            {{-- <td>{{ $totalContractor?->users?->department?->name }}</td> <!-- Adjust as needed --> --}}
                                                            <td>{{ Str::limit(ucwords($totalContractor->name), 25) }}</td>
                                                            <td>{{ $designation->name }}</td>
                                                            <td>
                                                                <a href="{{ route('employees-new.list', ['status' => '','contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'designation' => $designation->id, 'employeeType' => $employeeType]) }}">
                                                                    {{ $usersCountWithDesignation }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('employees-new.list', ['status' => 'present','contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'designation' => $designation->id, 'employeeType' => $employeeType]) }}">
                                                                {{ $presentCount }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('employees-new.list', ['status' => 'absent','contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'designation' => $designation->id, 'employeeType' => $employeeType]) }}">
                                                                {{ $absentCount }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="progress">
                                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                                    role="progressbar" style="width:
                                                                    {{ $usersCountWithDesignation ? floor(($presentCount / $usersCountWithDesignation) * 100) : '0'
                                                                     }}%" aria-valuenow="
                                                                     {{ $usersCountWithDesignation ? floor(($presentCount / $usersCountWithDesignation)
                                                                     * 100) : '0' }}" aria-valuemin="0" aria-valuemax="100">
                                                                     &nbsp;&nbsp; {{ $usersCountWithDesignation ?
                                                                     floor(($presentCount / $usersCountWithDesignation) * 100) : ' 0' }}% </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            ++$i;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
{{--
                                    @foreach ($totalContractors as $totalContractor)
                                        @php
                                            $currentContractorData = $todayPunchData->where( fn($item) => $item->user->contractor_id  == $totalContractor->id );
                                            $currentContractorData = $currentContractorData->where( fn($item) => $item->user->department_id == request()->department );
                                            $currentContractorData = $currentContractorData->where( fn($item) => $item->user->employee_type == $employeeType );
                                        @endphp
                                        <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                                            <div class="card custom-card rounded">
                                                <h6 class="card-header rounded bg-primary py-2 px-3  text-center"> {{ Str::limit(ucwords($totalContractor->name), 25) }}</h6>
                                                <div class="card-body px-3">
                                                    <div class="row">
                                                        <div class="col-4 br-right text-center">
                                                            <a href="{{ route('employees.list', ['status' => '', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Total</h6>
                                                            <strong style="font-size:22px">{{ $totalContractor->users_count }} </strong> <br>
                                                            </a>
                                                        </div>
                                                        <div class="col-4 br-right text-center">
                                                            <a href="{{ route('employees.list', ['status' => 'present', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Present</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ $currentContractorData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $totalContractor->users_count ? round(($currentContractorData->count()/$totalContractor->users_count)*100) : '0' }}%)</strong> <br>
                                                            </a>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <a href="{{ route('employees.list', ['status' => 'absent', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Absent</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ abs( $totalContractor->users_count-$currentContractorData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $totalContractor->users_count ? round(((($totalContractor->users_count-$currentContractorData->count() ))/$totalContractor->users_count)*100) : '0' }}%)</strong> <br>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach --}}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- <div class="col-12 px-0">
                    @if ( $is_admin && request()->department && !$totalContractors->isEmpty())
                        <div class="row">
                            <div class="card rounded">
                                <div class="card-header px-2 py-3">
                                    <h6>Contractor Wise Details</h6>
                                </div>
                                <div class="row">
                                    @foreach ($totalContractors as $totalContractor)
                                        @php
                                            $currentContractorData = $todayPunchData->where( fn($item) => $item->user->contractor_id  == $totalContractor->id );
                                            $currentContractorData = $currentContractorData->where( fn($item) => $item->user->department_id == request()->department );
                                            $currentContractorData = $currentContractorData->where( fn($item) => $item->user->employee_type == $employeeType );
                                        @endphp
                                        <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                                            <div class="card custom-card rounded">
                                                <h6 class="card-header rounded bg-primary py-2 px-3  text-center"> {{ Str::limit(ucwords($totalContractor->name), 25) }}</h6>
                                                <div class="card-body px-3">
                                                    <div class="row">
                                                        <div class="col-4 br-right text-center">
                                                            <a href="{{ route('employees.list', ['status' => '', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Total</h6>
                                                            <strong style="font-size:22px">{{ $totalContractor->users_count }} </strong> <br>
                                                            </a>
                                                        </div>
                                                        <div class="col-4 br-right text-center">
                                                            <a href="{{ route('employees.list', ['status' => 'present', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Present</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ $currentContractorData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $totalContractor->users_count ? round(($currentContractorData->count()/$totalContractor->users_count)*100) : '0' }}%)</strong> <br>
                                                            </a>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <a href="{{ route('employees.list', ['status' => 'absent', 'contractor_id' => $totalContractor->id, 'department_id' => request()->department, 'employee_type' => $employeeType]) }}">
                                                            <h6 class="mb-0">Absent</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ abs( $totalContractor->users_count-$currentContractorData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $totalContractor->users_count ? round(((($totalContractor->users_count-$currentContractorData->count() ))/$totalContractor->users_count)*100) : '0' }}%)</strong> <br>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div> --}}


                {{-- Office/Ward wise details --}}
                <div class="col-12 px-0">
                    @if ( $is_admin && request()->department )
                        <div class="row">
                            <div class="card rounded">
                                <div class="card-header px-2 py-3">
                                    <h6>Office Wise Details</h6>
                                </div>
                                <div class="row">
                                    @foreach ($totalWards as $totalWard)
                                        @php
                                            $currentWardData = $todayPunchData->where( fn($item) => $item->user->ward_id == $totalWard->id );
                                            $currentWardData = $currentWardData->where( fn($item) => $item->user->department_id == request()->department );
                                            $currentWardData = $currentWardData->where( fn($item) => $item->user->employee_type == $employeeType );
                                        @endphp
                                        <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                                            <div class="card custom-card rounded">
                                                <h6 class="card-header rounded bg-primary py-2 px-3  text-center"> {{ Str::limit(ucwords($totalWard->name), 25) }} Office</h6>
                                                <div class="card-body px-3">
                                                    <div class="row">
                                                        <div class="col-4 br-right text-center">
                                                            <h6 class="mb-0">Total</h6>
                                                            <strong style="font-size:22px">{{ $totalWard->users_count }} </strong> <br>
                                                        </div>
                                                        <div class="col-4 br-right text-center">
                                                            <h6 class="mb-0">Present</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ $currentWardData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $totalWard->users_count ? round(($currentWardData->count()/$totalWard->users_count)*100) : '0' }}%)</strong> <br>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <h6 class="mb-0">Absent</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ abs( $totalWard->users_count-$currentWardData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $totalWard->users_count ? round(((($totalWard->users_count-$currentWardData->count() ))/$totalWard->users_count)*100) : '0' }}%)</strong> <br>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="card-footer row">
                                                    <div class="col-12 col-sm-12">
                                                        <a href="{{ route('dashboard', ['ward'=> $totalWard->id]) }}" class="btn btn-primary color-green-blue font-12">CLICK HERE FOR MORE DETAILS</a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>



                {{-- Department wise details --}}
                <div class="col-12 px-0">
                    @if ( $is_admin && !request()->department )
                        <div class="row">
                            <div class="card rounded">
                                <div class="card-header px-2 py-3">
                                    <h6>Department Wise Details</h6>
                                </div>
                                <div class="row">
                                    @foreach ($departments as $department)
                                        @php
                                            $currentDeptData = $todayPunchData
                                                            // ->where( fn($item) => $item->user->ward_id == request()->ward )
                                                            ->where( fn($item) => $item->user?->department_id == $department->id )
                                                            ->where( fn($item) => $item->user->employee_type == $employeeType );
                                        @endphp
                                        <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                                            <div class="card custom-card rounded">
                                                <h6 class="card-header rounded bg-primary py-2 px-3 text-center"> {{ Str::limit(ucwords($department->name), 25) }}</h6>
                                                <div class="card-body px-3">
                                                    <div class="row">
                                                        <div class="col-4 br-right text-center">
                                                            <h6 class="mb-0">Total</h6>
                                                            <strong style="font-size:22px">{{ $department->users_count }} </strong> <br>
                                                        </div>
                                                        <div class="col-4 br-right text-center">
                                                            <h6 class="mb-0">Present</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ $currentDeptData->count() }} </span><span style="font-size:14px; display:inline-block;">({{ $department->users_count ? round(($currentDeptData->count()/$department->users_count)*100) : '0' }}%)</strong> <br>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <h6 class="mb-0">Absent</h6>
                                                            <strong style="font-size:22px; display:inline-block;">{{ abs( $department->users_count-$currentDeptData->count() ) }} </span><span style="font-size:14px; display:inline-block;">({{ $department->users_count ? round(((($department?->users_count-$currentDeptData->count() ))/$department->users_count)*100) : '0' }}%)</strong> <br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer row">
                                                    <div class="col-2 col-sm-2">
                                                        <h6 class="font-12">CL</h6>
                                                        <h3 class="font-16"><span class="counter">{{ $currentDeptData->where('leave_type_id', '6')->count() }}</span></h3>
                                                    </div>
                                                    <div class="col-2 col-sm-2">
                                                        <h6 class="font-12">EL</h6>
                                                        <h3 class="font-16"><span class="counter">{{ $currentDeptData->where('leave_type_id', '5')->count() }}</span></h3>
                                                    </div>
                                                    <div class="col-2 col-sm-2">
                                                        <h6 class="font-12">ML</h6>
                                                        <h3 class="font-16"><span class="counter">{{ $currentDeptData->where('leave_type_id', '7')->count() }}</span></h3>
                                                    </div>
                                                    <div class="col-3 col-sm-3">
                                                        <h6 class="font-12">OL</h6>
                                                        <h3 class="font-16"><span class="counter">{{ $currentDeptData->where('leave_type_id', '4')->count() }}</span></h3>
                                                    </div>
                                                    <div class="col-3 col-sm-3">
                                                        <h6 class="font-12">HDL</h6>
                                                        <h3 class="font-16"><span class="counter">{{ $currentDeptData->where('leave_type_id', '0')->count() }}</span></h3>
                                                    </div>
                                                </div>
                                                {{-- <div class="card-footer row">
                                                    <div class="col-12 col-sm-12">
                                                        <a href="{{ route('dashboard', ['department'=> $department->id]) }}" class="btn btn-primary color-green-blue font-12">CLICK HERE FOR MORE DETAILS</a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>



            </div>


        </div>
        <!-- Container-fluid Ends-->
    </div>

    @push('scripts')
        <!-- Open Change Password Modal-->
        <script>
            setInterval(function(){
                window.location.reload(1);
            }, 300000);
        </script>
    @endpush

</x-admin.admin-layout>

