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
                        <th style="min-width: 150px">Remark</th>
                        <th>View Document</th>
                        <th style="min-width: 150px" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = $leaveRequests->perPage() * ($leaveRequests->currentPage() -1 );
                    @endphp
                    @foreach ($leaveRequests as $request)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $request->user->emp_code }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->department?->name }}</td>
                            <td>{{ $request->user->ward?->name }}</td>
                            <td>{{ $request->user->clas?->name }}</td>
                            <td>{{ $request->leaveType ? $request->leaveType->name : 'Half Day' }}</td>
                            <td>{{ $request->from_date }}</td>
                            <td>{{ $request->to_date }}</td>
                            <td>{{ $request->no_of_days }}</td>
                            <td>{{ Str::limit($request->remark, 60) }}</td>
                            <td>
                                <a class="btn btn-primary" target="_blank" href="{{asset($request->document->path)}}">View </a>
                            </td>
                            <td>
                                {{-- @if($type_const == 'pending') --}}
                                    @if ($request->is_approved == 0)
                                        <button class="approve-request btn btn-success px-2 py-1" title="Approve Request" data-id="{{ $request->id }}" data-status="1">Approve <i data-feather="check-circle"></i></button>
                                        <button class="change-request btn btn-warning px-2 py-1" title="Reject Request" data-id="{{ $request->id }}" data-status="2">Reject <i data-feather="x-circle"></i></button>
                                    @elseif ($request->is_approved == 1)
                                        <button class="btn btn-success px-2 py-1">Approved</button>
                                    @else
                                        <button class="btn btn-danger px-2 py-1">Rejected</button>
                                    @endif
                                {{-- @endif --}}
                                @if($isAdmin)
                                    <button class="btn btn-danger rem-element px-2 py-1" title="Delete Leave" data-id="{{ $request->id }}"><i class="fa fa-trash"></i></button>
                                @endif
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
