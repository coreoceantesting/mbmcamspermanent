<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employees</x-slot>
    <livewire:styles />

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


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
                                            <select class="form-select col-sm-12" name="employee_type">
                                                <option value="1" selected> Permanent </option>
                                                {{-- <option value="0"> Contractual </option> --}}
                                            </select>
                                            <span class="text-danger error-text employee_type_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Contractor Name<span class="text-danger">*</span></label>
                                            <select class="form-select col-sm-12" name="contractor_id">
                                                <option value=""> Select Contractor </option>
                                            </select>
                                            <span class="text-danger error-text contractor_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Is Rotational ?<span class="text-danger">*</span></label>
                                            <select class="form-select col-sm-12" name="is_rotational">
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
                                            <input class="form-control" id="work_duration" name="work_duration" type="number" min="1" max="12" placeholder="Enter Work Duration">
                                            <span class="text-danger error-text work_duration_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="sa_duration">Saturday Work Duration </label>
                                            <input class="form-control" id="sa_duration" name="sa_duration" type="number" min="1" max="12" placeholder="Enter Saturday Work Duration">
                                            <span class="text-danger error-text sa_duration_err"></span>
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
                    <div class="col-sm-6">
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        {{-- <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button> --}}
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div >
                                <livewire:emp-list />
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
                        <h5 class="modal-title">Employee Info</h5>
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

    {{-- Change Password Form --}}
    <div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" id="changePasswordForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="user_id" name="user_id" value="">

                        <div class="col-8 mx-auto my-2">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control" type="password" id="new_password" name="new_password">
                                    {{-- <div class="show-hide"><span class="show"></span></div> --}}
                                </div>
                                <span class="text-danger error-text password_err"></span>
                            </div>
                        </div>

                        <div class="col-8 mx-auto my-2">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control" type="password" id="confirmed_password" name="confirmed_password">
                                    {{-- <div class="show-hide"><span class="show"></span></div> --}}
                                </div>
                                <span class="text-danger error-text confirmed_password_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="changePasswordSubmit" type="submit">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<livewire:scripts />
</x-admin.admin-layout>

<!-- Open Change Password Modal-->
<script>
    $("#list_table").on("click", ".change-password", function(e) {
        e.preventDefault();
        var user_id = $(this).attr("data-id");
        $('#user_id').val(user_id);
        $('#change-password-modal').modal('show');
    });
</script>

<!-- Update User Password -->
<script>
    $("#changePasswordForm").submit(function(e) {
        e.preventDefault();
        $("#changePasswordSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        formdata.append('_method', 'PUT');
        var model_id = $('#user_id').val();
        var url = "{{ route('users.change-password', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#changePasswordSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        $("#change-password-modal").modal('hide');
                        $("#changePasswordSubmit").prop('disabled', false);
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#changePasswordSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#changePasswordSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('changePasswordForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                $('.' + key + '_err').text('');
                $('#' + key).removeClass('is-invalid');
                $('#' + key).addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
                $('#' + key).addClass('is-invalid');
                $('#' + key).removeClass('is-valid');
            });
        }

    });
</script>

<!-- Toggle Status -->
<script>
    $("#list_table").on("change", ".status", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.toggle', ':model_id') }}";

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


<!-- Edit -->
<script>
    $("#list_table").on("click", ".edit-element", function(e) {
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
                            window.location.href = '{{ route('employees.index') }}';
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


<!-- Retire Employee -->
<script>
    $("#list_table").on("click", ".retire", function(e) {
        e.preventDefault();
        swal({
                title: "Are you sure to retire this employee?",
                icon: "warning",
                buttons: ["Cancel", "Confirm"]
            })
            .then((yes) => {
                if (yes) {
                var model_id = $(this).attr("data-id");
                var url = "{{ route('users.retire', ':model_id') }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'GET',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (!data.error && !data.error2) {
                            swal("Success!", data.success, "success");
                            Livewire.emitTo('emp-list', '$refresh');
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


<!-- Show Details -->
<script>
    $("#list_table").on("click", ".emp-more-info", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('employees.show', ':model_id') }}";

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

<!-- Get Ward wise departments -->
<script>
    $("#addForm select[name='ward_id']").change( function(e) {
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
                    $("select[name='department_id']").html(data.departmentHtml);
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

<script>
    $(document).ready(function(){

        $('select[name="is_rotational"]').on('change', function() {
        if ( this.value === '1')
            $("select[name='shift_id']").closest('.col-md-4').addClass('d-none');
        else
            $("select[name='shift_id']").closest('.col-md-4').removeClass('d-none');
        });

    });
</script>
