@php
    use App\Models\User;
@endphp
<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Vendor Working Hour Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Vendor Working Hour Report</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('vendor-reports.index') }}">
                                @csrf
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="year">Year <span class="text-danger">*</span> </label>
                                            <select name="year" class="form-control" id="year">
                                                <option value="2025" {{ request()->year == '2025' ? 'selected' : '' }}>2025</option>
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
                                            <label class="col-form-label" for="contractor">Contractor  <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('contractor') is-invalid  @enderror" name="contractor" required>
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
                                            <th>Date</th>
                                            <th>Total Employee</th>
                                            <th>Present Count</th>
                                            <th>Employee Completed Hours</th>
                                            <th>Differance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dateRanges as $index => $dateRange)
                                            @php
                                                $Date = \Carbon\Carbon::parse($dateRange)->format('Y-m-d');

                                                // Counting the number of users with punches within the date range
                                                $presentCount = User::whereNot('id', $authUser->id)
                                                    ->where('contractor_id', $contractor_id)
                                                    ->where('is_employee', 1)
                                                    ->with(['empShifts' => function($q) use ($fromDate, $toDate) {
                                                        $q->whereBetween('from_date', [$fromDate, $toDate]);
                                                    }])
                                                    ->whereHas('punches', function($q) use ($Date) {
                                                        $q->where('punch_date', $Date);
                                                    })
                                                    ->count();
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $Date }}</td>
                                                <td>{{ ($total_emp) ? $total_emp->total_emp : 0 }}</td>
                                                <td>{{ ($presentCount) ? $presentCount : 0 }}</td>
                                                <td>{{ ($presentCount) ? $presentCount * 8 : 0 }}</td>
                                                <td>{{ ($total_emp) ? ($total_emp->total_emp * 8) - ($presentCount * 8) : 0 }}</td>
                                            </tr>
                                        @endforeach
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



</x-admin.admin-layout>


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
</script>

<script>
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
