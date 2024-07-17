<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Detailed Contractor Wise Report</x-slot>

    <div class="page-body">
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="row">
                    <div class="col-sm-12">
                        {{-- <h3>{{ $employees[0]?->contractor?->name . " (". $employees[0]?->designation?->name.")". "(". $employees[0]?->ward?->name .")" }}</h3> --}}
                        @if (!empty($employees) && count($employees) > 0)
                        <h3>{{ $employees[0]?->contractor?->name . " (" . $employees[0]?->designation?->name . ")" . "(" . $employees[0]?->ward?->name . ")" }}</h3>
                    @else
                        <p>No employee data available.</p>
                    @endif
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>

                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th>SR NO.</th>
                                            <th>EMP CODE</th>
                                            <th>EMP NAME</th>
                                            <th>DESIGNATION</th>
                                            <th>DEPARTMENT</th>
                                            <th>CONTRACTOR NAME</th>
                                            <th>OFFICE</th>
                                            <th>LOCATION</th>
                                            @if($status == 'present')
                                            <th>IN TIME</th>
                                            <th>OUT TIME</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $employee?->emp_code }}</td>
                                                <td>{{ $employee?->name }}</td>
                                                <td>{{ $employee?->designation?->name }}</td>
                                                <td>{{ $employee?->department?->name }}</td>
                                                <td>{{ $employee?->contractor?->name }}</td>
                                                <td>{{ $employee?->ward?->name }}</td>
                                                <td>{{ $employee?->device?->DeviceLocation }}</td>
                                                @if($status == 'present')
                                                <td>{{ $employee?->check_in }}</td>
                                                <td>{{ $employee?->check_out }}</td>
                                                @endif
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
