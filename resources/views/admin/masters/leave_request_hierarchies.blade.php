<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Leave Request Hierarchies</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form -->
                <div class="row" id="addContainer" style="display:none;">
                    <div class="col-sm-12">
                        <div class="card">
                            <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                                @csrf

                                <div class="card-body">
                                    <div class="row">


                                        {{-- Class --}}
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="class_id">Class <span class="text-danger">*</span></label>
                                            <select name="clas_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('class_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>

                                        {{-- Requester Designation --}}
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="requester_designation_id">Requester Designation <span class="text-danger">*</span></label>
                                            <select name="requester_designation_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}" {{ old('requester_designation_id') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text requester_designation_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('requester_designation_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>

                                        {{-- Requester Department --}}
                                        <div class="col-md-4 ">
                                            <label class="col-form-label" for="requester_department_id">Requester Department <span class="text-danger">*</span></label>
                                            <select name="requester_department_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('requester_department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text requester_department_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('requester_department_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>

                                        {{-- Loop over 4 Approvers --}}
                                        @for ($i = 1; $i <= 4; $i++)
                                            {{-- Approver Designation --}}
                                            <div class="col-md-6 mt-4">
                                                <label class="col-form-label" for="{{ $i }}_approver_designation_id">
                                                    Approver Designation {{ $i }} @if ($i == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <select name="{{ $i }}_approver_designation_id" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($designations as $designation)
                                                        <option value="{{ $designation->id }}" {{ old("{$i}_approver_designation_id") == $designation->id ? 'selected' : '' }}>
                                                            {{ $designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text {{ $i }}_approver_designation_id_err"></span>
                                                <span class="text-danger">
                                                    @error("{$i}_approver_designation_id")
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                            {{-- Approver Department --}}
                                            <div class="col-md-6 mt-4">
                                                <label class="col-form-label" for="{{ $i }}_approver_department_id">
                                                    Approver Department {{ $i }} @if ($i == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <select name="{{ $i }}_approver_department_id" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" {{ old("{$i}_approver_department_id") == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text {{ $i }}_approver_department_id_err"></span>
                                                <span class="text-danger">
                                                    @error("{$i}_approver_department_id")
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        @endfor
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
                                    <h4 class="card-title">Edit Leave Hierarchies</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="row mb-3">
                                        {{-- Class --}}
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="class_id">Class <span class="text-danger">*</span></label>
                                            <select name="clas_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('class_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-form-label" for="requester_designation_id">Requester Designation <span class="text-danger">*</span></label>
                                            <select name="requester_designation_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}" {{ old('requester_designation_id') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text requester_designation_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('requester_designation_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        {{-- Requester Department --}}
                                        <div class="col-md-4 ">
                                            <label class="col-form-label" for="requester_department_id">Requester Department <span class="text-danger">*</span></label>
                                            <select name="requester_department_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('requester_department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text requester_department_id_err"></span>
                                            <span class="text-danger error-text">
                                                @error('requester_department_id')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        {{-- Loop over 4 Approvers --}}
                                        @for ($i = 1; $i <= 4; $i++)
                                            {{-- Approver Designation --}}
                                            <div class="col-md-6 mt-4">
                                                <label class="col-form-label" for="{{ $i }}_approver_designation_id">
                                                    Approver Designation {{ $i }} @if ($i == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <select name="{{ $i }}_approver_designation_id" id="{{ $i }}_approver_designation_id" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($designations as $designation)
                                                        <option value="{{ $designation->id }}" {{ old("{$i}_approver_designation_id") == $designation->id ? 'selected' : '' }}>
                                                            {{ $designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text {{ $i }}_approver_designation_id_err"></span>
                                                <span class="text-danger">
                                                    @error("{$i}_approver_designation_id")
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                            {{-- Approver Department --}}
                                            <div class="col-md-6 mt-4">
                                                <label class="col-form-label" for="{{ $i }}_approver_department_id">
                                                    Approver Department {{ $i }} @if ($i == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <select name="{{ $i }}_approver_department_id" id="{{ $i }}_approver_department_id" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" {{ old("{$i}_approver_department_id") == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text {{ $i }}_approver_department_id_err"></span>
                                                <span class="text-danger">
                                                    @error("{$i}_approver_department_id")
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        @endfor
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary" id="editSubmit">Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <h3>Leave Request Hierarchies</h3>

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
                                        @can('leave_request_hierarchies.create')
                                        <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                         @endcan
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Requester Class</th>
                                            <th>Requester Designation</th>
                                            <th>Requester Department</th>
                                            <th>First Approver Designation</th>
                                            <th>First Approver Department</th>
                                            <th>Second Approver Designation</th>
                                            <th>Second Approver Department</th>
                                            <th>Third Approver Designation</th>
                                            <th>Third Approver Department</th>
                                            <th>Fourth Approver Designation</th>
                                            <th>Fourth Approver Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leave_request_hierarchies as $leave_request_hierarchie)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $leave_request_hierarchie->requesterClass->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->requesterDesignation->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->requesterDepartment->name ?? 'NA' }}</td>

                                                <td>{{ $leave_request_hierarchie->firstApproverDesignations->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->firstApproverDepartments->name ?? 'NA' }}</td>

                                                <td>{{ $leave_request_hierarchie->secondApproverDepartments->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->secondApproverDesignations->name ?? 'NA' }}</td>

                                                <td>{{ $leave_request_hierarchie->thirdApproverDepartments->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->thirdApproverDesignations->name ?? 'NA' }}</td>

                                                <td>{{ $leave_request_hierarchie->fourthApproverDepartments->name ?? 'N/A' }}</td>
                                                <td>{{ $leave_request_hierarchie->fourthApproverDesignations->name ?? 'NA' }}</td>
                                                <td>
                                                    @can('leave_request_hierarchies.edit')
                                                      <button class="edit-element btn btn-primary px-2 py-1" title="Edit leave request hierarchie" data-id="{{ $leave_request_hierarchie->id }}"><i data-feather="edit"></i></button>
                                                    @endcan
                                                    @can('leave_request_hierarchies.delete')
                                                        <button class="btn btn-dark rem-element px-2 py-1" title="Delete leave request hierarchie" data-id="{{ $leave_request_hierarchie->id }}"><i data-feather="trash-2"></i> </button>
                                                    @endcan
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

{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('leave_request_hierarchies.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('leave_request_hierarchies.index') }}';
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


<!-- Delete -->
<script>
    $("#datatable-tabletools").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
                title: "Are you sure to delete this leave request hierarchie?",
                // text: "Make sure if you have filled Vendor details before proceeding further",
                icon: "info",
                buttons: ["Cancel", "Confirm"]
            })
            .then((justTransfer) => {
                if (justTransfer) {
                    var model_id = $(this).attr("data-id");
                    var url = "{{ route('leave_request_hierarchies.destroy', ':model_id') }}";

                    $.ajax({
                        url: url.replace(':model_id', model_id),
                        type: 'POST',
                        data: {
                            '_method': "DELETE",
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (!data.error && !data.error2) {
                                swal("Success!", data.success, "success")
                                    .then((action) => {
                                        window.location.reload();
                                    });
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


<!-- Edit -->
<script>
    $("#datatable-tabletools").on("click", ".edit-element", function(e) {
        e.preventDefault();
        $(".edit-element").show();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('leave_request_hierarchies.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();

                if (!data.error) {
                    // Set hidden input and name field
                    $("#editForm input[name='edit_model_id']").val(data.leaveRequestHierarchy.id);


                    // Set class
                    $("#editForm select[name='clas_id']").val(data.leaveRequestHierarchy.clas_id);

                    // Set requester designation
                    $("#editForm select[name='requester_designation_id']").val(data.leaveRequestHierarchy.requester_designation_id);

                    // Set requester department
                    $("#editForm select[name='requester_department_id']").val(data.leaveRequestHierarchy.requester_department_id);

                    // Set approvers (loop for 4 approvers)

                    $(`#editForm select[name='1_approver_department_id']`).val(data.leaveRequestHierarchy.first_approver_department);
                    $(`#editForm select[name='1_approver_designation_id']`).val(data.leaveRequestHierarchy.first_approver_designation);
                    $(`#editForm select[name='2_approver_department_id']`).val(data.leaveRequestHierarchy.second_approver_department);
                    $(`#editForm select[name='2_approver_designation_id']`).val(data.leaveRequestHierarchy.second_approver_designation);
                    $(`#editForm select[name='3_approver_department_id']`).val(data.leaveRequestHierarchy.third_approver_department);
                    $(`#editForm select[name='3_approver_designation_id']`).val(data.leaveRequestHierarchy.third_approver_designation);
                    $(`#editForm select[name='4_approver_department_id']`).val(data.leaveRequestHierarchy.fourth_approver_department);
                    $(`#editForm select[name='4_approver_designation_id']`).val(data.leaveRequestHierarchy.fourth_approver_designation);


                    // $(`#editForm select[name='${departmentField}']`).val(data.leaveRequestHierarchy.departmentField);

                } else {
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
            var url = "{{ route('leave_request_hierarchies.update', ':model_id') }}";
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
                            window.location.href = '{{ route('leave_request_hierarchies.index') }}';
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
