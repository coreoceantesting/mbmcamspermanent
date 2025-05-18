<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Retirement Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Retirement Report</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('reports.retirement') }}">

                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3 d-none">
                                            <label class="col-form-label" for="period">Select Period <span class="text-danger">*</span> </label>
                                            <select name="period" class="form-select" id="period" required>
                                                <option value="6" selected @if(isset(request()->period) && request()->period == "6")selected @endif>10</option>
                                            </select>
                                            <span class="text-danger error-text year_err"></span>
                                        </div>


                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="ward">Ward </label>
                                            <select class="form-select col-sm-12  @error('ward') is-invalid  @enderror" name="ward">
                                                <option value="">--Select Ward--</option>
                                                @foreach ($wards as $w)
                                                    <option value="{{ $w->id }}" {{ request()->ward == $w->id ? 'selected' : '' }} >{{ $w->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('ward')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="department">Department </label>
                                            <select class="js-example-basic-single col-sm-12  @error('department') is-invalid  @enderror" name="department">
                                                <option value="">--Select All Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}" {{ request()->department == $department->id ? 'selected' : '' }} >{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>



                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Class  </label>
                                            <select class="js-example-basic-single col-sm-12  @error('class') is-invalid  @enderror" name="class">
                                                <option value="">--Select Class--</option>
                                                @foreach ($class as $clas)
                                                    <option value="{{ $clas->id }}" {{ request()->class == $clas->id ? 'selected' : '' }} >{{ $clas->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" >Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        </div>

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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        {{-- <button id="addToTable" class="btn btn-primary">Manual Attendance <i class="fa fa-plus"></i></button> --}}
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-bordered" id="datatable-tabletools_test">
                                    <thead>
                                        <tr>
                                            <th >Sr No</th>
                                            <th >Emp Code</th>
                                            <th >Emp Name</th>
                                            <th >Ward</th>
                                            <th >Department</th>
                                            <th >Class</th>
                                            <th >Email</th>
                                            <th >Mobile</th>
                                            <th >Date Of Joining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($periods as $period)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $period->emp_code }}</td>
                                            <td>{{ $period->name }}</td>
                                            <td>{{ $period->ward?->name }}</td>
                                            <td>{{ $period->department?->name }}</td>
                                            <td>{{ $period->clas?->name }}</td>
                                            <td>{{ $period->email }}</td>
                                            <td>{{ $period->mobile }}</td>
                                            <td>{{ \Carbon\Carbon::parse($period->doj)->format('d-m-Y') }}</td>
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
        <!-- Container-fluid Ends-->
    </div>

</x-admin.admin-layout>



<!-- Show Details -->
<script>
    $("#datatable-tabletools_test").on("click", ".emp-more-info", function(e) {
        e.preventDefault();
        var model_id = $(this).attr("data-id");
        var url = "{{ route('punches.show', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                console.log(data);
                if (data.result == 1)
                {
                    $("#more-info-modal").modal('show');
                    $("#empMoreInfo").html(data.html);
                }
                else
                {
                    swal("Error!", "Some thing went wrong", "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


{{-- Get month wise date --}}
<script>
    $(document).ready(function(){

        $("select[name='month']").change(function(e){
            e.preventDefault();
            var month = $("select[name='month']").val();
            var year = $("select[name='year']").val();

            if(month != '')
            {
                $.ajax({
                    url: "{{ route('reports.dates') }}",
                    type: 'GET',
                    data: {
                        'month': month,
                        'year': year,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error)
                        {
                            $("input[name='from_date']").val(data.fromDate);
                            $("input[name='to_date']").val(data.toDate);
                        } else {
                            swal("Error!", data.error, "error");
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "Some thing went wrong", "error");
                    },
                });
            }
            else
            {
            alert("please select month");
            }
        });

    });

    (function($) {

    'use strict';

    var datatableInit = function() {
        var $table = $('#datatable-tabletools_test');
        let period = ($('#period').val() != "") ? $('#period').val() : '';
        let ward = ($("select[name=ward]").find(':selected').val() != "") ? $("select[name=ward]").find(':selected').text() : '';
        let department = ($("select[name=department]").find(':selected').val() != "") ? $("select[name=department]").find(':selected').text() : '';
        let classVal = ($("select[name=class]").find(':selected').val() != "") ? $("select[name=class]").find(':selected').text() : '';
        var reportDetails = `<b>
            Period :- ${period} <br>
            Ward :- ${ward} <br>
            Department :- ${department} <br>
            Class :- ${classVal} <br>
        </b>`;
        var table = $table.dataTable({
            sDom: '<"text-right mb-md"T><"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
            buttons: [ {
                extend: 'print',
                messageTop: reportDetails
            },
            {
                extend: 'excel',
            },
            {
                extend: 'pdf',
            } ],

        });

        $('<div />').addClass('dt-buttons mb-2 pb-1 text-right').prependTo('#datatable-tabletools_test_wrapper');

        $table.DataTable().buttons().container().prependTo( '#datatable-tabletools_test_wrapper .dt-buttons' );

        $('#datatable-tabletools_test_wrapper').find('.btn-secondary').removeClass('btn-secondary').addClass('btn-dark ms-2');
    };

    $(function() {
        datatableInit();
    });

}).apply(this, [jQuery]);
</script>

<!-- Get Ward wise departments -->
{{-- <script>
    $("select[name='ward']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('wards.departments', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                if (!data.error)
                {
                    $("select[name='department']").html(data.departmentHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script> --}}
