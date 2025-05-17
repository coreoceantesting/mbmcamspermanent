<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employees</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form -->



                {{-- Edit Form --}}
                <div class="row" id="editContainer" style="display:none;">
                    <div class="col">
                        <form class="form-horizontal form-bordered" method="post" id="editForm">
                            @csrf
                            <section class="card">
                                <header class="card-header pb-0">
                                    <h4 class="card-title">Edit Employee</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Employee Code <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="name">Employee Name <span class="text-danger">*</span></label>
                                            <input class="form-control" name="name" type="text" placeholder="Enter Employee Name">
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="email">Employee Email </label>
                                            <input class="form-control" name="email" type="email" placeholder="Enter Employee Email">
                                            <span class="text-danger error-text email_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="mobile">Employee Mobile </label>
                                            <input class="form-control" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter Employee Mobile">
                                            <span class="text-danger error-text mobile_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="aadhaar_no">Aadhaar No </label>
                                            <input class="form-control" name="aadhaar_no" type="number" placeholder="Enter Aadhaar No">
                                            <span class="text-danger error-text aadhaar_no_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="permanent_address">Permanent Address </label>
                                            <input class="form-control" name="permanent_address" type="text" placeholder="Enter Permanent Address">
                                            <span class="text-danger error-text permanent_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="present_address">Present Address </label>
                                            <input class="form-control" name="present_address" type="text" placeholder="Enter Present Address">
                                            <span class="text-danger error-text present_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="dob">Date of Birth </label>
                                            <input class="form-control" id="dob" name="dob" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Birth">
                                            <span class="text-danger error-text dob_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="doj">Date of Joining </label>
                                            <input class="form-control" id="doj" name="doj" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Joining">
                                            <span class="text-danger error-text doj_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radioMale">
                                                    <input class="radio_animated" id="radioMale" type="radio" name="gender" checked="" value="m">Male
                                                </label>
                                                <label class="me-3" for="radioFemale">
                                                    <input class="radio_animated" id="radioFemale" type="radio" name="gender" value="f">Female
                                                </label>
                                            </div>
                                            <span class="text-danger error-text gender_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Employee Type<span class="text-danger">*</span></label>
                                            <select class="form-control col-sm-12" name="employee_type">
                                                <option value="1"> Permanent </option>
                                                <option value="0"> Contractual </option>
                                            </select>
                                            <span class="text-danger error-text employee_type_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Contractor Name<span class="text-danger">*</span></label>
                                            <select class="form-control col-sm-12" name="contractor_id">
                                                <option value=""> Select Contractor </option>
                                            </select>
                                            <span class="text-danger error-text contractor_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Is Rotational ?<span class="text-danger">*</span></label>
                                            <select class="form-control col-sm-12" name="is_rotational">
                                                <option value=""> Is Rotational ? </option>
                                                <option value="0"> No </option>
                                                <option value="1"> Yes </option>
                                            </select>
                                            <span class="text-danger error-text is_rotational_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3 d-none">
                                            <label class="col-form-label" >Select Shift <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="shift_id">
                                                <option value="">--Select Shift--</option>
                                            </select>
                                            <span class="text-danger error-text shift_id_err"></span>
                                        </div>




                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Office <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="ward_id">
                                                <option value="">--Select Office--</option>
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="department_id">Select Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="department_id">
                                                <option value="">--Select Department--</option>
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Sub Department </label>
                                            <select class="js-example-basic-single col-sm-12" name="sub_department_id">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            <span class="text-danger error-text sub_department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Machine <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Machine--</option>
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Class <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="clas_id">
                                                <option value="">--Select Class--</option>
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Designation </label>
                                            <select class="js-example-basic-single col-sm-12" name="designation_id">
                                                <option value="">--Select Designation--</option>
                                            </select>
                                            <span class="text-danger error-text designation_id_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Shift <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="shift_id">
                                                <option value="">--Select Shift--</option>
                                            </select>
                                            <span class="text-danger error-text shift_id_err"></span>
                                        </div> --}}

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Choose In Time </label>
                                            <input type="time" class="form-control" name="in_time" value="10:00:00" onclick="this.showPicker()">
                                            <span class="text-danger error-text in_time_err"></span>
                                        </div> --}}

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_ot">Is OT Allow ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" checked="" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_divyang">Is Employee Divyang ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_divyang_yes">
                                                    <input class="radio_animated" id="radio_divyang_yes" type="radio" name="is_divyang" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_divyang_no">
                                                    <input class="radio_animated" id="radio_divyang_no" type="radio" name="is_divyang" checked="" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="work_duration">Work Duration </label>
                                            <input class="form-control" id="work_duration" name="work_duration" type="number" min="1" max="12" placeholder="Enter Work Duration" step="any">
                                            <span class="text-danger error-text work_duration_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="sa_duration">Saturday Work Duration </label>
                                            <input class="form-control" id="sa_duration" name="sa_duration" type="number" min="1" max="12" placeholder="Enter Saturday Work Duration" step="any">
                                            <span class="text-danger error-text sa_duration_err"></span>
                                        </div>

                                    </div>
                                    <h5 class="mt-4">Leave  Durations</h5>

                                        <div class="row">
                                            @foreach ($leave_types as $leave_type)
                                                <div class="col-md-4 mt-3">
                                                    <label class="col-form-label" for="leave_duration_{{ $leave_type->id }}">
                                                        {{ $leave_type->name }} Duration
                                                    </label>
                                                    <input
                                                        class="form-control"
                                                        id="leave_duration_{{ $leave_type->id }}"
                                                        name="leave_durations[{{ $leave_type->id }}]"
                                                        type="number"
                                                        value="0"
                                                        placeholder="Enter {{ $leave_type->name }} Duration"
                                                        step="any">
                                                    <span class="text-danger error-text leave_duration_{{ $leave_type->id }}_err"></span>
                                                </div>
                                            @endforeach
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

                        <h3>Employees</h3>

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
                                <table class="table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Id</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Office</th>
                                            <th>Location</th>
                                            {{-- <th>Emp Type</th>
                                            <th>Contractor</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $employee->emp_code }}</td>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->department_name }}</td>
                                                <td>{{ $employee->designations_name }}</td>
                                                <td>{{ $employee->ward_name }}</td>
                                                <td>{{ $employee->location_name }}</td>
                                                {{-- <td>{{ $employee->employee_type == 0 ? 'Contractual' : 'Permanent' }}</td>
                                                <td>{{ $employee?->contractor?->name }}</td> --}}
                                                <td>
                                                    {{-- @can('classes.edit') --}}
                                                        <button class="edit-element btn btn-primary px-2 py-1" title="Edit Employee" data-id="{{ $employee->id }}"><i data-feather="edit"></i></button>
                                                    {{-- @endcan
                                                    @can('classes.delete')
                                                        <button class="btn btn-dark rem-element px-2 py-1" title="Delete clas" data-id="{{ $clas->id }}"><i data-feather="trash-2"></i> </button>
                                                    @endcan --}}
                                                </td>
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
        <!-- Container-fluid Ends -->
    </div>


</x-admin.admin-layout>

<!-- Edit -->
<script>
    $("#datatable-tabletools").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('employees.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error) {
                    if ( data.user.is_rotational == '1')
                        $("select[name='shift_id']").closest('.col-md-4').addClass('d-none');
                    else
                        $("select[name='shift_id']").closest('.col-md-4').removeClass('d-none');


                    if ( data.user.employee_type == '0')
                        $("select[name='contractor_id']").closest('.col-md-4').removeClass('d-none');
                    else
                        $("select[name='contractor_id']").closest('.col-md-4').addClass('d-none');

                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#editForm input[name='emp_code']").val(data.user.emp_code);
                    $("#editForm select[name='department_id']").html(data.departmentHtml);
                    $("#editForm select[name='sub_department_id']").html(data.subDepartmentHtml);
                    $("#editForm select[name='ward_id']").html(data.wardHtml);
                    $("#editForm select[name='device_id']").html(data.deviceHtml);
                    $("#editForm select[name='clas_id']").html(data.clasHtml);
                    $("#editForm select[name='designation_id']").html(data.designationHtml);
                    $("#editForm select[name='shift_id']").html(data.shiftHtml);
                    $("#editForm select[name='contractor_id']").html(data.contractorHtml);
                    // $("#editForm input[name='in_time']").val(data.user.in_time);
                    $("#editForm input[name='name']").val(data.user.name);
                    $("#editForm input[name='email']").val(data.user.email);
                    $("#editForm input[name='mobile']").val(data.user.mobile);
                    $("#editForm input[name='aadhaar_no']").val(data.user.aadhaar_no);
                    $("#editForm input[name='dob']").val(data.user.dob);
                    $("#editForm input[name='doj']").val(data.user.doj);
                    $("#editForm input[name='present_address']").val(data.user.present_address);
                    $("#editForm input[name='permanent_address']").val(data.user.permanent_address);
                    $("#editForm select[name='employee_type']").val(data.user.employee_type);
                    $("#editForm select[name='is_rotational']").val(data.user.is_rotational);
                    data.user.gender == 'm' ? $("#editForm input[name='gender'][value='m']").prop("checked", true) : $("#editForm input[name='gender'][value='f']").prop("checked", true) ;
                    data.user.is_ot == 'y' ? $("#editForm input[name='is_ot'][value='y']").prop("checked", true) : $("#editForm input[name='is_ot'][value='n']").prop("checked", true) ;
                    data.user.is_divyang == 'y' ? $("#editForm input[name='is_divyang'][value='y']").prop("checked", true) : $("#editForm input[name='is_divyang'][value='n']").prop("checked", true) ;
                    $("#editForm input[name='work_duration']").val(data.user.work_duration);
                    $("#editForm input[name='sa_duration']").val(data.user.sa_duration);

                    if (data.user_leaves && Array.isArray(data.user_leaves)) {
                        data.user_leaves.forEach(function (leave) {
                            const input = $("#editForm input[name='leave_durations[" + leave.leave_type_id + "]']");
                            if (input.length) {
                                input.val(leave.leave_days);
                            }
                        });
                    }
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


<!-- Update -->
<script>
    $(document).ready(function() {

        $('select[name="employee_type"]').on('change', function() {
        if ( this.value == '0')
            $("select[name='contractor_id']").closest('.col-md-4').removeClass('d-none');
        else
            $("select[name='contractor_id']").closest('.col-md-4').addClass('d-none');
        });


        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('employees.update', ':model_id') }}";
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
                            window.location.href = '{{ route('employees-new.index') }}';
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


{{-- <!-- Edit -->
<script>
    $("#datatable-tabletools").on("click", ".edit-element", function(e) {
        e.preventDefault();
        $(".edit-element").show();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('clas.edit', ":model_id") }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error)
                {
                    $("#editForm input[name='edit_model_id']").val(data.clas.id);
                    $("#editForm input[name='edit_name']").val(data.clas.name);
                }
                else
                {
                    alert(data.error);
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                alert("Some thing went wrong");
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
            var url = "{{ route('clas.update', ":model_id") }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                            .then((action) => {
                                window.location.href = '{{ route('clas.index') }}';
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
                    $('#' + field).removeClass('is-invalid');
                    $('#' + field).addClass('is-valid');
                }
            }

            function printErrMsg(msg) {
                $.each(msg, function(key, value) {
                    var field = key.replace('[]', '');
                    $('.' + field + '_err').text(value);
                    $('#' + field).addClass('is-invalid');
                });
            }

        });
    });
</script> --}}
