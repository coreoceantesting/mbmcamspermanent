<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TMC corebio admin panel">
    <meta name="keywords" content="TMC corebio admin panel">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>{{ config('app.name') }} - Admin Panel Login</title>
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

    <style>
        .login-card{
            background: url('{{asset("assets/images/bg_login.jpg")}}');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
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
                    <div class="login-card">
                        <form class="theme-form login-form" id="loginForm">
                            <div class="col-12 mb-4 text-center">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="" style="height: 100px; width: auto" class="img-fluid">
                                {{-- <h4>Kalyan Dombivali Municipal Corporation</h4> --}}
                                <h4 class="mt-3">मिरा-भाईंदर महानगरपालिका</h4>
                            </div>
                            @csrf
                            @if(request()->device_type == 'mobile')
                                <input type="hidden" name="device_type" value="mobile">
                            @endif

                            <h4>Permanent Employees Login</h4>
                            <h6>Welcome back! Log in to your account</h6>

                            <div class="form-group">
                                <label>Mobile No <span id="generatedOTPMsg"></span></label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                    <input class="form-control" type="text" name="mobile" id="mobile"
                                        placeholder="">
                                </div>
                                <span class="text-danger error-text mobile_err"></span>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary me-0 ms-auto" id="generateOtp" type="button">Generate Otp</button>
                            </div>

                            <div class="form-group d-none displayOtp">
                                <label>Enter OTP</label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control" type="text" id="otp" name="otp" placeholder="otp">
                                </div>
                                <span class="text-danger error-text otp_err"></span>
                            </div>


                            <div class="form-group d-flex pb-5 d-none displayOtp">
                                <button class="btn btn-primary me-0 ms-auto" id="loginForm_submit" type="submit">Sign in</button>
                            </div>

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
    <!-- login js-->

    <script>
        $(document).ready(function(){
            $('#generateOtp').click(function(){
                    let mobile = $('#mobile').val();
                    $.ajax({
                    url: '{{ route('generate-otp') }}',
                    type: 'POST',
                    data: {
                        _token : "{{ csrf_token() }}",
                        mobile : mobile
                    },
                    success: function(data) {
                        if (!data.error && !data.error2) {
                            $('.mobile_err').text('');
                            $('#mobile').removeClass('is-invalid')
                            if(data.success != ""){
                                $('#generateOtp').addClass('d-none');
                                $('.displayOtp').removeClass('d-none');
                                $('#generatedOTPMsg').text(data.otp)
                            }else{
                                $('#generateOtp').removeClass('d-none');
                                $('.displayOtp').addClass('d-none');
                                $('#generatedOTPMsg').text('')
                            }
                        } else {
                            if (data.error2) {
                                swal("Error!", data.error2, "error");
                                $("#loginForm_submit").prop('disabled', false);
                            } else {
                                $("#loginForm_submit").prop('disabled', false);
                                resetErrors();
                                printErrMsg(data.error);
                            }
                        }
                    },
                    error: function(error) {
                        $("#loginForm_submit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    },
                });
            });


        });

        function resetErrors() {
                var form = document.getElementById('loginForm');
                var data = new FormData(form);
                for (var [key, value] of data) {
                    console.log(key, value)
                    $('.' + key + '_err').text('');
                    $('#' + key).removeClass('is-invalid');
                    $('#' + key).addClass('is-valid');
                }
            }

            function printErrMsg(msg) {
                $.each(msg, function(key, value) {
                    console.log(key);
                    $('.' + key + '_err').text(value);
                    $('#' + key).addClass('is-invalid');
                });
            }

        $("#loginForm").submit(function(e) {
            e.preventDefault();
            $("#loginForm_submit").prop('disabled', true);
            var formdata = new FormData(this);
            $.ajax({
                url: '{{ route('otp-signin') }}',
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (!data.error && !data.error2) {
                            window.location.href = "{{ route('dashboard') }}";
                    } else {
                        if (data.error) {
                            swal("Error!", data.error, "error");
                            $("#loginForm_submit").prop('disabled', false);
                        } else {
                            $("#loginForm_submit").prop('disabled', false);
                            resetErrors();
                            printErrMsg(data.error);
                        }
                    }
                },
                error: function(error) {
                    $("#loginForm_submit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                },
            });
        });
    </script>

</body>

</html>
