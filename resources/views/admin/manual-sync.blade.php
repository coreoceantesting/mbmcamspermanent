<x-admin.admin-layout>
    <x-slot name="title">Manual Attendance Sync</x-slot>

    <div class="page-body">
        {{-- <div class="container-fluid">
            <div class="page-header py-3">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Manual Attendance Sync</h3>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row card">
                <div class="col-sm-12 col-xl-10 mx-auto">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="">
                                <div class="card-header pb-0">
                                    <h3>Manual Attendance Sync</h3>
                                </div>
                                <form class="theme-form" id="addForm">
                                    @csrf
                                    <div class="card-body">

                                        <div class="mb-3 row">
                                            <div class="col-md-4">
                                                <label class="col-form-label" for="emp_code">Enter Emp Code <span class="text-danger">*</span></label>
                                                <input class="form-control" id="emp_code" name="emp_code" type="text" placeholder="Please enter emp code">
                                                <span class="text-danger error-text emp_code_err"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="col-form-label" for="from_date">Select From Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" id="from_date" name="from_date" onclick="this.showPicker()" type="date" placeholder="dd/mm/yyyy">
                                                <span class="text-danger error-text from_date_err"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="col-form-label" for="to_date">Select To Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" id="to_date" name="to_date" onclick="this.showPicker()" type="date" placeholder="dd/mm/yyyy">
                                                <span class="text-danger error-text to_date_err"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary" id="addSubmit">Submit</button>
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row card">
                <div class="col-sm-12 col-xl-10 mx-auto">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="">
                                <div class="card-header pb-0">
                                    <h3>Check Attendance Sync Status</h3>
                                </div>
                                <form class="theme-form" id="editForm">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="alert " id="alert" role="alert"></div>
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="col-md-4">
                                                <label class="col-form-label" for="emp_code">Enter Emp Code <span class="text-danger">*</span></label>
                                                <input class="form-control" name="emp_code" type="text" placeholder="Please enter emp code">
                                                <span class="text-danger error-text emp_code_err"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary" id="editSubmit">Submit</button>
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                    </div>
                                </form>
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
            url: '{{ route('manual-sync.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.reload();
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



{{-- Check Sync Status --}}
<script>
    $("#editForm").submit(function(e) {
        e.preventDefault();
        $("#editSubmit").prop('disabled', true);
        var formdata = new FormData(this);

        $.ajax({
            url: '{{ route('check-sync-status') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#editSubmit").prop('disabled', false);
                $('#alert').addClass('alert-'+data.message_type);
                $('#alert').text(data.success);
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
