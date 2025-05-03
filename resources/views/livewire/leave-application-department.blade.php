<div>
    <div wire:loading.flex style="position: absolute;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background: rgba(245,245,251,0.6);
        margin-top: -30px;
        margin-left: -30px;
        z-index: 4;
        pointer-events: none;
        font-size: 20px;
        font-weight: 600">Loading...
    </div>

    <div class="row p-1">
        <div class="col-sm-3 col-md-3 me-auto">
            <select name="records_per_page" id="" class="form-control" wire:model="records_per_page" style="max-width: 60px">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-sm-9 col-md-3 ms-auto">
            <input type="text" name="search" wire:model.debounce.500="search" class="form-control" placeholder="search..">
        </div>
    </div>
    <h3>Deparment wise leaves</h3>
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
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = $leaveRequests->perPage() * ($leaveRequests->currentPage() -1 );


                    @endphp
                    @foreach ($leaveRequests as $request)
                    @php
                    $user = $request->user;
                    $elAvailable = $user->userLeaves->where('leave_type_id', 5)->first()?->leave_days ?? 0;
                    $clAvailable = $user->userLeaves->where('leave_type_id', 6)->first()?->leave_days ?? 0;
                    $melAvailable = $user->userLeaves->where('leave_type_id', 7)->first()?->leave_days ?? 0;
                    $elTaken =   $user->leaveRequests->where('leave_type_id', 5)->where('is_approved', 1)->sum('no_of_days');
                    $clTaken = $user->leaveRequests->where('leave_type_id', 6)->where('is_approved', 1)->sum('no_of_days');
                    $melTaken = $user->leaveRequests->where('leave_type_id', 7)->where('is_approved', 1)->sum('no_of_days');
                    @endphp
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $request->user?->emp_code }}</td>
                            <td>{{ $request->user?->name }}</td>
                            <td>{{ $request->user?->department?->name }}</td>
                            <td>{{ $request->user?->ward?->name }}</td>
                            <td>{{ $request->user?->clas?->name }}</td>
                            <td>{{ $request->leaveType ? $request->leaveType->name : 'Half Day' }}</td>
                            <td>{{ $request->from_date }}</td>
                            <td>{{ $request->to_date }}</td>
                            <td>{{ $request->no_of_days }}</td>
                            <td>{{ $elAvailable - $elTaken }}</td>
                            <td>{{ $elTaken }}</td>

                            <td>{{ $clAvailable - $clTaken }}</td>
                            <td>{{ $clTaken }}</td>

                            <td>{{ $melAvailable - $melTaken }}</td>
                            <td>{{ $melTaken }}</td>
                            <td style="font-size: 11px;">

                                @foreach ($request->approvalHierarchy as $hierarchy)

                                    <strong> @if($isAdmin) {{ $loop->iteration  }} Approver </strong> - @endif  {{ $hierarchy->status == 0 ? 'Pending' : 'Approved' }} <br>
                                @endforeach
                            </td>
                            <td>{{ Str::limit($request->remark, 60) }}</td>
                            <td>
                                <a class="btn btn-primary" target="_blank" href="{{asset($request->document->path)}}">View </a>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-between">
            <div>
                Total Leaves Applications: {{ $leaveRequests->total() }}
            </div>
            <div>
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>

</div>
