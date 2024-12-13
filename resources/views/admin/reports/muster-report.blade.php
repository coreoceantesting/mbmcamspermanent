<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Muster Report</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <div class="row">
                    <div class="col-sm-12">
                        <h3>Muster Report</h3>

                        @if(Session::has('success'))
                            <div class="alert bg-success text-center">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        @if($errorMessage)
                            <div class="alert bg-danger text-center">
                                {{ $errorMessage }}
                            </div>
                        @endif

                        <div class="card">
                            <form class="theme-form" method="GET" action="{{ route('reports.muster') }}" target="_blank">
                                
                                <div class="card-body pt-0">

                                    <div class="mb-3 row">
                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="year">Year <span class="text-danger">*</span> </label>
                                            <select name="year" class="form-select" id="year">
                                                @php $year = (date('m') == 12) ? $year = date('Y') + 1 : date('Y'); @endphp
                                                @for($i=$year; $i >= 2022; $i--)
                                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <span class="text-danger error-text year_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="month">Select Month <span class="text-danger">*</span></label>
                                            <select class="col-sm-12 form-select @error('month') is-invalid  @enderror" value="{{ old('month') }}" required name="month">
                                                <option value="">--Select Month--</option>
                                                <option value="1" {{ request()->month == 1 ? 'selected' : '' }} >January</option>
                                                <option value="2" {{ request()->month == 2 ? 'selected' : '' }} >February</option>
                                                <option value="3" {{ request()->month == 3 ? 'selected' : '' }} >March</option>
                                                <option value="4" {{ request()->month == 4 ? 'selected' : '' }} >April</option>
                                                <option value="5" {{ request()->month == 5 ? 'selected' : '' }} >May</option>
                                                <option value="6" {{ request()->month == 6 ? 'selected' : '' }} >June</option>
                                                <option value="7" {{ request()->month == 7 ? 'selected' : '' }} >July</option>
                                                <option value="8" {{ request()->month == 8 ? 'selected' : '' }} >August</option>
                                                <option value="9" {{ request()->month == 9 ? 'selected' : '' }} >September</option>
                                                <option value="10" {{ request()->month == 10 ? 'selected' : '' }} >October</option>
                                                <option value="11" {{ request()->month == 11 ? 'selected' : '' }} >November</option>
                                                <option value="12" {{ request()->month == 12 ? 'selected' : '' }} >December</option>
                                            </select>
                                            @error('month')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="from_date">From Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="from_date" id="from_date" type="date" value="{{ request()->from_date }}" placeholder="From Date" readonly>
                                            <span class="text-danger error-text from_date_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="to_date">To Date <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="to_date" id="to_date" type="date" value="{{ request()->to_date }}" placeholder="To Date" readonly>
                                            <span class="text-danger error-text to_date_err"></span>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Ward</label>
                                            <select class="js-example-basic-single col-sm-12  @error('ward') is-invalid  @enderror" name="ward">
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

                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="contractor">Contractors  <span class="text-danger"></span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('contractor') is-invalid  @enderror" name="contractor" required>
                                                <option value="">--Select Contractors--</option>
                                            </select>
                                            @error('contractor')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="designation">Designation  <span class="text-danger"></span></label>
                                            <select class="js-example-basic-single col-sm-12  @error('designation') is-invalid  @enderror" name="designation">
                                                <option value="">--Select Designation--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}" {{ request()->designation == $designation->id ? 'selected' : '' }} >{{ $designation->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('designation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="sub_department">Sub Department </label>
                                            <select class="js-example-basic-single col-sm-12  @error('sub_department') is-invalid  @enderror" name="sub_department">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            @error('sub_department')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        {{-- <div class="col-md-3 mt-3">
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
                                        </div> --}}

                                        <div class="col-md-3 mt-3">
                                            <label class="col-form-label" for="class">Emp Code <span class="text-info" style="font-size: 10px">for individual report</span></label>
                                            <input class="form-control" name="emp_code" id="emp_code" type="text" value="{{ request()->emp_code }}" placeholder="Employee Code" >
                                            @error('emp_code')
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

    </div>




</x-admin.admin-layout>

<!-- Get Contractors -->
<script>
    $("select[name='department']").change( function(e) {

        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('fetch-contractor', ':model_id') }}";

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
                    $("select[name='contractor']").html(data.ContractorHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>


<!-- Get Sub departments -->
<script>
    // $("select[name='department']").change( function(e) {
    //     e.preventDefault();

    //     var model_id = $(this).val();
    //     var url = "{{ route('departments.sub_departments', ':model_id') }}";

    //     $.ajax({
    //         url: url.replace(':model_id', model_id),
    //         type: 'GET',
    //         data: {
    //             '_token': "{{ csrf_token() }}"
    //         },
    //         success: function(data, textStatus, jqXHR)
    //         {
    //             if (!data.error)
    //             {
    //                 $("select[name='sub_department']").html(data.subDepartmentHtml);
    //             } else {
    //                 swal("Error!", data.error, "error");
    //             }
    //         },
    //         error: function(error, jqXHR, textStatus, errorThrown) {
    //             swal("Error!", "Some thing went wrong", "error");
    //         },
    //     });
    // });
</script>


<!-- Show Details -->
<script>
    $("#datatable-tabletools").on("click", ".emp-more-info", function(e) {
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
</script>

