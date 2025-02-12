<div>
    <style>
        .table th, .table td{
            padding: 5px !important;
        }
    </style>
    <div wire:loading.flex style="position: absolute;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background: rgba(245,245,251,0.9);
        margin-top: -30px;
        margin-left: -30px;
        z-index: 4;
        pointer-events: none;
        font-size: 20px;
        font-weight: 600">Loading...
    </div>

    <div class="mb-3 row">
        <div class="col-md-4" wire:ignore>
            <label class="col-form-label" for="to_date">From Date <span class="text-danger">*</span></label>
            <input type="date" wire:model="from_date" id="from_date" onclick="this.showPicker()" class="form-control">
        </div>

        <div class="col-md-4" wire:ignore>
            <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span></label>
            <input type="date" wire:model="to_date" id="to_date" disabled class="form-control">
        </div>

        <div class="col-md-4" wire:ignore>
            <label class="col-form-label" for="day_count">Week Count </label>
            <select wire:model="day_count" id="day_count" class="form-control">
                <option value="">Select Days Count</option>
                <option value="6">1 Week</option>
                <option value="13">2 Week</option>                
                <option value="20">3 Week</option>
                <option value="27">4 Week</option>
            </select>
        </div>
    </div>


    <div class="mb-3 row">
        <div class="col-md-4" wire:ignore>
            <label class="col-form-label" for="ward_id">Office <span class="text-danger">*</span> </label>
            <select class="js-example-basic-single col-sm-12" id="ward_id" name="ward_id">
                <option value="">--Select Office--</option>
                @foreach ($wards as $ward)
                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4" wire:ignore>
            <label class="col-form-label">Choose One <span class="text-danger">*</span></label>
            <div class="col">
                <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                    <div class="radio radio-primary">
                        <input id="radioinline1" type="radio" name="is_department" wire:click="$set('is_department', '1')" value="1">
                        <label class="mb-0" for="radioinline1">Department</label>
                    </div>
                    <div class="radio radio-primary">
                        <input id="radioinline2" type="radio" name="is_department" wire:click="$set('is_department', '2')" value="2">
                        <label class="mb-0" for="radioinline2">Designation</label>
                    </div>
                </div>
            </div>
        </div>
        @if ($is_department == 1)
            <div class="col-md-4">
                <label class="col-form-label">Select Department <span class="text-danger">*</span></label>
                <select class="col-sm-12 form-control" wire:model="selected_department">
                    <option value="">--Select Department--</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if ($is_department == 2)
            <div class="col-md-4">
                <label class="col-form-label">Select Designation <span class="text-danger">*</span> </label>
                <select class="col-sm-12 form-control" wire:model="selected_designation">
                    <option value="">--Select Designation--</option>
                    @foreach ($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>


    @if( !empty($employees) )
        <div class="mb-3 row">
            <div class="col-12">
                <div class="text-success error-text px-4 py-1 mt-3 mb-2" style="font-size:11px; background-color: #19875436; font-weight: 700; border-radius: 5px;">
                    Fill below shift details employee wise
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="min-width: 80px; max-width: 80px" scope="col">Emp Code</th>
                            <th style="min-width: 140px" scope="col">Name</th>
                            @foreach ($date_ranges as $key => $date_range)
                                <th style="min-width: 120px" scope="col">{{ $date_range->format('l') }}
                                    <br>({{ $date_range->format('d M') }})
                                </th>
                            @endforeach
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($employees as $key => $employee)
                            <tr>
                                <th>
                                    <input wire:model.defer="emp_code.{{ $key }}" name="emp_code.{{ $key }}" class="form-control px-1" type="text" disabled placeholder="Enter Employee Code">
                                </th>
                                <th>
                                    {{ strtoupper($employee->name) }}
                                </th>
                                @foreach ($date_ranges as $date_range)
                                    @php
                                        $fieldName = strtolower(substr(Carbon\Carbon::parse($date_range)->format('D'), 0, 2)).'.'.$key;
                                    @endphp
                                    <td>
                                        <select class="form-control @if($errors->has( $fieldName )) is-invalid @endif" name="{{ $fieldName }}" wire:model.defer="{{ $fieldName }}">
                                            <option value="">----</option>
                                            <option value="wo">WEEK OFF</option>
                                            <option value="no">NIGHT OFF</option>
                                            @foreach ($shiftLists as $shiftList)
                                                <option value="{{ $shiftList->id }}">{{ $shiftList->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has($fieldName))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first($fieldName) }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <button class="btn btn-primary" wire:click="saveShift({{$key}})">Save</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    @endif

</div>
