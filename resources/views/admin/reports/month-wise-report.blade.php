<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Month Wise Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Month Wise Report</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('reports.index') }}">
                                @csrf
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="year">Year <span class="text-danger">*</span> </label>
                                            <select name="year" class="form-control" id="year">
                                                <option value="2024" {{ request()->year == '2024' ? 'selected' : '' }}>2024</option>
                                                <option value="2023" {{ request()->year == '2023' ? 'selected' : '' }}>2023</option>
                                                <option value="2022" {{ request()->year == '2022' ? 'selected' : '' }}>2022</option>
                                            </select>
                                            <span class="text-danger error-text year_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="month">Select Month <span class="text-danger">*</span></label>
                                            <select class="col-sm-12 form-control @error('month') is-invalid  @enderror" value="{{ old('month') }}" required name="month">
                                                <option value="">--Select Month--</option>
                                                <option value="1" {{ request()->month == 1 ? 'selected' : '' }} >January</option>
                                                <option value="2" {{ request()->month == 2 ? 'selected' : '' }} >February</option>
                                                <option value="3" {{ request()->month == 3 ? 'selected' : '' }} >March</option>
                                                <option value="4" {{ request()->month == 4 ? 'selected' : '' }} >April</option>
                                                <option value="5" {{ request()->month == 5 ? 'selected' : '' }} >May</option>
                                                <option value="6" {{ request()->month == 6 ? 'selected' : '' }} >June</option>
                                                <option value="7" {{ request()->month == 7 ? 'selected' : '' }} >July</option>
                                                <option value="8" {{ request()->month == 8 ? 'selected' : '' }} >August</option>
                                                <option value="9" {{ request()->month == 9 ? 'selected' : '' }} >September</option>
                                                <option value="10" {{ request()->month == 10 ? 'selected' : '' }} >October</option>
                                                <option value="11" {{ request()->month == 11 ? 'selected' : '' }} >November</option>
                                                <option value="12" {{ request()->month == 12 ? 'selected' : '' }} >December</option>
                                            </select>
                                            @error('month')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="from_date" id="from_date" type="date" value="{{ request()->from_date }}" placeholder="From Date" readonly>
                                            <span class="text-danger error-text from_date_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="to_date" id="to_date" type="date" value="{{ request()->to_date }}" placeholder="To Date" readonly>
                                            <span class="text-danger error-text to_date_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="department">Department <span class="text-danger">*</span> </label>
                                            <select class="js-example-basic-single col-sm-12  @error('department') is-invalid  @enderror" name="department" required>
                                                <option value="">--Select Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}" {{ request()->department == $department->id ? 'selected' : '' }} >{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="contractor">Contractor  <span class="text-danger"></span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('contractor') is-invalid  @enderror" name="contractor">
                                                <option value="">--Select Contractor--</option>
                                                @foreach ($contractors as $c)
                                                    <option value="{{ $c->id }}" {{ request()->contractor == $c->id ? 'selected' : '' }} >{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('contractor')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="designation">Designation  <span class="text-danger"></span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('designation') is-invalid  @enderror" name="designation">
                                                <option value="">--Select Designation--</option>
                                                @foreach ($designations as $des)
                                                    <option value="{{ $des->id }}" {{ request()->designation == $des->id ? 'selected' : '' }} >{{ $des->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('designation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Ward  <span class="text-danger"></span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('ward') is-invalid  @enderror" name="ward">
                                                <option value="">--Select Ward--</option>
                                                @foreach ($wards as $w)
                                                    <option value="{{ $w->id }}" {{ request()->ward == $w->id ? 'selected' : '' }} >{{ $w->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('ward')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="sub_department">Sub Department </label>
                                            <select class="js-example-basic-single col-sm-12  @error('sub_department') is-invalid  @enderror" name="sub_department">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            @error('sub_department')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class  </label>
                                            <select class="js-example-basic-single col-sm-12  @error('class') is-invalid  @enderror" name="class">
                                                <option value="">--Select Class--</option>
                                                @foreach ($class as $clas)
                                                    <option value="{{ $clas->id }}" {{ request()->class == $clas->id ? 'selected' : '' }} >{{ $clas->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" >Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        {{-- <button id="addToTable" class="btn btn-primary">Manual Attendance <i class="fa fa-plus"></i></button> --}}
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools_test">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Code</th>
                                            <th>Emp Name</th>
                                            <th>Department</th>

                                            <th>Total Working Days</th>
                                            <th>Present Days</th>
                                            <th>Late Marks</th>
                                            {{-- <th>Late Mark CL</th> --}}
                                            <th>Short Day</th>
                                            {{-- @foreach ($leaveTypes as $leaveType)
                                                <th>{{ ucfirst($leaveType->name) }}</th>
                                            @endforeach --}}
                                            {{-- <th>Paid Leaves</th> --}}
                                            <th>Week Offs</th>
                                            <th>Holidays</th>
                                            <th>Total Present Days</th>
                                            <th>Total Absent Days</th>
                                            {{-- <th style="min-width: 120px" >Leave Dates</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($empList as $emp)
                                            @php
                                                $leaveDaysCount = array_fill_keys($leavesArray, []);
                                                $shortDays = 0;
                                                $weekOffs = 0;
                                                $leaveHalfDays = $emp->punches->where('type', '2')->count();
                                                $latemark = 0;
                                                $paidLeaves = $emp->punches->where('is_paid', '1')->whereIn('leave_type_id', ['0', '1', '3', '4', '5', '6', '7'])->count();
                                                $outpostLeaves = $emp->punches->where('leave_type_id', '2')->count();
                                                $holidaysArray = $holidays->pluck('date')->toArray();
                                                $absendDays = 0;
                                                $holidayCount = 0;
                                                $actualPresent = 0;
                                                $latemarkGraceTime = $emp->is_divyang == 'y' ? $settings['LATE_MARK_TIMING_DIVYANG'] : $settings['LATE_MARK_TIMING'];
                                            @endphp

                                            @foreach ($dateRanges as $dateRange)
                                                @php
                                                    $isWeekOff = false;
                                                    $weekNameColumn = strtolower(substr($dateRange->format('D'), 0, 2));
                                                    if($weekNameColumn == 'sa' && $emp->{$weekNameColumn.'_duration'} )
                                                        $minCompletionHour = $emp->{$weekNameColumn.'_duration'};
                                                    else
                                                        $minCompletionHour = $emp->work_duration ?? $settings['MIN_COMPLETION_HOUR'];
                                                    $clonedDate = clone($dateRange);
                                                    $currentDate = $clonedDate->toDateString();
                                                    $nextDate = $clonedDate->addDay()->toDateString();
                                                    $hasPunch = 0;
                                                    $hasShift = 0;

                                                    if($emp->is_rotational == 0 && $dateRange->isWeekend() && !$emp->{$weekNameColumn.'_duration'}){ $isWeekOff = true; }
                                                    else
                                                    {
                                                        $hasShift = $emp->is_rotational == 0 ? 0 : $emp->empShifts->where('from_date', $currentDate)->where('from_date', '<', $nextDate)->first();
                                                        $isWeekOff = $hasShift && $hasShift->in_time == 'wo' ? true : false;
                                                    }

                                                    $hasPunch = $emp->punches->where('punch_date', '>=', $currentDate)->where('punch_date', '<', $nextDate)->first();
                                                    $hasLeaveApplied = $emp->punches->where('punch_date', '>=', $currentDate)->where('punch_date', '<', $nextDate)->where('leave_type_id', '!=', null)->first();

                                                    if($hasLeaveApplied)
                                                    {
                                                        array_push($leaveDaysCount[$leavesArray[$hasLeaveApplied->leave_type_id]], Carbon\Carbon::parse($hasLeaveApplied->punch_date)->toDateString());
                                                    }
                                                    elseif ($isWeekOff)
                                                    {
                                                        if(!$hasShift && $hasPunch && $hasPunch->punch_by == '0') { $actualPresent++; }
                                                        else { $weekOffs++; }
                                                    }
                                                    else
                                                    {
                                                        if ( in_array( $dateRange->format('Y-m-d'), $holidaysArray ) )
                                                        {
                                                            if($hasPunch && $hasPunch->punch_by == '0'){ $actualPresent++; }
                                                            else { $holidayCount++; }
                                                        }
                                                        elseif($hasShift && array_key_exists( $hasShift->in_time, $otherLeavesArray ) )
                                                        {
                                                            $actualPresent++;
                                                        }
                                                        elseif (!$hasPunch) { $absendDays++; }
                                                        elseif ( $hasPunch->punch_by == '2' ){ }
                                                        elseif( $hasPunch->check_in )
                                                        {
                                                            $actualPresent++;
                                                            $isNightShift = false;
                                                            if( $hasShift)
                                                            {
                                                                if(
                                                                    Carbon\Carbon::today()->startOfDay()->diffInMinutes(substr($hasPunch->check_in, 11)) >
                                                                    Carbon\Carbon::today()->startOfDay()->diffInMinutes( Carbon\Carbon::parse($hasShift->in_time)->addMinutes($latemarkGraceTime) )
                                                                )
                                                                {
                                                                    $latemark++; $islate = true;
                                                                }
                                                                if($hasShift->is_night == 1)
                                                                    $isNightShift = true;
                                                            }
                                                            elseif( $emp->is_rotational == 0 &&
                                                                    Carbon\Carbon::today()->startOfDay()->diffInMinutes(substr($hasPunch->check_in, 11)) >
                                                                    Carbon\Carbon::today()->startOfDay()->diffInMinutes( Carbon\Carbon::parse($emp->shift?->from_time ?? $defaultShift['from_time'])->addMinutes($latemarkGraceTime) )
                                                            )
                                                            { $latemark++; $islate = true;}

                                                            if( !$isNightShift && $hasPunch->duration < $minCompletionHour) {$shortDays++;}

                                                            if( $isNightShift )
                                                            {
                                                                $nextDayPunch = $emp->punches->where('punch_date', '>', $nextDate)->first();
                                                                $checkIn = $hasPunch->check_out ?? $hasPunch->check_in;
                                                                $checkOut = $nextDayPunch ? $nextDayPunch->check_in : '';
                                                                $duration = $checkOut ? Carbon\Carbon::parse( $checkIn )->diffInSeconds( $checkOut ) : 0;
                                                                if($duration < $minCompletionHour) {$shortDays++;}
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            @endforeach



                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $emp->emp_code }}</td>
                                                <td>{{ $emp->name }}</td>
                                                <td>{{ $emp->department->name }}</td>

                                                <td>{{ $totalDays }}</td>
                                                <td>{{ $actualPresent+$outpostLeaves+($leaveHalfDays/2) }}</td>
                                                <td>{{ $latemark }} </td>
                                                {{-- <td>{{ floor($latemark/3) }} </td> --}}
                                                <td>{{ ($shortDays) }}</td>
                                                {{-- @foreach ($leaveTypes as $leaveType)
                                                    @if ($leaveType->id == '6')
                                                        <td>{{ $emp->punches->where('leave_type_id', $leaveType->id)->count()+($leaveHalfDays/2) }}</td>
                                                    @else
                                                        <td>{{ $emp->punches->where('leave_type_id', $leaveType->id)->count() }}</td>
                                                    @endif
                                                @endforeach --}}
                                                {{-- <td>{{ $paidLeaves-($leaveHalfDays/2) }}</td> --}}
                                                <td>{{ $weekOffs }}</td>
                                                <td>{{ $holidayCount }}</td>
                                                @if( (($actualPresent+$paidLeaves+$outpostLeaves+($leaveHalfDays/2)) > 0) )
                                                    <td>{{ ($actualPresent + $weekOffs + $holidayCount + $paidLeaves + $outpostLeaves) }}</td>
                                                    <td>{{ $absendDays }}</td>
                                                @else
                                                    <td>0</td>
                                                    <td>{{ $totalDays }}</td>
                                                @endif
                                                {{-- <td>
                                                    @foreach ($leavesArray as $larr)
                                                        @if ( count($leaveDaysCount[$larr]) > 0 )
                                                            <strong>{{$larr}} => </strong>
                                                            @foreach ($leaveDaysCount[$larr] as $ld)
                                                                {{ $ld }},
                                                            @endforeach
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                {{-- @foreach ($leaveTypes as $leaveType)
                                                    <td></td>
                                                @endforeach --}}
                                                <td></td>
                                                {{-- <td></td>
                                                <td></td>
                                                <td></td> --}}
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>



    {{-- Show More Info Modal --}}
    <div class="modal fade" id="more-info-modal" role="dialog" >
        <div class="modal-dialog" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Punch Info</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="empMoreInfo">

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>



</x-admin.admin-layout>



<!-- Get Sub departments -->
<script>
    $("select[name='department']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('departments.sub_departments', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                if (!data.error)
                {
                    $("select[name='sub_department']").html(data.subDepartmentHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Show Details -->
<script>
    $("#datatable-tabletools_test").on("click", ".emp-more-info", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.show', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                console.log(data);
                if (data.result == 1)
                {
                    $("#more-info-modal").modal('show');
                    $("#empMoreInfo").html(data.html);
                }
                else
                {
                    swal("Error!", "Some thing went wrong", "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


{{-- Get month wise date --}}
<script>
    $(document).ready(function(){

        $("select[name='month']").change(function(e){
            e.preventDefault();
            var month = $("select[name='month']").val();
            var year = $("select[name='year']").val();

            if(month != '')
            {
                $.ajax({
                    url: "{{ route('reports.dates') }}",
                    type: 'GET',
                    data: {
                        'month': month,
                        'year': year,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error)
                        {
                            $("input[name='from_date']").val(data.fromDate);
                            $("input[name='to_date']").val(data.toDate);
                        } else {
                            swal("Error!", data.error, "error");
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "Some thing went wrong", "error");
                    },
                });
            }
            else
            {
            alert("please select month");
            }
        });

    });

    (function($) {

    'use strict';

    var datatableInit = function() {
        var $table = $('#datatable-tabletools_test');
        let month = ($("select[name=month]").find(':selected').val() != "") ? $("select[name=month]").find(':selected').text() : '';
        let fromDate = ($('#from_date').val() != "") ? $('#from_date').val() : '';
        let toDate = ($('#to_date').val() != "") ? $('#to_date').val() : '';
        let ward = ($("select[name=ward]").find(':selected').val() != "") ? $("select[name=ward]").find(':selected').text() : '';
        let department = ($("select[name=department]").find(':selected').val() != "") ? $("select[name=department]").find(':selected').text() : '';
        let subDepartment = ($("select[name=sub_department]").find(':selected').val() != "") ? $("select[name=sub_department]").find(':selected').text() : '';
        let classVal = ($("select[name=class]").find(':selected').val() != "") ? $("select[name=class]").find(':selected').text() : '';
        var reportDetails = `<b>
            Month :- ${month} <br>
            From Date :- ${fromDate} <br>
            To Date :- ${toDate} <br>
            Ward :- ${ward} <br>
            Department :- ${department} <br>
            Sub Department :- ${subDepartment} <br>
            Class :- ${classVal} <br>
        </b>`;
        var table = $table.dataTable({
            sDom: '<"text-right mb-md"T><"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
            buttons: [ {
                extend: 'print',
                messageTop: reportDetails
            },
            {
                extend: 'excel',
            },
            {
                extend: 'pdf',
            } ],

        });

        $('<div />').addClass('dt-buttons mb-2 pb-1 text-right').prependTo('#datatable-tabletools_test_wrapper');

        $table.DataTable().buttons().container().prependTo( '#datatable-tabletools_test_wrapper .dt-buttons' );

        $('#datatable-tabletools_test_wrapper').find('.btn-secondary').removeClass('btn-secondary').addClass('btn-dark ms-2');
    };

    $(function() {
        datatableInit();
    });

}).apply(this, [jQuery]);
</script>

<!-- Get Ward wise departments -->
{{-- <script>
    $("select[name='ward']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('wards.departments', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                if (!data.error)
                {
                    $("select[name='department']").html(data.departmentHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script> --}}
