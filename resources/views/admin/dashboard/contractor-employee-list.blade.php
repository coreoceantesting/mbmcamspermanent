<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Contractor Wise Employee List</x-slot>

    <div class="page-body">
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="row">
                    <div class="col-sm-12">

                        <h3>
                            @if($status == 'present')
                                {{ 'Present Employee List' }}
                            @elseif ($status == 'absent')
                                {{ 'Absent Employee List' }}
                            @else
                                {{ 'All Employee List' }}
                            @endif
                        </h3>

                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>

                @foreach($designations as $designation)
                @if($employees->where('designation_id', $designation->id)->count() > 0)
                    <div class="col-md-4 col-lg-4 col-xl-4 box-col-4">
                        <div class="card custom-card rounded">
                            <h6 class="card-header rounded bg-primary py-3 px-3 text-center">
                                {{ Str::limit(ucwords($designation->name), 25) }}  ({{ $employees->where('designation_id', $designation->id)->count() }})
                            </h6>
                            <div class="card-footer row">
                                @foreach ($wards as $ward)
                                    @php
                                        $count = $employees->where('designation_id', $designation->id)
                                                            ->where('ward_id', $ward->id)
                                                            ->count();
                                    @endphp
                                    @if($count > 0)
                                        <div class="col-3 col-sm-3">
                                            <h6 class="font-14">{{ $ward->initial }}</h6>
                                            <a href="{{ route('employees-new.list', ['status' => request()->status,'contractor_id' => $contractorId, 'department_id' => $departmentId, 'designation' => $designation->id, 'ward' => $ward->id, 'employeeType' => $employeeType]) }}">
                                                <h3 class="font-18"><span class="counter">{{ $count }}</span></h3>
                                            </a>
                                        </div>
                                    @else
                                    <div class="col-3 col-sm-3">
                                        <h6 class="font-14">{{ $ward->initial }}</h6>
                                            <h3 class="font-18"><span class="counter">0</span></h3>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach


                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>SR NO.</th>
                                            <th>EMP ID</th>
                                            <th>NAME</th>
                                            <th>DEPARTMENT</th>
                                            <th>DESIGNATION</th>
                                            <th>OFFICE</th>
                                            <th>LOCATION</th>
                                            <th>CONTRACTOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $employee?->emp_code }}</td>
                                                <td>{{ $employee?->name }}</td>
                                                <td>{{ $employee?->department?->name }}</td>
                                                <td>{{ $employee?->designation?->name }}</td>
                                                <td>{{ $employee?->ward?->name }}</td>
                                                <td>{{ $employee?->device?->DeviceLocation }}</td>
                                                <td>{{ $employee?->contractor?->name }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No employees found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- Container-fluid Ends -->
    </div>


</x-admin.admin-layout>
