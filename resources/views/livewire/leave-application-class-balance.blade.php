<div>
    <!-- Loading Indicator -->
    <div wire:loading.flex style="position: absolute; width: 100%; height: 100%; justify-content: center; align-items: center; background: rgba(245,245,251,0.6); margin-top: -30px; margin-left: -30px; z-index: 4; pointer-events: none; font-size: 20px; font-weight: 600">
        Loading...
    </div>

    <!-- Records per page and search -->
    <div class="row p-1">
        <div class="col-sm-3 col-md-3 me-auto">
            <select name="records_per_page" class="form-control" wire:model="records_per_page" style="max-width: 100px">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-sm-9 col-md-3 ms-auto">
            <input type="text" name="search" wire:model.debounce.500="search" class="form-control" placeholder="Search by Emp Code or Name...">
        </div>
    </div>

    <h3 class="mt-3">Balance Leave of Class 1 & Class 2</h3>

    <!-- Table with users' leave requests -->
    <div class="row" style="overflow-x: scroll">
        <div class="col-12">
            <table class="table table-hover" id="list_table">
               <thead>
                    <tr>
                        <th style="min-width: 100px" > <span class="custom_th">Sr No. </span> <span class="arrow"></span> </th>
                        <th style="min-width: 110px" >Emp Code</th>
                        <th style="min-width: 120px" >Emp Name</th>
                        <th style="min-width: 120px" >Department</th>
                        <th style="min-width: 120px" >Office</th>
                        <th style="min-width: 120px" >Class</th>
                        <th style="min-width: 120px" >Leave Type</th>
                        <th style="min-width: 120px" >From Date</th>
                        <th style="min-width: 120px" >To Date</th>
                        <th>Days</th>
                        <th>EL Available</th>
                        <th>EL Taken</th>
                        <th>CL Available</th>
                        <th>CL Taken</th>
                        <th>MEL Available</th>
                        <th>MEL Taken</th>
                        <th>Approval Status</th>
                        <th style="min-width: 150px">Remark</th>
                        <th>View Document</th>
                        <th style="min-width: 150px" >Action</th>
                    </tr>
                </thead>
              <tbody>
    @php $i = 0; @endphp
    @foreach ($users as $user)
        @php
            // Available leave days per type
            $elAvailable = $user->userLeaves->where('leave_type_id', 5)->first()?->leave_days ?? 0;
            $clAvailable = $user->userLeaves->where('leave_type_id', 6)->first()?->leave_days ?? 0;
            $melAvailable = $user->userLeaves->where('leave_type_id', 7)->first()?->leave_days ?? 0;

            // Leave taken (approved only)
            $elTaken = $user->leaveRequests->where('leave_type_id', 5)->where('is_approved', 1)->sum('no_of_days');
            $clTaken = $user->leaveRequests->where('leave_type_id', 6)->where('is_approved', 1)->sum('no_of_days');
            $melTaken = $user->leaveRequests->where('leave_type_id', 7)->where('is_approved', 1)->sum('no_of_days');
        @endphp

        @if ($user->leaveRequests->count())
            @foreach ($user->leaveRequests as $request)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->emp_code }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->department?->name }}</td>
                    <td>{{ $user->ward?->name }}</td>
                    <td>{{ $user->clas?->name }}</td>
                    <td>{{ $request->leaveType?->name ?? 'Half Day' }}</td>
                    <td>{{ $request->from_date }}</td>
                    <td>{{ $request->to_date }}</td>
                    <td>{{ $request->no_of_days }}</td>

                    <!-- EL -->
                    <td>{{ $elAvailable - $elTaken }}</td>
                    <td>{{ $elTaken }}</td>

                    <!-- CL -->
                    <td>{{ $clAvailable - $clTaken }}</td>
                    <td>{{ $clTaken }}</td>

                    <!-- MEL -->
                    <td>{{ $melAvailable - $melTaken }}</td>
                    <td>{{ $melTaken }}</td>

                    <!-- Approval Status -->
                    <td style="font-size: 11px;">
                        @foreach ($request->approvalHierarchy as $hierarchy)
                            <strong>
                               
                                    {{ $loop->iteration }} Approver
                               
                            </strong>
                            - {{ $hierarchy->status == 0 ? 'Pending' : 'Approved' }}<br>
                        @endforeach
                    </td>

                    <td>{{ Str::limit($request->remark, 60) }}</td>

                    <!-- Document -->
                    <td>
                        @if ($request->document)
                            <a class="btn btn-primary btn-sm" target="_blank" href="{{ asset($request->document->path) }}">View</a>
                        @else
                            N/A
                        @endif
                    </td>

                    <!-- Actions -->
                    <td>
                        @if ($request->is_approved == 0)
                            <button class="btn btn-warning btn-sm">Pending</button>

                        @elseif ($request->is_approved == 1)
                            <button class="btn btn-success btn-sm">Approved</button>
                        @else
                            <button class="btn btn-danger btn-sm">Rejected</button>
                        @endif

                        @if ($isAdmin)
                            <button class="btn btn-danger btn-sm rem-element" title="Delete Leave" data-id="{{ $request->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <!-- No leave requests -->
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->emp_code }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->department?->name }}</td>
                <td>{{ $user->ward?->name }}</td>
                <td>{{ $user->clas?->name }}</td>
                <td colspan="3">No leave requests</td>
                <td>0</td>

                <!-- EL -->
                <td>{{ $elAvailable }}</td>
                <td>0</td>

                <!-- CL -->
                <td>{{ $clAvailable }}</td>
                <td>0</td>

                <!-- MEL -->
                <td>{{ $melAvailable }}</td>
                <td>0</td>

                <td colspan="4">-</td>
            </tr>
        @endif
    @endforeach
</tbody>

            </table>
            
        </div>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="d-flex justify-content-between">
            <!-- <div>
                <strong>Total Employee:</strong> {{ $users->total() }}
            </div> -->
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
