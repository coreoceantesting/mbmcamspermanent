<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employees</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="container-fluid support-ticket">
                    <div class="row">

                        <div class="col-sm-12">

                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table-bordered" id="datatable-tabletools">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Total Employee</th>
                                                    <th>Today's Present</th>
                                                    <th>Today's Absent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee_list as $employee)
                                                    @php
                                                        $todays_absent = $employee->users_count - $employee->present_count;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <a href="{{ route('employees-new.list', ['status' => '','contractor_id' => $contractorId, 'department_id' => $departmentId, 'designation' => $designation, 'employeeType' => $employeeType]) }}">{{ $employee->users_count }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('employees-new.list', ['status' => 'present','contractor_id' => $contractorId, 'department_id' => $departmentId, 'designation' => $designation, 'employeeType' => $employeeType]) }}">{{ $employee->present_count }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('employees-new.list', ['status' => 'absent','contractor_id' => $contractorId, 'department_id' => $departmentId, 'designation' => $designation, 'employeeType' => $employeeType]) }}">{{ $todays_absent }}</a>
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
            </div>
        </div>
    </div>



</x-admin.admin-layout>
