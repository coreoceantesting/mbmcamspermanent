<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="TMC corebio admin panel">
    <meta name="keywords"
        content="TMC corebio admin panel">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>TMC - Login</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-4.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <section>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="login-card" style="padding: 0">
                        <form class="theme-form login-form pb-5" id="registerForm" style="height: 100vh; padding: 10px 18px;">
                            <div class="col-12 mb-2 text-center">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" style="height: 65px; width: auto" class="img-fluid">
                                <h4 class="mt-2" style="font-size: 18px">ठाणे महानगरपालिका</h4>
                            </div>
                            @csrf

                            <h4 class="text-center">Register</h4>

                            <div class="form-group row">
                                <div class="col-8">
                                    <label class="mb-1" for="date">Employee Id <span class="text-danger">*</span> </label>
                                    <input class="form-control" name="emp_code" type="text" placeholder="Enter Employee Id">
                                    <span class="text-danger error-text emp_code_err"></span>
                                </div>
                                <div class="col-4">
                                    <button type="button" id="searchEmpCode" class="btn btn-primary px-3 mt-4 ms-auto">Search</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="name">Full Name<span class="text-danger">*</span> </label>
                                <input class="form-control" name="name" type="text" placeholder="Enter Full Name">
                                <span class="text-danger error-text name_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="gender">Gender<span class="text-danger">*</span> </label>
                                <select name="gender" id="" class="form-control">
                                    <option value="m">Male</option>
                                    <option value="f">Female</option>
                                </select>
                                <span class="text-danger error-text gender_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="mobile">Mobile Number<span class="text-danger">*</span> </label>
                                <input class="form-control" name="mobile" type="number" placeholder="Enter Mobile Number">
                                <span class="text-danger error-text mobile_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="email">Email Id<span class="text-danger">*</span> </label>
                                <input class="form-control" name="email" type="email" placeholder="Enter Email Id">
                                <span class="text-danger error-text email_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="ward_id">Select Office<span class="text-danger">*</span> </label>
                                <select class="js-example-basic-single col-sm-12" name="ward_id">
                                    <option value="">--Select Ward--</option>
                                    @foreach ($wards as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text ward_id_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="ward_id">Select Department<span class="text-danger">*</span> </label>
                                <select class="js-example-basic-single col-sm-12" name="department_id">
                                    <option value="">--Select Department--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text department_id_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="ward_id">Select Class<span class="text-danger">*</span> </label>
                                <select class="js-example-basic-single col-sm-12" name="clas_id">
                                    <option value="">--Select Class--</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text clas_id_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="designation_id">Select Designation<span class="text-danger">*</span> </label>
                                <select class="js-example-basic-single col-sm-12" name="designation_id">
                                    <option value="">--Select Designation--</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text designation_id_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="password">Password<span class="text-danger">*</span> </label>
                                <input class="form-control" name="password" type="password" placeholder="********">
                                <span class="text-danger error-text password_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="mb-1" for="confirm_password">Confirm Password<span class="text-danger">*</span> </label>
                                <input class="form-control" name="confirm_password" type="password" placeholder="********">
                                <span class="text-danger error-text confirm_password_err"></span>
                            </div>

                            <div class="form-group pb-5">
                                <button class="btn btn-primary btn-block ms-auto" id="registerFormSubmit" type="submit">Register</button>
                            </div>

                            <div style="height: 30vh"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Theme js-->
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <!-- login js-->

    {{-- Add --}}
    <script>
        $("#registerForm").submit(function(e) {
            e.preventDefault();
            $("#registerFormSubmit").prop('disabled', true);

            var formdata = new FormData(this);
            $.ajax({
                url: '{{ route('employee.signup') }}',
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#registerFormSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                        .then((action) => {
                            window.location.href = '{{ route('employee.home') }}';
                        });
                    else
                        swal("Error!", data.error2, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        console.log(responseObject);
                        $("#registerFormSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#registerFormSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
            });

            function resetErrors() {
                var form = document.getElementById('registerForm');
                var data = new FormData(form);
                for (var [key, value] of data) {
                    $('.' + key + '_err').text('');
                    $('#' + key).removeClass('is-invalid');
                    $('#' + key).addClass('is-valid');
                }
            }

            function printErrMsg(msg) {
                $.each(msg, function(key, value) {
                    $('.' + key + '_err').text(value);
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).removeClass('is-valid');
                });
            }

        });
    </script>


{{-- Search Emp Info --}}
<script>
    $(document).ready(function(){
        $("#searchEmpCode").click(function(e){
            var empCode = $("input[name='emp_code']").val();

            if( empCode == '' )
                swal("Error!", "Please enter employee code", "error");
            else
            {
                var url = "{{ route('employee.emp-info') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'emp_code': empCode
                    },
                    success: function(data, textStatus, jqXHR)
                    {
                        if (!data.error2)
                        {
                            $("#registerForm input[name='name']").val(data.user.name);
                            $("#registerForm select[name='gender']").val(data.user.gender);
                            $("#registerForm input[name='mobile']").val(data.user.mobile);
                            $("#registerForm input[name='email']").val(data.user.email);
                            $("#registerForm select[name='ward_id']").html(data.wardHtml);
                            $("#registerForm select[name='designation_id']").html(data.designationHtml);
                            $("#registerForm select[name='clas_id']").html(data.classHtml);
                            $("#registerForm select[name='department_id']").html(data.departmentHtml);
                        } else {
                            swal("Error!", data.error2, "error");
                        }
                    },
                    error: function(error, jqXHR, textStatus, errorThrown) {
                        swal("Error!", "No Employee found for this Emp code", "error");
                    },
                });
            }
        });
    });
</script>


</body>


</html>
