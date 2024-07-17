<div style="">
    <div wire:loading.flex style="position: absolute;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background: rgba(0,0,0,0.1);">Loading...
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
                        <th style="min-width: 100px" scope="col"> <span class="custom_th">Sr No. </span> <span class="arrow"></span> </th>
                        <th style="min-width: 110px" wire:click="sorting('user.emp_code', '{{$order}}')" class="sortable {{ $column == 'user.emp_code' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Emp Id </span> <span class="arrow"></span> </th>
                        <th                          wire:click="sorting('user.name', '{{$order}}')" class="sortable {{ $column == 'user.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Name </span> <span class="arrow"></span> </th>
                        <th style="min-width: 140px" wire:click="sorting('user.ward.name', '{{$order}}')" class="sortable {{ $column == 'user.ward.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Office </span> <span class="arrow"></span> </th>
                        <th style="min-width: 160px" wire:click="sorting('user.department.name', '{{$order}}')" class="sortable {{ $column == 'user.department.name' ? 'active' : '' }} {{ $order }}" scope="col"> <span class="custom_th">Department </span> <span class="arrow"></span> </th>
                        {{-- <th style="min-width: 120px" scope="col">Device</th> --}}
                        <th style="min-width: 120px" scope="col">Check In</th>
                        <th style="min-width: 120px" scope="col">Check Out</th>
                        <th style="min-width: 90px" scope="col">Is Late</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = $punches->perPage() * ($punches->currentPage() -1 );
                    @endphp
                    @foreach ($punches as $punch)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $punch->user->emp_code }}</td>
                            <td>{{ $punch->user->name }}</td>
                            <td>{{ $punch->user->ward->name }}</td>
                            <td>{{ $punch->user->department->name }}</td>
                            {{-- <td>{{ $punch->device->name }}</td> --}}
                            <td>{{ $punch->check_in }}</td>
                            <td>{{ $punch->check_out }}</td>
                            <td>
                                <div class="media-body text-end icon-state">
                                    <label class="switch">
                                        <input type="checkbox" class="status" data-id="{{ $punch->id }}" {{ $punch->is_latemark == '1' ? 'checked' : '' }}><span class="switch-state"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <button class="edit-element btn btn-primary px-2 py-1" title="Edit Attendance" data-id="{{ $punch->id }}"><i class="fa fa-pencil"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-end">
            {{ $punches->links() }}
        </div>
    </div>

</div>
