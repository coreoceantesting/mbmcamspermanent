<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Employees</x-slot>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form Start -->
                <div class="row" id="addContainer">
                    <div class="col-sm-12">
                        <div class="card">
                            <form class="theme-form" name="addForm" id="addForm">
                                @csrf
                                <div class="card-header pb-0">
                                    <h4>Create Employee</h4>
                                </div>
                                <div class="card-body pt-0">


                                    <div class="mb-3 row">
                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="emp_code">Employee Code <span class="text-danger">*</span> </label>
                                            <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Code">
                                            <span class="text-danger error-text emp_code_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="name">Employee Name <span class="text-danger">*</span></label>
                                            <input class="form-control" name="name" type="text" placeholder="Enter Employee Name">
                                            <span class="text-danger error-text name_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="email">Employee Email </label>
                                            <input class="form-control" name="email" type="email" placeholder="Enter Employee Email">
                                            <span class="text-danger error-text email_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="mobile">Employee Mobile </label>
                                            <input class="form-control" name="mobile" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter Employee Mobile">
                                            <span class="text-danger error-text mobile_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="aadhaar_no">Aadhaar No </label>
                                            <input class="form-control" name="aadhaar_no" type="number" placeholder="Enter Aadhaar No">
                                            <span class="text-danger error-text aadhaar_no_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="permanent_address">Permanent Address </label>
                                            <input class="form-control" name="permanent_address" type="text" placeholder="Enter Permanent Address">
                                            <span class="text-danger error-text permanent_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="present_address">Present Address </label>
                                            <input class="form-control" name="present_address" type="text" placeholder="Enter Present Address">
                                            <span class="text-danger error-text present_address_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="dob">Date of Birth </label>
                                            <input class="form-control" id="dob" name="dob" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Birth">
                                            <span class="text-danger error-text dob_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="doj">Date of Joining </label>
                                            <input class="form-control" id="doj" name="doj" type="date" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}" onclick="this.showPicker()" placeholder="Enter Date of Joining">
                                            <span class="text-danger error-text doj_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="gender">Gender <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radioMale">
                                                    <input class="radio_animated" id="radioMale" type="radio" name="gender" checked="" value="m">Male
                                                </label>
                                                <label class="me-3" for="radioFemale">
                                                    <input class="radio_animated" id="radioFemale" type="radio" name="gender" value="f">Female
                                                </label>
                                            </div>
                                            <span class="text-danger error-text gender_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Employee Type<span class="text-danger">*</span></label>
                                            <select class="form-select col-sm-12" name="employee_type">
                                                {{-- <option value=""> Select Employee Type </option> --}}
                                                {{-- <option value="0"> Contractual </option> --}}
                                                <option value="1" selected> Permanent </option>
                                            </select>
                                            <span class="text-danger error-text employee_type_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3 d-none">
                                            <label class="col-form-label" >Contractor Name<span class="text-danger">*</span></label>
                                            <select class="form-select col-sm-12" name="contractor_id">
                                                <option value=""> Select Contractor </option>
                                                @foreach ($contractors as $contractor)
                                                    <option value="{{ $contractor->id }}">{{ $contractor->name }} </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text contractor_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Is Rotational ?<span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="is_rotational">
                                                <option value=""> Is Rotational ? </option>
                                                <option value="0"> No </option>
                                                <option value="1"> Yes </option>
                                            </select>
                                            <span class="text-danger error-text is_rotational_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3 d-none">
                                            <label class="col-form-label" >Select Shift <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="shift_id">
                                                <option value="">--Select Shift--</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->id }}">{{ Carbon\Carbon::parse($shift->from_time)->format('h:i A') }} - {{ Carbon\Carbon::parse($shift->to_time)->format('h:i A') }} ( <strong>{{ $shift->name }}</strong> ) </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text shift_id_err"></span>
                                        </div>



                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Office <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="ward_id">
                                                <option value="">--Select Office--</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text ward_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="department_id">Select Department <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="department_id">
                                                <option value="">--Select Department--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Sub Department </label>
                                            <select class="js-example-basic-single col-sm-12" name="sub_department_id">
                                                <option value="">--Select Sub Department--</option>
                                            </select>
                                            <span class="text-danger error-text sub_department_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Machine <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="device_id">
                                                <option value="">--Select Machine--</option>
                                                @foreach ($devices as $device)
                                                    <option value="{{ $device->DeviceId }}">{{ $device->DeviceLocation }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text device_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Class <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="clas_id">
                                                <option value="">--Select Class--</option>
                                                @foreach ($clas as $cl)
                                                    <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text clas_id_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Designation </label>
                                            <select class="js-example-basic-single col-sm-12" name="designation_id">
                                                <option value="">--Select Designation--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text designation_id_err"></span>
                                        </div>

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Select Shift <span class="text-danger">*</span></label>
                                            <select class="js-example-basic-single col-sm-12" name="shift_id">
                                                <option value="">--Select Shift--</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->id }}">{{ Carbon\Carbon::parse($shift->from_time)->format('h:i A') }} - {{ Carbon\Carbon::parse($shift->to_time)->format('h:i A') }} ( <strong>{{ $shift->name }}</strong> ) </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text shift_id_err"></span>
                                        </div> --}}

                                        {{-- <div class="col-md-4 mt-3">
                                            <label class="col-form-label" >Choose In Time </label>
                                            <input type="time" class="form-control" name="in_time" onclick="this.showPicker()">
                                            <span class="text-danger error-text in_time_err"></span>
                                        </div> --}}

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_ot">Is OT Allow ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" checked="" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_ot_yes">
                                                    <input class="radio_animated" id="radio_ot_yes" type="radio" name="is_ot" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="is_divyang">Is Employee Divyang ? <span class="text-danger">*</span></label>
                                            <div class="col">
                                                <label class="me-3" for="radio_divyang_yes">
                                                    <input class="radio_animated" id="radio_divyang_yes" type="radio" name="is_divyang" value="y">Yes
                                                </label>
                                                <label class="me-3" for="radio_divyang_no">
                                                    <input class="radio_animated" id="radio_divyang_no" type="radio" name="is_divyang" checked="" value="n">No
                                                </label>
                                            </div>
                                            <span class="text-danger error-text is_ot_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="work_duration">Work Duration </label>
                                            <input class="form-control" id="work_duration" name="work_duration" type="number" min="1" max="12" placeholder="Enter Work Duration">
                                            <span class="text-danger error-text work_duration_err"></span>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="col-form-label" for="sa_duration">Saturday Work Duration </label>
                                            <input class="form-control" id="sa_duration" name="sa_duration" type="number" min="1" max="12" placeholder="Enter Saturday Work Duration">
                                            <span class="text-danger error-text sa_duration_err"></span>
                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>




</x-admin.admin-layout>


<script>
    $(document).ready(function(){

        $('select[name="is_rotational"]').on('change', function() {
        if ( this.value === '1')
            $("select[name='shift_id']").closest('.col-md-4').addClass('d-none');
        else
            $("select[name='shift_id']").closest('.col-md-4').removeClass('d-none');
        });


        $('select[name="employee_type"]').on('change', function() {
        if ( this.value == '0')
            $("select[name='contractor_id']").closest('.col-md-4').removeClass('d-none');
        else
            $("select[name='contractor_id']").closest('.col-md-4').addClass('d-none');
        });

    });
</script>



{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('employees.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#addSubmit").prop('disabled', false);
                if (!data.error2)
                    swal("Successful!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('employees.index') }}';
                    });
                else
                    swal("Error!", data.error2, "error");
            },
            statusCode: {
                422: function(responseObject, textStatus, jqXHR) {
                    $("#addSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#addSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

        function resetErrors() {
            var form = document.getElementById('addForm');
            var data = new FormData(form);
            for (var [key, value] of data) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text('');
                $("[name='"+field+"']").removeClass('is-invalid');
                $("[name='"+field+"']").addClass('is-valid');
            }
        }

        function printErrMsg(msg) {
            $.each(msg, function(key, value) {
                var field = key.replace('[]', '');
                $('.' + key + '_err').text(value);
                $("[name='"+field+"']").addClass('is-invalid');
                $("[name='"+field+"']").removeClass('is-valid');
            });
        }

    });
</script>


<!-- Get Sub departments -->
<script>
    $("select[name='department_id']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('departments.sub_departments', ':model_id') }}";

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
                    $("select[name='sub_department_id']").html(data.subDepartmentHtml);
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


<!-- Get Ward wise departments -->
{{-- <script>
    $("#addForm select[name='ward_id']").change( function(e) {
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
                    $("select[name='department_id']").html(data.departmentHtml);
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
