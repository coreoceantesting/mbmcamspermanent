<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employee Wise Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Employee Wise Report</h3>

                        <div class="card">
                            <form class="theme-form" id="addForm" method="GET" action="{{ route('dashboard.employee-wise-report') }}">
                                @csrf
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Id<span class="text-danger">*</span></label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code" required>
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="col-form-label" for="searchEmpCode">&nbsp;</label>
                                            <button class="btn btn-primary mt-5" type="button" id="searchEmpCode">Search</button>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="name">Employee Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="name" type="text" placeholder="Employee Name" readonly required>
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="ward">Office <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="ward" type="text" placeholder="Office" readonly required>
                                            <span class="text-danger error-text ward_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="department">Department <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="department" type="text" placeholder="Department" readonly required>
                                            <span class="text-danger error-text department_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="class" type="text" placeholder="Class" readonly required>
                                            <span class="text-danger error-text class_err"></span>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="from_date">From Date</label>
                                            <input class="form-control" name="from_date" type="date">
                                            <span class="text-danger error-text from_date_err"></span>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="to_date">From Date</label>
                                            <input class="form-control" name="to_date" type="date">
                                            <span class="text-danger error-text to_date_err"></span>
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

                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Code</th>
                                            <th>Emp Name</th>
                                            <th>Device Name</th>
                                            <th>Device Serial</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Punch Date</th>
                                            <th>Is Latemark</th>
                                            <th>Work Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data)
                                            @foreach ($data as $emp)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $emp->emp_code }}</td>
                                                    <td>{{ $emp->user->name }}</td>
                                                    <td>{{ $emp->device?->DeviceLocation }}</td>
                                                    <td>{{ $emp->device?->SerialNumber}}</td>
                                                    <td>{{ $emp->check_in }}</td>
                                                    <td>{{ $emp->check_out }}</td>
                                                    <td>{{ Carbon\Carbon::parse($emp->punch_date)->toDateString() }}</td>
                                                    <td>{{ $emp->is_latemark ? 'yes' : 'No' }}</td>
                                                    <td>{{ $emp->duration_in_minute }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
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

{{-- Fetch Emp Info from Emp Code --}}
<script>
    $(document).ready(function(){
        $("#searchEmpCode").click(function(e){
            var empCode = $("input[name='emp_code']").val();

            if( empCode == '' )
                swal("Error!", "Please enter employee code", "error");
            else
            {
                var url = "{{ route('employees.info', ':model_id') }}";
                $.ajax({
                    url: url.replace(':model_id', empCode),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error2)
                        {
                            $("#addForm input[name='name']").val(data.name);
                            $("#addForm input[name='ward']").val(data.ward.name);
                            $("#addForm input[name='department']").val(data.department.name);
                            $("#addForm input[name='class']").val(data.clas.name);
                        } else {
                            swal("Error!", data.error2, "error");
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "No Employee found for this Emp code", "error");
                    },
                });
            }
        });
    });
</script>


<!-- Get Ward wise departments -->
<script>
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
</script>
