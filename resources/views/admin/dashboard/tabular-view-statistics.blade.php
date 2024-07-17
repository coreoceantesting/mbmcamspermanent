<x-admin.admin-layout>
    <style>
        a {
            cursor: pointer;
        }
    </style>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Tabular View Statistics</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">

                <div class="row">
                    <div class="col-sm-12">

                        <h3>Tabular View Statistics</h3>

                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered" id="datatable-tabletools">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary">Sr No</th>
                                            <th class="bg-primary">Department</th>
                                            <th class="bg-primary">Total Employee</th>
                                            <th class="bg-primary">Today's Present</th>
                                            <th class="bg-primary">Today's Absent</th>
                                            <th class="bg-primary">Progress Bar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($departmentwise as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                   <a class="view-contractors" data-department-id="{{ $value->id }}" data-toggle="modal" data-target="#contractorModal" >{{ $value->name }}</a>
                                                    {{-- <button type="button" class="btn btn-link view-contractors" data-department-id="{{ $value->id }}" data-toggle="modal" data-target="#contractorModal">View Contractors</button> --}}
                                                </td>
                                                <td>{{ $value->users_count }}</td>
                                                <td>{{ $value->present_count }}</td>
                                                <td>{{ $value->users_count - $value->present_count }}</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: {{ $value->users_count ? floor(($value->present_count / $value->users_count) * 100) : '0' }}%" aria-valuenow="{{ $value->users_count ? floor(($value->present_count / $value->users_count) * 100) : '0' }}" aria-valuemin="0" aria-valuemax="100"> &nbsp;&nbsp; {{ $value->users_count ? floor(($value->present_count / $value->users_count) * 100) : ' 0' }}% </div>
                                                    </div>
                                                </td>
                                                {{-- <td>{{  }}</td> --}}
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <!-- Modal Structure 1 -->
                                <div class="modal fade" id="contractorModal" tabindex="-1" aria-labelledby="contractorModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title" id="contractorModalLabel">Contractors List</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table-bordered" id="datatable-tabletoolsone">
                                                    <thead>
                                                      <tr>
                                                        <th class="bg-primary">Name</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="contractorList"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Structure 2 -->
                                <div class="modal fade" id="designationModel" tabindex="-1" aria-labelledby="designationModelLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="designationModelLabel">Designation List</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table-bordered" id="datatable-tabletoolstwo">
                                                    <thead>
                                                      <tr>
                                                        <th class="bg-primary">Name</th>
                                                        {{-- <th class="bg-primary">Total Employee</th>
                                                        <th class="bg-primary">Today's Present</th>
                                                        <th class="bg-primary">Today's Absent</th> --}}
                                                      </tr>
                                                    </thead>
                                                    <tbody id="designationList"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Structure 3 -->
                                <div class="modal fade" id="employeeModel" tabindex="-1" aria-labelledby="employeeModelLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="employeeModelLabel">Employee Detail</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table-bordered" id="datatable-tabletoolstwo">
                                                    <thead>
                                                      <tr>
                                                        <th class="bg-primary">Total Employee</th>
                                                        <th class="bg-primary">Today's Present</th>
                                                        <th class="bg-primary">Today's Absent</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="employeeList"></tbody>
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
        </div>
        <!-- Container-fluid Ends -->
    </div>


</x-admin.admin-layout>

<!-- Add these in the head section of your layout -->
{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

{{-- contractor view --}}
<script>
    $(document).ready(function() {
        $('.view-contractors').click(function() {
            var departmentId = $(this).data('department-id');

            // AJAX request to fetch contractors for the department
            $.ajax({
                url: '/fetch-contractors/' + departmentId, // Replace with your route to fetch contractors
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var contractors = response.contractors;
                    var modalBody = $('#contractorModal').find('.modal-body');
                    var contractorList = $('#contractorList');

                    // Clear previous data
                    contractorList.empty();

                    // Populate modal with contractors
                    if (contractors.length > 0) {
                        contractors.forEach(function(contractor) {
                            contractorList.append(
                                '<tr>' +
                                    '<td><a data-toggle="modal" class="view-designations" data-target="#designationModel" data-contractor-id="' + contractor.id + '">' + contractor.name + '</a></td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        contractorList.append('<li>No contractors found.</li>');
                    }

                    // Ensure the designation modal opens correctly
                    $('.view-designations').click(function() {
                        var contractorId = $(this).data('contractor-id');
                        $('#designationModel').modal('show');
                        // Add any additional logic if needed

                        $.ajax({
                            url: '/fetch-designation/' + contractorId, // Replace with your route to fetch contractors
                            type: 'GET',
                            dataType: 'json',
                            data : {departmentid: departmentId},
                            success: function(response) {
                                var designations = response.designations;
                                var modalBody = $('#designationModel').find('.modal-body');
                                var designationList = $('#designationList');
                                var employeeType = 0;

                                // Clear previous data
                                designationList.empty();

                                // Populate modal with contractors
                                if (designations.length > 0) {
                                    designations.forEach(function(designation) {
                                        designationList.append(

                                        '<tr>' +
                                            '<td><a href="{{ route('employees-detail.list') }}?status=&contractor_id=' + contractorId + '&department_id=' + departmentId + '&designation=' + designation.id + '&employeeType=' + employeeType + '" data-designation-id="' + designation.id + '">' + designation.name + '</a></td>' +
                                        '</tr>'
                                        );
                                    });
                                } else {
                                    contractorList.append('<li>No Designation found.</li>');
                                }

                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching designation:', error);
                                // Optionally, display an error message in the modal body
                                var modalBody = $('#designationModel').find('.modal-body');
                                modalBody.html('<p>Error fetching designation. Please try again.</p>');
                            }
                        });

                    });

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching contractors:', error);
                    // Optionally, display an error message in the modal body
                    var modalBody = $('#contractorModal').find('.modal-body');
                    modalBody.html('<p>Error fetching contractors. Please try again.</p>');
                }
            });
        });
    });
</script>

<script>
    // Add an event listener to the modal close button
    document.getElementById('closeModalButton').addEventListener('click', function() {
        // Reload the page when the modal is hidden
        $('#designationModel').on('hidden.bs.modal', function () {
            location.reload();
        });
    });
</script>


