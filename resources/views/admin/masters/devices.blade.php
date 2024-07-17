<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Devices</x-slot>

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
                                    <h4>Create Device</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="ward_id">Select Office <span class="text-danger">*</span> </label>
                                            <select class="js-example-basic-single col-sm-12" name="ward_id">
                                                <option value="">--Select Office--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DeviceFName">Device Full Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="DeviceFName" type="text" placeholder="Enter Device Full Name">
                                            <span class="text-danger error-text DeviceFName_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DevicesName">Device Short Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="DevicesName" type="text" placeholder="Enter Device Short Name">
                                            <span class="text-danger error-text DevicesName_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="SerialNumber">Serial Number <span class="text-danger">*</span></label>
                                            <input class="form-control" name="SerialNumber" type="text" placeholder="Enter Device Serial Number">
                                            <span class="text-danger error-text SerialNumber_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="IpAddress">Ip Address <span class="text-danger">*</span></label>
                                            <input class="form-control" name="IpAddress" type="text"  minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" title="Please enter valid Ip Address like : 255.255.255.255" placeholder="Enter Device Ip Address">
                                            <span class="text-danger error-text IpAddress_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DeviceLocation">Device Location <span class="text-danger">*</span></label>
                                            <input class="form-control" name="DeviceLocation" type="text" placeholder="Enter Device Location">
                                            <span class="text-danger error-text DeviceLocation_err"></span>
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
                                    <h4 class="card-title">Edit Device</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">

                                        <div class="col-md-4">
                                            <label class="col-form-label" for="ward_id">Select Office <span class="text-danger">*</span> </label>
                                            <select class="js-example-basic-single col-sm-12" name="ward_id">
                                                <option value="">--Select Office--</option>
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DeviceFName">Device Full Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="DeviceFName" type="text" placeholder="Enter Device Full Name">
                                            <span class="text-danger error-text DeviceFName_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DevicesName">Device Short Name <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="DevicesName" type="text" placeholder="Enter Device Short Name">
                                            <span class="text-danger error-text DevicesName_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="SerialNumber">Serial Number <span class="text-danger">*</span></label>
                                            <input class="form-control" name="SerialNumber" type="text" placeholder="Enter Device Serial Number">
                                            <span class="text-danger error-text SerialNumber_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="IpAddress">Ip Address <span class="text-danger">*</span></label>
                                            <input class="form-control" name="IpAddress" type="text"  minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" title="Please enter valid Ip Address like : 255.255.255.255" placeholder="Enter Device Ip Address">
                                            <span class="text-danger error-text IpAddress_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="DeviceLocation">Device Location <span class="text-danger">*</span></label>
                                            <input class="form-control" name="DeviceLocation" type="text" placeholder="Enter Device Location">
                                            <span class="text-danger error-text DeviceLocation_err"></span>
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
                        <h3>Devices</h3>
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
                                            <th>Device Full Name</th>
                                            <th>Device Short name</th>
                                            <th>Serial Number</th>
                                            <th>Ip Address</th>
                                            <th>Last Ping Date</th>
                                            <th>Device Location</th>
                                            <th>Office</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devices as $device)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $device->DeviceFName }}</td>
                                                <td>{{ $device->DevicesName }}</td>
                                                <td>{{ $device->SerialNumber }}</td>
                                                <td>{{ $device->IpAddress }}</td>
                                                <td>{{ $device->LastPing }}</td>
                                                <td>{{ $device->DeviceLocation }}</td>
                                                <td>{{ $device->wardDevice?->ward?->name }}</td>
                                                <td>
                                                    <button class="edit-element btn btn-primary px-2 py-1" title="Edit Device" data-id="{{ $device->DeviceId }}"><i data-feather="edit"></i></button>
                                                    {{-- <button class="btn btn-primary change-password px-2 py-1" title="Change Password" data-id="{{ $emp->id }}"><i data-feather="lock"></i></button> --}}
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



</x-admin.admin-layout>


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('devices.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('devices.index') }}';
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
    $("#datatable-tabletools").on("click", ".edit-element", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('devices.edit', ':model_id') }}";

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
                    $("#editForm input[name='edit_model_id']").val(data.device.DeviceId);
                    $("#editForm input[name='DeviceFName']").val(data.device.DeviceFName);
                    $("#editForm input[name='DevicesName']").val(data.device.DevicesName);
                    $("#editForm input[name='SerialNumber']").val(data.device.SerialNumber);
                    $("#editForm input[name='IpAddress']").val(data.device.IpAddress);
                    $("#editForm input[name='DeviceLocation']").val(data.device.DeviceLocation);
                    $("#editForm select[name='ward_id']").html(data.wardHtml);
                }
                else
                {
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
            var url = "{{ route('devices.update', ':model_id') }}";
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
                            window.location.href = '{{ route('devices.index') }}';
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


<!-- Show Details -->
<script>
    $("#datatable-tabletools").on("click", ".emp-more-info", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('devices.show', ':model_id') }}";

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
