<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Users</x-slot>

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
                                    <h4>Create User</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Employee Code </label>
                                            <input class="form-control" id="emp_code" name="emp_code" type="text" placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="clas_id">Select Clas <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="clas_id" name="clas_id">
                                                <option value="">--Select Department--</option>
                                                @foreach ($class as $clas)
                                                    <option value="{{ $clas->id }}">{{ $clas->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="designation_id">Select Designation <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="designation_id" name="designation_id">
                                                <option value="">--Select Department--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text designation_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="department_id[]">Select Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="department_id" name="department_id[]" multiple>
                                                <option value="">--Select Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="sub_department_id">Select Sub Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="sub_department_id" name="sub_department_id">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            <span class="text-danger error-text sub_department_id_err"></span>
                                        </div> --}}

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="ward">Select Office <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="ward" name="ward_id">
                                                <option value="">--Select Office--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text role_err"></span>
                                        </div> --}}

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="name">User Name <span class="text-danger">*</span></label>
                                            <input class="form-control" id="name" name="name" type="text" placeholder="Enter User Name">
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="email">User Email <span class="text-danger">*</span></label>
                                            <input class="form-control" id="email" name="email" type="text" placeholder="Enter User Email">
                                            <span class="text-danger error-text email_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="mobile">User Mobile</label>
                                            <input class="form-control" id="mobile" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter User Mobile">
                                            <span class="text-danger error-text mobile_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                            <input class="form-control" id="dob" name="dob" type="date" onclick="this.showPicker()" placeholder="Enter User Mobile">
                                            <span class="text-danger error-text dob_err"></span>
                                        </div> --}}

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
                                            <label class="col-form-label" for="role">Select User Type / Role <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="role" name="role">
                                                <option value="">--Select Role--</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text role_err"></span>
                                        </div>


                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="password">Password <span class="text-danger">*</span></label>
                                            <input class="form-control" id="password" name="password" type="password" placeholder="********">
                                            <span class="text-danger error-text password_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                            <input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="********">
                                            <span class="text-danger error-text confirm_password_err"></span>
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
                                <header class="card-header">
                                    <h4 class="card-title">Edit User</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Employee Code </label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="clas_id_edit">Select Clas <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="clas_id_edit" name="clas_id">
                                                <option value="">--Select Department--</option>
                                                @foreach ($class as $clas)
                                                    <option value="{{ $clas->id }}">{{ $clas->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="designation_id_edit">Select Designation <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="designation_id_edit" name="designation_id">
                                                <option value="">--Select Department--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text designation_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="department_id_edit">Select Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="department_id_edit" name="department_id[]" multiple>
                                                <option value="">--Select Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="sub_department_id">Select Sub Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="sub_department_id">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            <span class="text-danger error-text sub_department_id_err"></span>
                                        </div> --}}

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="ward">Select Office <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" id="ward" name="ward_id">
                                                <option value="">--Select Office--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text role_err"></span>
                                        </div> --}}

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="name">User Name <span class="text-danger">*</span></label>
                                            <input class="form-control" name="name" type="text" placeholder="Enter User Name">
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="email">User Email</label>
                                            <input class="form-control" name="email" type="text" placeholder="Enter User Email">
                                            <span class="text-danger error-text email_err"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="mobile">User Mobile</label>
                                            <input class="form-control" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter User Mobile">
                                            <span class="text-danger error-text mobile_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                            <input class="form-control" name="dob" type="date" onclick="this.showPicker()" placeholder="Enter User Mobile">
                                            <span class="text-danger error-text dob_err"></span>
                                        </div> --}}

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="editRadioMale">
                                                    <input class="radio_animated" type="radio" name="gender" checked="" value="m">Male
                                                </label>
                                                <label class="me-3" for="editRadioFemale">
                                                    <input class="radio_animated" type="radio" name="gender" value="f">Female
                                                </label>
                                            </div>
                                            <span class="text-danger error-text gender_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label">Select User Type / Role <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="role">
                                                <option value="">--Select Role--</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text role_err"></span>
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
                        <h3>Users</h3>
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
                                        <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Department</th>
                                            <th style="min-width: 100px;">Status</th>
                                            <th>Registered On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->mobile }}</td>
                                                <td>{{ $user->department?->name }}</td>
                                                <td>
                                                    <div class="media-body text-end icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox" class="status" data-id="{{ $user->id }}" {{ $user->active_status == '1' ? 'checked' : '' }}><span class="switch-state"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M, y h:i:s') }}
                                                </td>
                                                <td>
                                                    <button class="edit-element btn btn-primary px-2 py-1" title="Edit User" data-id="{{ $user->id }}"><i data-feather="edit"></i></button>
                                                    <button class="btn btn-primary change-password px-2 py-1" title="Change Password" data-id="{{ $user->id }}"><i data-feather="lock"></i></button>
                                                    <button class="btn btn-warning assign-role px-2 py-1" title="Assign Role" data-id="{{ $user->id }}"><i data-feather="user-check"></i></button>
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
        <!-- Container-fluid Ends-->
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


    {{-- Assign Role Modal --}}
    <div class="modal fade" id="assign-role-modal" role="dialog" >
        <div class="modal-dialog" role="document">
            <form action="" id="assignRoleForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Role</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="role_user_id" name="role_user_id" value="">

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="name">User Name : </label>
                            <div class="col-sm-9">
                                <h6 id="role_user_name" class="pt-2"></h6>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label" for="name">Role : </label>
                            <div class="col-sm-9">
                                <select class="js-example-basic-single" id="edit_role" name="edit_role">
                                    <option value="">--Select Role--</option>
                                </select>
                                <span class="text-danger error-text edit_role_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="assignRoleSubmit" type="submit">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-admin.admin-layout>


<!-- Toggle Status -->
<script>
    $("#datatable-tabletools").on("change", ".status", function(e) {
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


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('users.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('users.index') }}';
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


<!-- Open Change Password Modal-->
<script>
    $("#datatable-tabletools").on("click", ".change-password", function(e) {
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


<!-- Edit -->
<script>
    $("#datatable-tabletools").on("click", ".edit-element", function(e) {
        e.preventDefault();
        // $(".edit-element").show();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#editForm input[name='emp_code']").val(data.user.emp_code);
                    $("#department_id_edit").html(data.departmentHtml);
                    $("#department_id_edit").trigger('change');;
                    $("#editForm select[name='clas_id']").html(data.clasHtml).change();
                    $("#editForm select[name='designation_id']").html(data.designationHtml).change();
                    //$("#editForm select[name='sub_department_id']").html(data.subDepartmentHtml);
                    // $("#editForm input[name='dob']").val(data.user.dob);
                    data.user.gender == 'm' ? $("#editForm input[name='gender'][value='m']").prop("checked", true) : $("#editForm input[name='gender'][value='f']").prop("checked", true) ;
                    $("#editForm select[name='role']").html(data.roleHtml);
                    $("#editForm input[name='name']").val(data.user.name);
                    $("#editForm input[name='email']").val(data.user.email);
                    $("#editForm input[name='mobile']").val(data.user.mobile);
                    $("#editForm input[name='emp_code']").val(data.user.emp_code);
                    // $("#editForm select[name='ward_id']").html(data.wardHtml);
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
        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('users.update', ':model_id') }}";
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
                            window.location.href = '{{ route('users.index') }}';
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
</script>


<!-- Open Assign Role Modal-->
<script>
    $("#datatable-tabletools").on("click", ".assign-role", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('users.get-role', ':model_id') }}";
        $('#role_user_id').val(model_id);

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {

                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.user.id);
                    $("#edit_role").html(data.roleHtml);
                    $("#role_user_name").text(data.user.name);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });

        $('#assign-role-modal').modal('show');
    });
</script>

<!-- Update User Role -->
<script>
    $("#assignRoleForm").submit(function(e) {
        e.preventDefault();
        $("#assignRoleSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        formdata.append('_method', 'PUT');
        var model_id = $('#role_user_id').val();
        var url = "{{ route('users.assign-role', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#assignRoleSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        $("#assign-role-modal").modal('hide');
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#assignRoleSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#assignRoleSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('assignRoleForm');
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
