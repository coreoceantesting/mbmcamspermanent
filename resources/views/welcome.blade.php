<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MBMCAMC | Landing Page</title>
        <link rel="shortcut icon" href="https://pmc-fire-management.creatorkart.com/admin/images/favicon.png">
        <link href="https://pmc-fire-management.creatorkart.com/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            body{
                background-color: #f0f8ff;
                overflow-x: hidden;
            }

            .bg-img{
                background-image: url('{{ asset("assets/images/mbmc-banner.jpg") }}');
                background-repeat: no-repeat;
                background-position: 0%;
                background-size: cover;
                content: "";
                height: 100vh;
            }
            .right-content-div{
                background: #284db2;
                color: #fff;
                padding: 3% 2%;
                text-align: center;
                margin: 0% 10%;
                font-size: 18px;
                font-weight: 800;
                border-radius: 10px;
            }
            .custompadding{
                padding: 5% 10%;
            }

            .form-control{
                padding: 10px;
                border: 1px solid #2b5de4;
            }

            @media only screen and (min-width: 1200px) {
                .bg-img {
                    background-position: 1%;
                }
            }

            @media only screen and (max-width: 1999px) {
                .bg-img {
                    background-position: 1%;
                }
            }

            @media only screen and (max-width: 1115px) {
                .bg-img {
                    background-position: 16%;
                }
            }

            @media only screen and (max-width: 1060px) {
                .bg-img {
                    background-position: 24%;
                }
            }

            @media only screen and (max-width: 992px) {
                .bg-img {
                    background-position: 30%;
                }
            }

            @media only screen and (max-width: 767px) {
                    .bg-img {
                        background-image: none;
                        background-color: #fff;
                        height: auto;
                        display: flex: 
                        justify-content: center;
                    }

                    .custompadding{
                        padding: 0%;
                    }

                    .form-control{
                        padding: 10px;
                        background-color: #fff;
                        border: 1px solid #fff;
                    }

                    .form-label, .form-check-label{
                        color: #fff;
                    }

                    #loginForm_submit{
                        background-color: #fff;
                        color: #234cb3;
                        font-weight: 900;
                        font-size: 18px;
                        width : 50% !important;
                    }

                    .textSignup{
                        color: #fff!important;
                    }
                }
            .btn-gradient-1 {
                border-width: 4px;
                border-style: solid;
                border-image: linear-gradient(to right, darkblue, darkorchid) 1;
            }

            /* method 2 -> use background-clip to support border-radius */
            .btn-gradient-2 {
                background: linear-gradient(white, white) padding-box,
                            linear-gradient(to right, darkblue, darkorchid) border-box;
                border-radius: 50em;
                border: 4px solid transparent;
            }

            /* demo stuff */
            .parent {
                display: flex;
                flex-wrap: wrap;
                gap: 3rem;
                padding: 1rem;
                justify-content: center;
                align-items: center;
                /* min-height: 100vh; */
            }

            .btn-gradient-1, .btn-gradient-2 {
                position: relative;
                display: inline-flex;
                justify-content: center;
                align-items: center;
                font-size: 1em;
                color: darkblue;
                padding: 0.5rem 2rem;
                cursor: pointer;
            }

            .custom-btn {
                /* width: 130px; */
                height: 50px;
                color: #fff;
                border-radius: 5px;
                padding: 10px 25px;
                font-family: 'Lato', sans-serif;
                font-weight: 500;
                background: transparent;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                display: inline-block;
                box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5),
                7px 7px 20px 0px rgba(0,0,0,.1),
                4px 4px 5px 0px rgba(0,0,0,.1);
                outline: none;
            }

            .btn-11 {
                border: 1px solid #8c68cd;
                background: #8c68cd;
                background: linear-gradient(to left, #b3b3e6, #6666cc, #4040bf);
                color: #fff;
                overflow: hidden;
                width: 44%;
                height: 10%;
                text-align: center;
            }
            .btn-11:hover {
                text-decoration: none;
                color: #fff;
            }
            .btn-11:before {
                position: absolute;
                content: '';
                display: inline-block;
                top: -180px;
                left: 0;
                width: 30px;
                height: 100%;
                background-color: #fff;
                animation: shiny-btn1 3s ease-in-out infinite;
            }
            .btn-11:hover{
                opacity: .7;
            }
            .btn-11:active{
                box-shadow:  4px 4px 6px 0 rgba(255,255,255,.3),
                                    -4px -4px 6px 0 rgba(116, 125, 136, .2), 
                            inset -4px -4px 6px 0 rgba(255,255,255,.2),
                            inset 4px 4px 6px 0 rgba(0, 0, 0, .2);
            }

            @-webkit-keyframes shiny-btn1 {
                0% { -webkit-transform: scale(0) rotate(45deg); opacity: 0; }
                80% { -webkit-transform: scale(0) rotate(45deg); opacity: 0.5; }
                81% { -webkit-transform: scale(4) rotate(45deg); opacity: 1; }
                100% { -webkit-transform: scale(50) rotate(45deg); opacity: 0; }
            }

            .custom-header {
                font-weight: bold;
                background-color: #5353c6;
                color: white;
                padding: 15px;
                border-radius: 8px;
                margin: 10px auto;
                width: 80%;
            }

            @media (min-width: 576px) {
                .custom-header {
                    width: 70%;
                }
            }

            @media (min-width: 768px) {
                .custom-header {
                    width: 60%;
                }
            }

            @media (min-width: 992px) {
                .custom-header {
                    width: 50%;
                }
            }

            @media (min-width: 1200px) {
                .custom-header {
                    width: 56%;
                }
            }
        </style>
    </head>

    <body>
        <section class="">
            <div class="container-flud">
                <div class="row">
                    <div class="bg-img col-lg-6 col-md-6 col-12 d-flex justify-content-center">
                        <img class="d-md-none d-lg-none d-xl-none d-sm-block d-block mt-4"
                            src="{{ asset('assets/images/logo/logo.png') }}"
                            style="width: 150px;">
                    </div>
                    <div
                        class="col-lg-6 col-md-6 col-12 d-md-none d-lg-none d-xl-none d-sm-block d-block mobile-view-bgcolor">
                        <img src="https://pmc-fire-management.creatorkart.com/admin/images/banner_new.jpg"
                            style="width: 100%" alt="">
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 mobile-view-bgcolor bg-white">
                        <div class="d-flex justify-content-center mt-3">
                            <img class="d-md-block d-lg-block d-xl-block d-sm-none d-none"
                                src="{{ asset('assets/images/logo/logo.png') }}"
                                style="width: 200px;">
                        </div>
                        <h2 class="text-center custom-header">मिरा-भाईंदर महानगरपालिका</h2><br>
                        <h3 class="text-center"><b>Attendance Management System</b></h3>
                        <h3 class="text-center"><b>( उपस्थिती विभाग प्रणाली )</b></h3>
                        <div class="container custompadding">
                            <div class="parent">
                                <a href="{{ route('login') }}" target="_blank" class="custom-btn btn-11">
                                    <b>Permanent Employees Login</b>
                                    <div class="dot"></div>
                                </a>
                                <a href="http://mbmcams.in/" target="_blank" class="custom-btn btn-11">
                                    <b>Contractual Employees Login</b>
                                    <div class="dot"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>

</html>