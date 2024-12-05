<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Attendance</x-slot>
    <livewire:styles />

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form Start -->
                <div class="row" id="addContainer" style="display:none;">
                    <div class="col-sm-12">
                        <div class="card">
                            <form class="theme-form" name="addForm" id="addForm">
                                @csrf
                                <div class="card-header pb-0">
                                    <h4>Create Attendance</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Id<span class="text-danger">*</span></label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code">
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
                                            <input class="form-control" name="name" type="text" placeholder="Employee Name" readonly>
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="ward">Office <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="ward" type="text" placeholder="Office" readonly>
                                            <span class="text-danger error-text ward_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="department">Department <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="department" type="text" placeholder="Department" readonly>
                                            <span class="text-danger error-text department_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="class" type="text" placeholder="Class" readonly>
                                            <span class="text-danger error-text class_err"></span>
                                        </div>
                                    </div>


                                    <div class="mb-3 row">

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="device_id">Select Device <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Device--</option>
                                                @foreach ($devices as $dev)
                                                    <option value="{{ $dev->DeviceId }}">{{ $dev->DeviceLocation }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="punch_date">Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="punch_date" type="date" onclick="this.showPicker()" >
                                            <span class="text-danger error-text punch_date_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_in">Check In <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_in" type="time" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text check_in_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_out">Check Out <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_out" type="time" placeholder="Enter Check Out" value="19:00:00">
                                            <span class="text-danger error-text check_out_err"></span>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Edit Form --}}
                <div class="row" id="editContainer" style="display:none;">
                    <div class="col">
                        <form class="form-horizontal form-bordered" method="post" id="editForm">
                            @csrf
                            <section class="card">
                                <header class="card-header pb-0">
                                    <h4 class="card-title">Edit Attendance</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Id<span class="text-danger">*</span></label>
                                            <input class="form-control" name="emp_code" type="text" readonly placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>
                                    </div>


                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="name">Employee Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="name" type="text" placeholder="Employee Name" readonly>
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="ward">Office <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="ward" type="text" placeholder="Office" readonly>
                                            <span class="text-danger error-text ward_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="department">Department <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="department" type="text" placeholder="Department" readonly>
                                            <span class="text-danger error-text department_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="class" type="text" placeholder="Class" readonly>
                                            <span class="text-danger error-text class_err"></span>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="device_id">Select Device <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Device--</option>
                                                @foreach ($devices as $dev)
                                                    <option value="{{ $dev->DeviceId }}">{{ $dev->DeviceLocation }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="punch_date">Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="punch_date" type="date" onclick="this.showPicker()" min="{{ Carbon\Carbon::today()->startOfYear() }}" max="{{ Carbon\Carbon::today()->endOfYear() }}" placeholder="Enter Check In" value="10:00:00">
                                            <span class="text-danger error-text punch_date_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_in">Check In <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_in" type="time" placeholder="Enter Check In" >
                                            <span class="text-danger error-text check_in_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="check_out">Check Out <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="check_out" type="time" placeholder="Enter Check Out">
                                            <span class="text-danger error-text check_out_err"></span>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" id="editSubmit">Update</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Attendance</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('punches.index') }}">
                                @csrf
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="year">Year <span class="text-danger">*</span> </label>
                                            <select name="year" class="form-control" id="year">
                                                <option value="2022" {{ date('Y') == '2022' ? 'selected' : '' }}>2022</option>
                                                <option value="2023" {{ date('Y') == '2023' ? 'selected' : '' }}>2023</option>
                                                <option value="2024" {{ date('Y') == '2024' ? 'selected' : '' }}>2024</option>
                                                <option value="2025" {{ date('Y') == '2025' ? 'selected' : '' }}>2025</option>
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
                                            <label class="col-form-label" for="class">Ward  <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('ward') is-invalid  @enderror" name="ward" required>
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

                                        @if ( auth()->user()->hasRole(['Admin', 'Super Admin']) )
                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="department">Department <span class="text-danger">*</span> </label>
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
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="">
                                        <button id="addToTable" class="btn btn-primary">Manual Attendance <i class="fa fa-plus"></i></button>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div >
                                {{-- <livewire:punch-list :from_date="{{ $request->from_date }}" /> --}}
                                @livewire('punch-list',['from_date'=> request()->from_date, 'to_date'=> request()->to_date, 'selected_ward'=> request()->ward, 'selected_department'=> request()->department])

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>



<livewire:scripts />
</x-admin.admin-layout>


<!-- Toggle Status -->
<script>
    $("#list_table").on("change", ".status", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.toggle', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                if (!data.error && !data.error2) {
                    swal("Success!", data.success, "success");
                } else {
                    if (data.error) {
                        swal("Error!", data.error, "error");
                    } else {
                        swal("Error!", data.error2, "error");
                    }
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Something went wrong", "error");
            },
        });
    });
</script>


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


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('punches.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('punches.index') }}';
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#addSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#addSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('addForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text('');
                $("[name='"+field+"']").removeClass('is-invalid');
                $("[name='"+field+"']").addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text(value);
                $("[name='"+field+"']").addClass('is-invalid');
                $("[name='"+field+"']").removeClass('is-valid');
            });
        }

    });
</script>


<!-- Edit -->
<script>
    $("#list_table").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();
                // console.log(data);
                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.punch.id);
                    $("#editForm input[name='emp_code']").val(data.punch.user.emp_code);
                    $("#editForm input[name='name']").val(data.punch.user.name);
                    $("#editForm input[name='ward']").val(data.punch.user.ward.name);
                    $("#editForm input[name='department']").val(data.punch.user.department.name);
                    $("#editForm input[name='class']").val(data.punch.user.clas.name);
                    $("#editForm select[name='device_id']").html(data.deviceHtml);
                    $("#editForm input[name='punch_date']").val(data.punch_date);
                    $("#editForm input[name='check_in']").val(data.punch.check_in);
                    $("#editForm input[name='check_out']").val(data.punch.check_out);
                }
                else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Update -->
<script>
    $(document).ready(function() {
        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('punches.update', ':model_id') }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('punches.index') }}';
                        });
                    else
                        swal("Error!", data.error2, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        $("#editSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#editSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
            });

            function resetErrors() {
                var form = document.getElementById('editForm');
                var data = new FormData(form);
                for (var [key, value] of data) {
                    var field = key.replace('[]', '');
                    $('.' + field + '_err').text('');
                    $("[name='"+field+"']").removeClass('is-invalid');
                    $("[name='"+field+"']").addClass('is-valid');
                }
            }

            function printErrMsg(msg) {
                $.each(msg, function(key, value) {
                    var field = key.replace('[]', '');
                    $('.' + field + '_err').text(value);
                    $("[name='"+field+"']").addClass('is-invalid');
                });
            }

        });
    });
</script>


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
