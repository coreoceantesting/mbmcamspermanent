<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Muster Report</title>
    <style>
        :root {
            --border-strong: 3px solid #777;
            --border-normal: 1px solid gray;
        }

        @charset "UTF-8";

        @page {
            padding: 0 10px;
            size: us-letter;
            margin: 0px;
            margin-top: 5px !important;
        }

        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: "Source Sans 3", Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 13px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }

        h4, h5{
            font-weight: 400;
        }

        tbody tr:nth-child(odd) {
            background: #eee;
        }

        .invoice-table{
            border-spacing: 0 !important;
            border: 0.5px solid #eee;
            width: 98%;
            margin: auto;
        }
        .invoice-table thead th, .invoice-table tbody td{
            padding: 5px 3px;
            color: #000;
        }
        .invoice-table tbody td{
            text-align: center;
        }
        table.invoice-table thead th{
            border: none !important;
            font-weight: 400;
        }
        .invoice-table tbody tr td, th{
            border-left: 0.5px solid #ccc;
            border-right: 0.5px solid #ccc;
        }

        .page-break {
            page-break-after: always;
        }

        .time_table{
            border: 1px solid #eee;
            width: 98%;
            margin: auto;
            display: table;
        }

        .time_table .row{
            height: 110px;
            border-right: 1px solid #eee;
            width: auto;
            display: table-cell;
            text-align: center;
            margin: 0;
        }

        .time_table .row .row_cell{
            height: 32px;
            min-width: 25px;
            padding: 5px 5px;
            color: #000;
            margin: 0;
        }

        .time_table .row .row_cell.bg-grey{
            background-color: #eee;
            height: 20px;
        }
        .time_table .row .row_cell.bg-grey-top{
            background-color: #eee;
            height: 32px;
        }

        .time_table .row .row_cell.full_height{
            background-color: #d1d1d1;
            height: 105px;
            min-width: 15px;
        }
        .time_table .row .row_cell.full_height_green{
            background-color: #ded;
            height: 82px;
            padding: 0px;
            border: 1px solid rgb(21, 255, 0);
        }
        .time_table .row .row_cell.full_height_blue{
            background-color: rgb(214, 220, 236);
            height: 82px;
            padding: 0px;
            border: 1px solid rgb(0, 195, 255);
        }
        .time_table .row .row_cell.full_height_red{
            background-color: rgb(236, 221, 214);
            height: 82px;
            padding: 0px;
            border: 1px solid rgb(255, 81, 0);
        }
        .time_table .row .row_cell.full_height_dark{
            background-color: rgb(192, 192, 192);
            height: 82px;
            padding: 0px;
            border: 1px solid rgb(58, 58, 58);
        }
        .time_table .row .row_cell.full_height div{
            width: 8px;
            padding-left: 7px;
            word-wrap: break-word;
            line-height: 15px;
            font-weight: 500;
            text-align: center;
        }

        .colors-indicator{
            display: block;
            width: 95%;
            margin-bottom: 7px;
        }

        .colors-indicator div{
            display: inline-block;
        }

        .color-red, .color-green, .color-blue, .color-dark{
            height: 10px;
            width: 10px;
            margin-left: 15px;
            margin-right: 3px'
        }
        .color-red{
            background-color: rgb(255, 81, 0);
        }
        .color-green{
            background-color: rgb(21, 255, 0);
        }
        .color-blue{
            background-color: rgb(0, 195, 255);
        }
        .color-dark{
            background-color: rgb(58, 58, 58);
        }
    </style>
</head>

<body>
    @php
        $pageCount = 1;
    @endphp
    <!-- Table -->
    <h2 style="text-align: center; margin-top: 10px;" >Mira-Bhayandar Municipal Corporation</h2>
    <h5 style="text-align: center; margin-top: 5px; font-size:12px" >Muster Report for the month of {{ Carbon\Carbon::parse($toDate)->format('F Y') }}</h5>

    <div style="margin-bottom: 5px">
        <h4 style="text-align:left; margin-left: 15px; display:inline-block; float: left">
            Page: {{ $pageCount }}
        </h4>
        <h4 style="text-align:right; margin-right: 15px; display:inline-block; float:right">
            Generated On: {{ Carbon\Carbon::now()->toDateTimeString() }}
        </h4>
    </div>

    @foreach ($empList as $emp)
        @php
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
            
            $latemarkGraceTime = $emp->is_divyang == 'y' ? $settings['PERMANENT_LATE_MARK_TIMING_DIVYANG'] : $settings['PERMANENT_LATE_MARK_TIMING'];
        @endphp

        <table class="invoice-table" style="color:#535b61; margin-top:20px">
            <thead style="background-color:#dde2ee; border-top-left-radius: 10px;text-align:left">
                <tr>
                    <th colspan="1" style="text-align: left; font-weight:700">#{{ $emp->emp_code }} </th>
                    <th colspan="1" style="text-align: left">{{ Carbon\Carbon::parse($toDate)->format('F') }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">NAME : </th>
                    <th colspan="1" style="text-align: left">{{ $emp->name }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">OFFICE : </th>
                    <th colspan="1" style="text-align: left">{{ $emp->ward?->name }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">DEPARTMENT : </th>
                    <th colspan="1" style="text-align: left">{{ $emp->department?->name }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700"> </th>
                    <th colspan="1" style="text-align: left"></th>
                </tr>
                <tr style="border-bottom: 1px solid #ccc">
                    <th colspan="1" style="text-align: left; font-weight:700">DESIGNATION : </th>
                    <th colspan="1" style="text-align: left"> {{ $emp->designation?->name }} </th>
                    <th colspan="1" style="text-align: left; font-weight:700">CLASS : </th>
                    <th colspan="1" style="text-align: left">{{ $emp->clas?->name }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">Contractor : </th>
                    <th colspan="1" style="text-align: left">{{ $emp->contractor?->name }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">FROM DATE : </th>
                    <th colspan="1" style="text-align: left">{{ $fromDate }}</th>
                    <th colspan="1" style="text-align: left; font-weight:700">TO DATE : </th>
                    <th colspan="1" style="text-align: left">{{ $toDate }}</th>
                </tr>
            </thead>
        </table>


        <div class="time_table">
            <div class="row">
                <div class="row_cell bg-grey-top">
                    <span>DAY</span>
                </div>
                <div class="row_cell">
                    <span>IN</span>
                </div>
                <div class="row_cell">
                    <span>OUT</span>
                </div>
                <div class="row_cell bg-grey">
                    <span>WD</span>
                </div>
            </div>

            @foreach ($dateRanges as $dateRange)
                @php
                    $isWeekOff = false;
                    $islate = false;
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
                        $hasShift = $emp->is_rotational == 0 ? 0 : $emp->empShifts->where('from_date', '>=', $currentDate)->where('from_date', '<', $nextDate)->first();
                        $isWeekOff = $hasShift && $hasShift->in_time == 'wo' ? true : false;
                    }

                    $hasPunch = $emp->punches->where('punch_date', '>=', $currentDate)->where('punch_date', '<', $nextDate)->first();
                    $hasLeaveApplied = $emp->punches->where('punch_date', '>=', $currentDate)->where('punch_date', '<', $nextDate)->where('leave_type_id', '!=', null)->first();
                @endphp

                <div class="row">
                    <div class="row_cell bg-grey-top">
                        <span>{{ $dateRange->format('d') }}</span> <br>
                        <span>{{ substr($dateRange->format('D'), 0, 2) }}</span>
                    </div>

                    @if($hasLeaveApplied)
                        <div class="row_cell full_height">
                            <div>{{ $leavesArray[$hasLeaveApplied->leave_type_id] }}</div>
                        </div>
                    @elseif ($isWeekOff)
                        {{-- @if(!$hasShift && $hasPunch && $hasPunch->punch_by == '0' ) --}}
                        @if($hasPunch && $hasPunch->punch_by == '0' )
                            @php $actualPresent++; @endphp
                            <div class="row_cell full_height_green">
                                <div class="row_cell">
                                    <span>{{ Carbon\Carbon::parse($hasPunch->check_in)->format('h:i A') }}</span>
                                </div>
                                <div class="row_cell">
                                    <span>{{ $hasPunch->check_out ? ( Carbon\Carbon::parse($hasPunch->check_out)->gt(Carbon\Carbon::parse($hasPunch->check_in)->addMinutes(5)) ? Carbon\Carbon::parse($hasPunch->check_out)->format('h:i A') : '-' ) : '-' }}</span>
                                </div>
                                <div class="row_cell bg-grey">
                                    <span>{{ $hasPunch->duration_in_minute }}</span>
                                </div>
                            </div>
                        @else
                            @php $weekOffs++; @endphp
                            <div class="row_cell full_height">
                                <div>WEEKOFF</div>
                            </div>
                        @endif
                    @else

                        @if($hasShift && array_key_exists( $hasShift->in_time, $otherLeavesArray ) )
                            @php $actualPresent++; @endphp
                            <div class="row_cell full_height">
                                <div>{{ $otherLeavesArray[$hasShift->in_time] }}</div>
                            </div>

                        @elseif( in_array( $dateRange->format('Y-m-d'), $holidaysArray ) )

                            @if($hasPunch && $hasPunch->punch_by == '0')
                                @php $actualPresent++; @endphp
                                <div class="row_cell full_height_green">
                                    <div class="row_cell">
                                        <span>{{ Carbon\Carbon::parse($hasPunch->check_in)->format('h:i A') }}</span>
                                    </div>
                                    <div class="row_cell">
                                        <span>{{ $hasPunch->check_out ? ( Carbon\Carbon::parse($hasPunch->check_out)->gt(Carbon\Carbon::parse($hasPunch->check_in)->addMinutes(5)) ? Carbon\Carbon::parse($hasPunch->check_out)->format('h:i A') : '-' ) : '-' }}</span>
                                    </div>
                                    <div class="row_cell bg-grey">
                                        <span>{{ $hasPunch->duration_in_minute }}</span>
                                    </div>
                                </div>
                            @else
                                @php $holidayCount++; @endphp
                                <div class="row_cell full_height">
                                    <div>HOLIDAY</div>
                                </div>
                            @endif

                        @elseif (!$hasPunch)

                            @php $absendDays++; @endphp
                            <div class="row_cell full_height">
                                <div>ABSENT</div>
                            </div>

                        @elseif ( $hasPunch->punch_by == '2' )

                            <div class="row_cell full_height">
                                <div>{{ $leavesArray[$hasPunch->leave_type_id] }}</div>
                            </div>

                        @elseif( $hasPunch->check_in )

                            @php
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
                                { $latemark++; $islate = true; }

                                if( !$isNightShift && $hasPunch->duration < $minCompletionHour) {$shortDays++;}
                            @endphp
                            @if ( $isNightShift )
                                @php
                                    $nextDayPunch = $emp->punches->where('punch_date', '>', $nextDate)->first();
                                    $checkIn = $hasPunch->check_out ?? $hasPunch->check_in;
                                    $checkOut = $nextDayPunch ? $nextDayPunch->check_in : '';
                                    $duration = $checkOut ? Carbon\Carbon::parse( $checkIn )->diffInSeconds( $checkOut ) : 0;
                                    if($duration < $minCompletionHour) {$shortDays++;}
                                @endphp
                                <div class="{{ $islate ? 'row_cell full_height_red' : 'row_cell full_height_dark' }}">
                                    <div class="row_cell">
                                        <span>{{ Carbon\Carbon::parse($checkIn)->format('h:i A') }}</span>
                                    </div>
                                    <div class="row_cell">
                                        <span>{{ $checkOut ? Carbon\Carbon::parse($checkOut)->format('h:i A') : '-' }}</span>
                                    </div>
                                    <div class="row_cell bg-grey">
                                        <span>{{ gmdate("H:i", $duration) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="{{ $islate ? 'row_cell full_height_red' : '' }}">
                                    <div class="row_cell">
                                        <span>{{ Carbon\Carbon::parse($hasPunch->check_in)->format('h:i A') }}</span>
                                    </div>
                                    <div class="row_cell">
                                        <span>{{ $hasPunch->check_out ? ( Carbon\Carbon::parse($hasPunch->check_out)->gt(Carbon\Carbon::parse($hasPunch->check_in)->addMinutes(5)) ? Carbon\Carbon::parse($hasPunch->check_out)->format('h:i A') : '-' ) : '-' }}</span>
                                    </div>
                                    <div class="row_cell bg-grey">
                                        <span>{{ $hasPunch->duration_in_minute }}</span>
                                    </div>
                                </div>
                            @endif
                        @else

                            <div class="row_cell">
                                <span>-</span>
                            </div>
                            <div class="row_cell">
                                <span>-</span>
                            </div>
                            <div class="row_cell bg-grey">
                                <span>-</span>
                            </div>

                        @endif

                    @endif
                </div>
            @endforeach

        </div>


        <table class="invoice-table" style="color:#535b61;">
            <thead style="background-color:#ffebd0; text-align:left;">
                <tr>
                    <th colspan="1" style="text-align: center; font-weight:700">TOT DAY</th>
                    <th colspan="1" style="text-align: center; font-weight:700">PRES DAY</th>
                    <th colspan="1" style="text-align: center; font-weight:700">ABS DAY</th>
                    <th colspan="1" style="text-align: center; font-weight:700">LATE MARK</th>
                    <th colspan="1" style="text-align: center; font-weight:700">LATE MARK CL</th>
                    <th colspan="1" style="text-align: center; font-weight:700">HALF-DAY</th>
                    @foreach ($leaveTypes as $leaveType)
                        <th style="text-align: center; font-weight:700">{{ strtoupper($leaveType->name) }}</th>
                    @endforeach
                    <th colspan="1" style="text-align: center; font-weight:700">TOT PAID LEAVE</th>
                    <th colspan="1" style="text-align: center; font-weight:700">WO</th>
                    <th colspan="1" style="text-align: center; font-weight:700">HOLI</th>
                </tr>
                <tr>
                    <th colspan="1" style="text-align: center">{{ $totalDays }}</th>
                    <th colspan="1" style="text-align: center">{{ ($actualPresent+$outpostLeaves) + ($leaveHalfDays/2) }}</th>
                    <th colspan="1" style="text-align: center">{{ $absendDays }}</th>
                    <th colspan="1" style="text-align: center">{{ $latemark }}</th>
                    <th colspan="1" style="text-align: center">{{ floor($latemark/3) }}</th>
                    <th colspan="1" style="text-align: center">{{ ($shortDays) }}</th>
                    @foreach ($leaveTypes as $leaveType)
                        @if ($leaveType->id == '6')
                            <th colspan="1" style="text-align: center">{{ $emp->punches->where('leave_type_id', $leaveType->id)->count()+($leaveHalfDays/2) }}</th>
                        @else
                            <th colspan="1" style="text-align: center">{{ $emp->punches->where('leave_type_id', $leaveType->id)->count() }}</th>
                        @endif
                    @endforeach
                    <th colspan="1" style="text-align: center">{{ ($paidLeaves-($leaveHalfDays/2)) }}</th>
                    <th colspan="1" style="text-align: center">{{ $weekOffs }}</th>
                    <th colspan="1" style="text-align: center">{{ $holidayCount }}</th>
                </tr>
            </thead>
        </table>

        @if ($loop->iteration % 3 == 0)
            @php
                $pageCount++
            @endphp
            <div class="page-break"></div>

            <div class="colors-indicator">
                <div class="color-red"></div>
                <span>LATEMARK</span>
                <div class="color-green"></div>
                <span>WORKING ON WO/HOLI</span>
                <div class="color-blue"></div>
                <span>MANUAL ATT</span>
                <div class="color-dark"></div>
                <span>NIGHT SHIFT</span>
            </div>

            <div style="margin-bottom: 7px">
                <h4 style="text-align:left; margin-left: 15px; display:inline-block; float: left">
                    Page: {{ $pageCount }}
                </h4>
                <h4 style="text-align:right; margin-right: 15px; display:inline-block; float: right">
                    Generated On: {{ Carbon\Carbon::now()->toDateTimeString() }}
                </h4>
            </div>
        @endif
    @endforeach

</body>

</html>
