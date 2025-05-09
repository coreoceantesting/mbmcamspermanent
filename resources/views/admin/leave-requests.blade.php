<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - {{ ucwords(str_replace('_', ' ', $pageType)) }}</x-slot>

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
                                    <h4>Create {{ ucwords(str_replace('_', ' ', $pageType)) }} {{ $pageType == 'outpost' ? '' : 'Leave' }}</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">
                                        <input name="page_type" value="{{ $pageType }}" type="hidden">

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

                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="designation">Department <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="department" type="text" placeholder="Department" readonly>
                                            <span class="text-danger error-text department_err"></span>
                                        </div> --}}

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="class" type="text" placeholder="Class" readonly>
                                            <span class="text-danger error-text class_err"></span>
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-start">
                                        {{-- <div class="col-12">
                                            <div class="text-success error-text px-4 py-1 mt-3 mb-2" style="font-size:11px; background-color: #19875436; font-weight: 700; border-radius: 5px;">If requesting leave for 1 day, then select FROM DATE & TO DATE same</div>
                                        </div> --}}


                                        @if ( $pageType == 'half_day' )
                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="date">Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="date" type="date" onclick="this.showPicker()" placeholder="Date" >
                                                <span class="text-danger error-text date_err"></span>
                                            </div>
                                        @else

                                            @if ( $pageType == 'outpost' )
                                                <input type="hidden" name="leave_type_id" value="2">
                                            @else
                                                <div class="col-md-3 mt-3">
                                                    <label class="col-form-label" for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                                    <select class="js-example-basic-single col-sm-12" name="leave_type_id">
                                                        <option value="">--Select Leave Type--</option>
                                                        @foreach ($leaveTypes as $leaveType)
                                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error-text leave_type_id_err"></span>
                                                </div>
                                            @endif

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="from_date" type="date" onclick="this.showPicker()" placeholder="From Date" >
                                                <span class="text-danger error-text from_date_err"></span>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="to_date" type="date" onclick="this.showPicker()" placeholder="To Date" >
                                                <span class="text-danger error-text to_date_err"></span>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="no_of_days">No of Days <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="no_of_days" type="text" placeholder="No of Days"  readonly>
                                                <span class="text-danger error-text no_of_days_err"></span>
                                            </div>
                                        @endif

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Choose File </label>
                                            <input class="form-control" name="file" type="file" accept="application/pdf, image/png, image/jpeg,  image/jpg" placeholder="Choose File" >
                                            <span class="text-danger error-text file_err"></span>
                                        </div>

                                        @if ( $pageType == 'half_day' )
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Select Half Day Type<span class="text-danger">*</span> </label>
                                            <select name="half_day_type" id="half_day_type" class="form-select">
                                                <option value="">Select Type</option>
                                                <option value="1">1st Half</option>
                                                <option value="2">2nd Half</option>
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="remark">Remark <span class="text-danger">*</span> </label>
                                            <textarea class="form-control" name="remark" style="min-height: 60px; max-height:60px"></textarea>
                                            <span class="text-danger error-text remark_err"></span>
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
                                    <h4 class="card-title">Edit {{ ucwords(str_replace('_', ' ', $pageType)) }} {{ $pageType == 'outpost' ? '' : 'Leave' }}</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">
                                        <input name="page_type" value="{{ $pageType }}" type="hidden">

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Id<span class="text-danger">*</span></label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code" readonly>
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
                                            <input class="form-control" name="ward" type="text" placeholder="Ward" readonly>
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

                                    <div class="mb-3 row align-items-start">
                                        {{-- <div class="col-12">
                                            <div class="text-success error-text px-4 py-1 mt-3 mb-2" style="font-size:11px; background-color: #19875436; font-weight: 700; border-radius: 5px;">If requesting leave for 1 day, then select FROM DATE & TO DATE same</div>
                                        </div> --}}

                                        @if ( $pageType == 'half_day' )
                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="date">Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="date" type="date" onclick="this.showPicker()" placeholder="Date" >
                                                <span class="text-danger error-text date_err"></span>
                                            </div>
                                        @else

                                            @if ( $pageType == 'outpost' )
                                                <input type="hidden" name="leave_type_id" value="2">
                                            @else
                                                <div class="col-md-3 mt-3">
                                                    <label class="col-form-label" for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                                    <select class="js-example-basic-single col-sm-12" name="leave_type_id">
                                                        <option value="">--Select Leave Type--</option>
                                                    </select>
                                                    <span class="text-danger error-text leave_type_id_err"></span>
                                                </div>
                                            @endif

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="from_date" type="date" onclick="this.showPicker()" placeholder="From Date" >
                                                <span class="text-danger error-text from_date_err"></span>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="to_date" type="date" onclick="this.showPicker()" placeholder="To Date" >
                                                <span class="text-danger error-text to_date_err"></span>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <label class="col-form-label" for="no_of_days">No of Days <span class="text-danger">*</span> </label>
                                                <input class="form-control" name="no_of_days" type="text" placeholder="No of Days" readonly>
                                                <span class="text-danger error-text no_of_days_err"></span>
                                            </div>
                                        @endif

                                        <div class="col-md-3 mt-3" id="edit_img"></div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Choose File </label>
                                            <input class="form-control" name="file" type="file" accept="application/pdf, image/png, image/jpeg,  image/jpg" placeholder="Choose File" >
                                            <span class="text-danger error-text file_err"></span>
                                            <span class="text-danger error-text" style="font-size:11px">Choose if want to replace existing file</span>
                                        </div>

                                        @if ( $pageType == 'half_day' )
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Select Half Day Type<span class="text-danger">*</span> </label>
                                            <select name="half_day_type" id="half_day_type" class="form-select">
                                                <option value="">Select Type</option>
                                                <option value="1">1st Half</option>
                                                <option value="2">2nd Half</option>
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="remark">Remark <span class="text-danger">*</span> </label>
                                            <textarea class="form-control" name="remark" style="min-height: 60px; max-height:60px"></textarea>
                                            <span class="text-danger error-text remark_err"></span>
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        <button id="addToTable" class="btn btn-primary">{{ ucwords(str_replace('_', ' ', $pageType)) }} <i class="fa fa-plus"></i></button>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Id</th>
                                            <th style="min-width: 120px">Emp Name</th>
                                            <th style="min-width: 120px">Department</th>
                                            <th style="min-width: 120px">Office</th>
                                            <th style="min-width: 120px">Class</th>
                                            @if ($pageType == 'full_day')
                                                <th style="min-width: 120px">Leave Type</th>
                                            @endif
                                            <th style="min-width: 80px">{{ $pageType == 'half_day' ? 'Date' : 'From Date' }}</th>
                                            @if( $pageType != 'half_day' )
                                                <th style="min-width: 80px">To Date</th>
                                                <th>No. Of Days</th>
                                            @endif
                                            @if( $pageType == 'half_day' )
                                                <th>Half Day Type</th>
                                            @endif
                                            <th>Approval Status</th>
                                            <th style="min-width: 150px">Remark</th>
                                            <th>View Documents</th>
                                            <th style="min-width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveRequests as $request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->user->emp_code }}</td>
                                                <td>{{ $request->user->name }}</td>
                                                <td>{{ $request->user->department?->name }}</td>
                                                <td>{{ $request->user->ward?->name }}</td>
                                                <td>{{ $request->user->clas?->name }}</td>
                                                @if ($pageType == 'full_day')
                                                    <td>{{ $request->leaveType->name }}</td>
                                                @endif
                                                <td>{{ $request->from_date }}</td>
                                                @if( $pageType != 'half_day' )
                                                    <td>{{ $request->to_date }}</td>
                                                    <td>{{ $request->no_of_days }}</td>
                                                @endif
                                                @if( $pageType == 'half_day' )
                                                    <td>{{ ['1' => 'First Half', '2' => 'Second Half'][$request->half_day_type] ?? '-' }}</td>
                                                @endif
                                                <td>
                                                    @foreach ($request->approvalHierarchy as $hierarchy)
                                                        <strong>{{ $loop->iteration }} Approver</strong> - {{ $hierarchy->status == 0 ? 'Pending' : 'Approved' }} <br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $request->remark }}</td>
                                                <td>
                                                    <a class="btn btn-primary" target="_blank" href="{{asset($request->document->path)}}">View File</a>
                                                </td>
                                                <td>
                                                    @if ($request->is_approved == 0)
                                                        <button class="edit-element btn btn-primary px-2 py-1" title="Edit Leave Request" data-id="{{ $request->id }}"><i data-feather="edit"></i></button>
                                                        <button class="rem-element btn btn-danger px-2 py-1" title="Delete Leave Request" data-id="{{ $request->id }}"><i data-feather="trash"></i></button>
                                                    @endif
                                                    {{-- @if ($request->is_approved == 0)
                                                        <button class="approve-request btn btn-success px-2 py-1" title="Approve Request" data-id="{{ $request->id }}" data-status="1"><i data-feather="check-circle"></i></button>
                                                        <button class="change-request btn btn-warning px-2 py-1" title="Reject Request" data-id="{{ $request->id }}" data-status="2"><i data-feather="x-circle"></i></button>
                                                    @endif --}}
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

                    let errorMessage = "Something went wrong, please try again";
                    if (responseObject.responseJSON && responseObject.responseJSON.error) {
                        errorMessage = responseObject.responseJSON.error;
                    }

                    swal("Error occurred!", errorMessage, "error");
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
                        $("#editForm select[name='half_day_type']").val(data.leave_request.half_day_type);
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
    $("#datatable-tabletools").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
                title: "Are you sure to delete this Leave request?",
                // text: "Make sure if you have filled Vendor details before proceeding further",
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
    $("#datatable-tabletools").on("click", ".approve-request", function(e) {
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
    $("#datatable-tabletools").on("click", ".change-request", function(e) {
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
        // if($("#addForm select[name='leave_type_id']").val() == 7)
        // {
        //     // $("#addForm input[name='to_date']").closest(".col-md-3").addClass('d-none');
        //     $("#addForm input[name='no_of_days']").closest(".col-md-3").addClass('d-none');
        //     $("#addForm input[name='file']").closest(".col-md-3").addClass('d-none');
        // }
        // else
        // {
        //     $("#addForm input[name='to_date']").closest(".col-md-3").removeClass('d-none');
        //     $("#addForm input[name='no_of_days']").closest(".col-md-3").removeClass('d-none');
        //     $("#addForm input[name='file']").closest(".col-md-3").removeClass('d-none');
        // }
    });
    $("#editForm select[name='leave_type_id']").change(function() {
        // if($("#editForm select[name='leave_type_id']").val() == 7)
        // {
        //     $("#editForm input[name='to_date']").closest(".col-md-3").addClass('d-none');
        //     $("#editForm input[name='no_of_days']").closest(".col-md-3").addClass('d-none');
        //     $("#editForm input[name='file']").closest(".col-md-3").addClass('d-none');
        // }
        // else
        // {
        //     $("#editForm input[name='to_date']").closest(".col-md-3").removeClass('d-none');
        //     $("#editForm input[name='no_of_days']").closest(".col-md-3").removeClass('d-none');
        //     $("#editForm input[name='file']").closest(".col-md-3").removeClass('d-none');
        // }
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
