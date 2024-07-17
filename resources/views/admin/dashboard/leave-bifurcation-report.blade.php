<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Leave Bifurcation Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Leave Bifurcation Report</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('dashboard.todays-leave-bifurcation') }}">
                                @csrf
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="year">Year <span class="text-danger">*</span> </label>
                                            <select name="year" class="form-control" id="year">
                                                <option value="2022" {{ date('Y') == '2022' ? 'selected' : '' }}>2022</option>
                                                <option value="2023" {{ date('Y') == '2023' ? 'selected' : '' }}>2023</option>
                                                <option value="2024" {{ date('Y') == '2024' ? 'selected' : '' }}>2024</option>
                                            </select>
                                            <span class="text-danger error-text year_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="month">Select Date </label>
                                            <input class="form-control" name="date" type="date" onclick="this.showPicker()" value="{{ request()->date }}" >
                                            @error('date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="ward">Select Ward </label>
                                            <select class="js-example-basic-single col-sm-12  @error('ward') is-invalid  @enderror" name="ward">
                                                <option value="">--Select ward--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}" {{ request()->ward == $ward->id ? 'selected' : '' }} >{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('ward')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        @if ( $isAdmin )
                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="department">Department  </label>
                                                <select class="js-example-basic-single col-sm-12  @error('department') is-invalid  @enderror" name="department">
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
                                        @endif

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
                                <table class="display table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Code</th>
                                            <th>Emp Name</th>
                                            <th>Device</th>
                                            <th>Office</th>
                                            <th>Department</th>
                                            <th>Leave Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $value)
                                            @if ($value->user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $value->user->emp_code }}</td>
                                                    <td>{{ $value->user->name }}</td>
                                                    <td>{{ $value->user->device?->name }}</td>
                                                    <td>{{ $value->user->ward?->name }}</td>
                                                    <td>{{ $value->user->department?->name }}</td>
                                                    <td>{{ $value->leaveType?->name }}</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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



</x-admin.admin-layout>



<!-- Get Sub departments -->
<script>
    $("select[name='department_id']").change( function(e) {
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
                    $("select[name='sub_department_id']").html(data.subDepartmentHtml);
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
    $("#datatable-tabletools").on("click", ".emp-more-info", function(e) {
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
</script>


