<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Balance Leaves</x-slot>

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
                                    <h4>Create Leave</h4>
                                </div>
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        {{-- <input name="page_type" value="{{ $pageType }}" type="hidden"> --}}

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Code<span class="text-danger">*</span></label>
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

                                        <input type="hidden" name="page_type" value="full_day">
                                        <input type="hidden" value="7" name="leave_type_id">
                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="leave_type_id" readonly>
                                                <option value="">--Select Leave Type--</option>
                                                <option value="7" selected>Medical Leave</option>
                                            </select>
                                        </div> --}}

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

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Choose File <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="file" type="file" accept="application/pdf, image/png, image/jpeg,  image/jpg" placeholder="Choose File" >
                                            <span class="text-danger error-text file_err"></span>
                                        </div>

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
                                    <h4 class="card-title">Edit Medical Leave</h4>
                                </header>

                                <div class="card-body py-2">

                                    <input type="hidden" id="edit_model_id" name="edit_model_id" value="">

                                    <div class="mb-3 row">
                                        {{-- <input name="page_type" value="{{ $pageType }}" type="hidden"> --}}

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="emp_code">Enter Employee Code<span class="text-danger">*</span></label>
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
                                        <div class="col-12">
                                            <div class="text-success error-text px-4 py-1 mt-3 mb-2" style="font-size:11px; background-color: #19875436; font-weight: 700; border-radius: 5px;">Medical leave will reflect under reports within 1 hour after application submitted.</div>
                                        </div>

                                        <input type="hidden" name="page_type" value="full_day">
                                        <input type="hidden" name="leave_type_id" value="7">
                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="leave_type_id">Select Leave Type <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="leave_type_id">
                                                <option value="">--Select Leave Type--</option>
                                            </select>
                                            <span class="text-danger error-text leave_type_id_err"></span>
                                        </div> --}}

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

                                        <div class="col-md-3 mt-3" id="edit_img"></div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="file">Choose File<span class="text-danger">*</span> </label>
                                            <input class="form-control" name="file" type="file" accept="application/pdf, image/png, image/jpeg,  image/jpg" placeholder="Choose File" >
                                            <span class="text-danger error-text file_err"></span>
                                            <span class="text-danger error-text" style="font-size:11px">Choose if want to replace existing file</span>
                                        </div>

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
                        <h3>Balance Leaves</h3>
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
                            {{-- @if ($pageType == 'active') --}}
                                {{-- <div class="row">
                                    <div class="col-sm-6">
                                        <div class="">
                                            <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                            <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                        </div>
                                    </div>
                                </div> --}}
                            {{-- @endif --}}
                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Emp Code</th>
                                            <th>Emp Name</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Class</th>
                                            <th>EL Available</th>
                                            <th>EL Taken</th>
                                            <th>CL Available</th>
                                            <th>CL Taken</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveRequests as $request)
                                        @php

                                        $elAvailable = $request->userLeaves->where('leave_type_id', 5)->first()?->leave_days ?? 0;
                                        $clAvailable = $request->userLeaves->where('leave_type_id', 6)->first()?->leave_days ?? 0;
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->emp_code }}</td>
                                                <td>{{ $request->name }}</td>
                                                <td>{{ $request->department?->name }}</td>
                                                <td>{{ $request->designation?->name }}</td>
                                                <td>{{ $request->clas?->name }}</td>
                                                <td>{{ $elAvailable - $request->leaveRequests->where('leave_type_id', 5)->where('is_approved', 1)->sum('no_of_days') }}</td>
                                                <td>{{ $request->leaveRequests->where('leave_type_id', 5)->where('is_approved', 1)->sum('no_of_days') }}</td>
                                                <td>{{ $clAvailable - $request->leaveRequests->where('leave_type_id', 6)->where('is_approved', 1)->sum('no_of_days') }}</td>
                                                <td>{{ $request->leaveRequests->where('leave_type_id', 6)->where('is_approved', 1)->sum('no_of_days') }}</td>
                                                <td>
                                                    {{-- @if ($isAdmin)
                                                        <button class="rem-element btn btn-danger px-2 py-1" title="Delete Leave Request" data-id="{{ $request->id }}"><i data-feather="trash"></i></button>
                                                    @endif
                                                    @if ($request->is_approved == 0)
                                                        <button class="edit-element btn btn-primary px-2 py-1" title="Edit Leave Request" data-id="{{ $request->id }}"><i data-feather="edit"></i></button>
                                                        @if (!Auth::user()->hasRole('Maker'))
                                                            <button class="approve-request btn btn-success px-2 py-1" title="Approve Request" data-id="{{ $request->id }}" data-status="1"><i data-feather="check-circle"></i></button>
                                                            <button class="change-request btn btn-warning px-2 py-1" title="Reject Request" data-id="{{ $request->id }}" data-status="2"><i data-feather="x-circle"></i></button>
                                                        @endif
                                                    @elseif ($request->is_approved == 1)
                                                        <button class="btn btn-success px-2 py-1">Approved</button>
                                                    @else
                                                        <button class="btn btn-danger px-2 py-1">Rejected</button>
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





</x-admin.admin-layout>


