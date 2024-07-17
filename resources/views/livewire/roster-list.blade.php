<div>
    @push('styles')
        <style>
            .col-style{
                min-width: 110px;
                font-size: 12px;
            }
        </style>
    @endpush
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

    <div class="row mt-2 mb-3">
        <div class="col-3">
            <label class="form-label mb-0" for="">From Date</label>
            <input type="date" wire:model="from_date" class="form-control">
        </div>
        <div class="col-3">
            <label class="form-label mb-0" for="">To Date</label>
            <input type="date" wire:model="to_date" readonly class="form-control">
        </div>

        <div class="col-3">
            <label class="form-label mb-0" for="">Sub Department</label>
            <select name="" class="form-control" wire:model="department_id">
                <option value="">Select Sub Department</option>
                @foreach ($departments as $department)
                    <option value="{{$department->id}}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3">
            <label class="form-label mb-0" for="">Designation</label>
            <select name="" class="form-control" wire:model="designation_id">
                <option value="">Select Designation</option>
                @foreach ($designations as $designation)
                    <option value="{{$designation->id}}">{{ $designation->name }}</option>
                @endforeach
            </select>
        </div>

    </div>

    @if( !empty($users) )
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
                <input type="text" name="search" wire:model.debounce.700="search" class="form-control" placeholder="search...">
            </div>
        </div>

        <div class="row mt-4" style="overflow-x: scroll">
            <div class="col-12">
                <table class="table table-hover" id="list_table">
                    <thead>
                        <tr>
                            <th style="min-width: 60px"> <span class="custom_th">Sr No. </span> <span class="arrow"></span> </th>
                            <th style="min-width: 110px">Emp Code</th>
                            <th style="min-width: 140px">Emp Name</th>
                            <th style="min-width: 120px">Department</th>
                            <th style="min-width: 120px">Sub Department</th>
                            @foreach ($date_ranges as $date_range)
                                <th class="col-style"> {{ $date_range->format('l') }} <br> <span>({{ $date_range->format('d M') }})</span></th>
                            @endforeach
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = $users->perPage() * ($users->currentPage() -1 );
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->emp_code }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->department?->name }}</td>
                                <td>{{ $user->subDepartment?->name }}</td>
                                @foreach ($date_ranges as $date_range)
                                    <td>{{ $user->empShifts->where('from_date', $date_range->toDateString())->value('in_time') ?? $defaultShift['from_time'] }}</td>
                                @endforeach
                                <td>
                                    <button class="btn btn-primary px-2 py-1" title="Edit Shift" wire:click="editShift({{$user->id}})"><i class="fa fa-pencil"></i></button>
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
                    Total Employee: {{ $users->total() }}
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    @endif

</div>
