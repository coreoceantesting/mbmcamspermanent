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

    <h3 class="mt-3">Department-wise Leave Applications</h3>

    <!-- Table with users' leave balances -->
    <div class="row" style="overflow-x: scroll">
        <div class="col-12">
            <table class="table table-hover" id="list_table">
                <thead>
                    <tr>
                        <th style="min-width: 50px">Sr No.</th>
                        <th style="min-width: 100px">Emp Code</th>
                        <th style="min-width: 150px">Emp Name</th>
                        <th style="min-width: 120px">Department</th>
                        <th>EL Available</th>
                        <th>EL Taken</th>
                        <th>CL Available</th>
                        <th>CL Taken</th>
                        <th>MEL Available</th>
                        <th>MEL Taken</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($users as $user)
                        @php
                            // Calculate leave balances for this user
                            $elAvailable = $user->userLeaves->where('leave_type_id', 5)->first()?->leave_days ?? 0;
                            $clAvailable = $user->userLeaves->where('leave_type_id', 6)->first()?->leave_days ?? 0;
                            $melAvailable = $user->userLeaves->where('leave_type_id', 7)->first()?->leave_days ?? 0;

                            // Calculate taken leave days for this leave type
                            $elTaken = $user->leaveRequests->where('leave_type_id', 5)->where('is_approved', 1)->sum('no_of_days');
                            $clTaken = $user->leaveRequests->where('leave_type_id', 6)->where('is_approved', 1)->sum('no_of_days');
                            $melTaken = $user->leaveRequests->where('leave_type_id', 7)->where('is_approved', 1)->sum('no_of_days');
                        @endphp
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $user->emp_code }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->department->name }}</td>
                            <td>{{ $elAvailable - $elTaken }}</td>
                            <td>{{ $elTaken }}</td>
                            <td>{{ $clAvailable - $clTaken }}</td>
                            <td>{{ $clTaken }}</td>
                            <td>{{ $melAvailable - $melTaken }}</td>
                            <td>{{ $melTaken }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="d-flex justify-content-between">
            <div>
                <strong>Total Employee:</strong> {{ $users->total() }}
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

</div>
