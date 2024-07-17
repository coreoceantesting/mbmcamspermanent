<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - {{ ucwords(str_replace('_', ' ', $pageType)) }}</x-slot>
    <livewire:styles />

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-6">
                        <h3>{{ ucwords(str_replace('_', ' ', $pageType)) }}</h3>
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

                            <div>
                                <livewire:leave-application />
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>



    {{-- Reject Leave with reason modal --}}
    <div class="modal fade" id="reject-leave-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" id="rejectLeaveForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejection Reason</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="leave_request_id" value="">

                        <div class="col-12 mx-auto my-2">
                            <div class="form-group">
                                <label>Reason</label>
                                <div class="form-group">
                                    <textarea name="reason" class="form-control" id="reason" cols="15" rows="8" style="min-height: 140px; max-height: 140px"></textarea>
                                </div>
                                <span class="text-danger error-text reason_err"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="rejectLeaveSubmit" type="submit">Reject Leave</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <livewire:scripts />
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


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);
        var page_type = $("input[name='page_type']").val();
        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('leave-requests.store') }}',
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
        var url = "{{ route('leave-requests.edit', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR) {
                editFormBehaviour();
                if (!data.error) {
                    $("#editForm input[name='edit_model_id']").val(data.leave_request.id);
                    $("#editForm input[name='emp_code']").val(data.leave_request.user.emp_code);
                    $("#editForm input[name='name']").val(data.leave_request.user.name);
                    $("#editForm input[name='ward']").val(data.leave_request.user.ward.name);
                    $("#editForm input[name='department']").val(data.leave_request.user.department.name);
                    $("#editForm input[name='class']").val(data.leave_request.user.clas.name);

                    if( $("#editForm input[name='page_type']").val() != 'half_day' )
                    {
                        if( $("#editForm input[name='page_type']").val() == 'outpost' )
                        {
                            $("#editForm input[name='leave_type_id']").val('2');
                            $("#editForm select[name='leave_type_id']").html(data.leaveTypeHtml);
                        }
                        else
                        {
                            $("#editForm select[name='leave_type_id']").html(data.leaveTypeHtml);
                        }
                        $("#editForm input[name='from_date']").val(data.leave_request.from_date);
                        $("#editForm input[name='to_date']").val(data.leave_request.to_date);
                    }
                    else
                    {
                        $("#editForm input[name='date']").val(data.leave_request.from_date);
                    }
                        $("#editForm input[name='no_of_days']").val(data.leave_request.no_of_days);
                        $("#edit_img").html(data.fileHtml);
                        $("#editForm textarea[name='remark']").text(data.leave_request.remark);
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
            var url = "{{ route('leave-requests.update', ':model_id') }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    // console.log(data);
                    $("#editSubmit").prop('disabled', false);
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


<!-- Delete -->
<script>
    $("#list_table").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
                title: "Are you sure to delete this Leave application?",
                text: "If 'Yes' then press OK and wait unitll confirmation, it may take some seconds",
                icon: "warning",
                buttons: ["Cancel", "Confirm"]
            })
            .then((justTransfer) => {
                if (justTransfer) {
                    var model_id = $(this).attr("data-id");
                    var url = "{{ route('leave-requests.destroy', ':model_id') }}";

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


<!-- Approve Request -->
<script>
    $("#list_table").on("click", ".approve-request", function(e) {
        e.preventDefault();
        var status = $(this).attr("data-status");
        swal({
                title: "Are you sure to "+ (status == 1 ? "approve" : "reject") +" this Leave request?",
                icon: "info",
                buttons: ["Cancel", "Confirm"]
            })
            .then((proceed) => {
                if (proceed) {
                    var model_id = $(this).attr("data-id");
                    var url = "{{ route('leave-requests.change-request', ':model_id') }}";

                    $.ajax({
                        url: url.replace(':model_id', model_id),
                        type: 'POST',
                        data: {
                            '_method': "PUT",
                            'status': status,
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


<!-- Reject Request -->
<script>
    $("#list_table").on("click", ".change-request", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        $("input[name='leave_request_id']").val(model_id);
        $('#reject-leave-modal').modal('show');
    });
</script>


{{-- Calculate Number of Days  --}}
<script>
    $("#addForm input[name='to_date'], #addForm input[name='from_date']").focusout(function() {
        var from_date_obj = new Date( $("#addForm input[name='from_date']").val() );
        var to_date_obj = new Date( $("#addForm input[name='to_date']").val() );
        var difference_in_days = Math.ceil((to_date_obj - from_date_obj) / (1000 * 3600 * 24))+1;

        $("#addForm input[name='no_of_days']").val(difference_in_days);
    });

    $("#editForm input[name='to_date'], editForm input[name='from_date']").focusout(function() {
        var from_date_obj = new Date( $("#editForm input[name='from_date']").val() );
        var to_date_obj = new Date( $("#editForm input[name='to_date']").val() );
        var difference_in_days = Math.ceil((to_date_obj - from_date_obj) / (1000 * 3600 * 24))+1;

        $("#editForm input[name='no_of_days']").val(difference_in_days);
    });

    // Show n hide fields based on medical leave dropdown
    $("#addForm select[name='leave_type_id']").change(function() {
        if($("#addForm select[name='leave_type_id']").val() == 7)
        {
            $("#addForm input[name='to_date']").closest(".col-md-3").addClass('d-none');
            $("#addForm input[name='no_of_days']").closest(".col-md-3").addClass('d-none');
            $("#addForm input[name='file']").closest(".col-md-3").addClass('d-none');
        }
        else
        {
            $("#addForm input[name='to_date']").closest(".col-md-3").removeClass('d-none');
            $("#addForm input[name='no_of_days']").closest(".col-md-3").removeClass('d-none');
            $("#addForm input[name='file']").closest(".col-md-3").removeClass('d-none');
        }
    });
    $("#editForm select[name='leave_type_id']").change(function() {
        if($("#editForm select[name='leave_type_id']").val() == 7)
        {
            $("#editForm input[name='to_date']").closest(".col-md-3").addClass('d-none');
            $("#editForm input[name='no_of_days']").closest(".col-md-3").addClass('d-none');
            $("#editForm input[name='file']").closest(".col-md-3").addClass('d-none');
        }
        else
        {
            $("#editForm input[name='to_date']").closest(".col-md-3").removeClass('d-none');
            $("#editForm input[name='no_of_days']").closest(".col-md-3").removeClass('d-none');
            $("#editForm input[name='file']").closest(".col-md-3").removeClass('d-none');
        }
    });
</script>


{{-- Submit leave rejection form --}}
<script>
    $("#rejectLeaveForm").submit(function(e) {
        e.preventDefault();
        $("#rejectLeaveSubmit").prop('disabled', true);
        var model_id = $("input[name='leave_request_id']").val();
        var url = "{{ route('leave-requests.change-request', ':model_id') }}";
        var status = 2;
        var formdata = new FormData(this);
        formdata.append('status', status);
        formdata.append('_method', 'PUT');

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#rejectLeaveSubmit").prop('disabled', false);
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
                    $("#rejectLeaveSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#rejectLeaveSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('rejectLeaveForm');
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
