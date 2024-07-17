<div style="">
    <div wire:loading.flex style="position: absolute;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background: rgba(255,255,255,0.9);">Loading...
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
                        {{-- <th style="min-width: 100px" wire:click="sorting('app_users.id', '{{$order}}')" class="sortable {{ $column == 'app_users.id' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Sr No. </span> <span class="arrow"></span> </th> --}}
                        <th style="min-width: 70px" scope="col"> <span class="custom_th">Sr No. </span> <span class="arrow"></span> </th>
                        <th style="min-width: 110px" wire:click="sorting('app_users.emp_code', '{{$order}}')" class="sortable {{ $column == 'app_users.emp_code' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Emp Code </span> <span class="arrow"></span> </th>
                        <th wire:click="sorting('app_users.name', '{{$order}}')" class="sortable {{ $column == 'app_users.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Name </span> <span class="arrow"></span> </th>
                        <th style="min-width: 160px" wire:click="sorting('departments.name', '{{$order}}')" class="sortable {{ $column == 'departments.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Department </span> <span class="arrow"></span> </th>
                        <th style="min-width: 140px" wire:click="sorting('wards.name', '{{$order}}')" class="sortable {{ $column == 'wards.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Office </span> <span class="arrow"></span> </th>
                        <th style="min-width: 160px" wire:click="sorting('Devices.DeviceLocation', '{{$order}}')" class="sortable {{ $column == 'Devices.DeviceLocation' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Location </span> <span class="arrow"></span> </th>
                        <th style="min-width: 120px" scope="col">Emp Type</th>
                        <th style="min-width: 120px" scope="col">Contractor</th>
                        <th style="min-width: 120px" scope="col">Details</th>
                        <th style="min-width: 90px" scope="col">Status</th>
                        <th style="min-width: 90px" scope="col">Retirement Status</th>
                        <th style="min-width: 190px" wire:click="sorting('app_users.created_at', '{{$order}}')" class="sortable {{ $column == 'app_users.created_at' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Registered On </span> <span class="arrow"></span>  </th>
                        <th style="min-width: 140px" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = $employees->perPage() * ($employees->currentPage() -1 );
                    @endphp
                    @foreach ($employees as $emp)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $emp->emp_code }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->department_name }}</td>
                            <td>{{ $emp->ward_name }}</td>
                            <td>{{ $emp->location_name }}</td>
                            <td>{{ $emp->employee_type == 0 ? 'Contractual' : 'Permanent' }}</td>
                            <td>{{ $emp?->contractor?->name }}</td>
                            <td>
                                <button class="emp-more-info btn btn-primary px-2 py-1" title="More info" data-id="{{ $emp->id }}"><i class="fa fa-circle-info"></i></button>
                            </td>
                            <td>
                                <div class="media-body text-end icon-state">
                                    <label class="switch">
                                        <input type="checkbox" class="status" data-id="{{ $emp->id }}" {{ $emp->active_status == '1' ? 'checked' : '' }}><span class="switch-state"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                @if ($emp->deleted_at == null)
                                    <strong>Active</strong>
                                @else
                                    <strong>Retired by:</strong> <br>
                                    {{ $emp->deletedBy?->emp_code }} - {{ Str::limit($emp->deletedBy?->name, 20) }} - {{ $emp->deleted_at }}
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($emp->created_at)->format('d M, y h:i:s') }}
                            </td>
                            <td>
                                @if ($emp->deleted_at == null)
                                    <button class="edit-element btn btn-primary px-2 py-1" title="Edit Employee" data-id="{{ $emp->id }}"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-primary change-password px-2 py-1" title="Change Password" data-id="{{ $emp->id }}"><i class="fa fa-lock"></i></button>
                                    <button class="btn btn-warning retire px-2 py-1" title="Retire Employee" data-id="{{ $emp->id }}"><i class="fa fa-user-minus"></i></button>
                                @else
                                    @if( Auth::user()->hasRole(['Super Admin']))
                                        <button class="btn btn-info restore-element px-2 py-1" title="Restore Employee" wire:key="{{ $emp->id }}" wire:click="restoreEmployee({{$emp->id}})" ><i class="fa fa-recycle"></i></button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-end">
            {{ $employees->links() }}
        </div>
    </div>

</div>
