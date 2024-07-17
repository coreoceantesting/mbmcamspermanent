<header class="main-nav close_icon" >
    <nav class="h-100">
        <div class="main-navbar h-100">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav" class="h-100">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div class="">
                            <img class="img-50 rounded-circle" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('assets/images/dashboard/1.png') }}" alt="">
                            <strong style="color: #000; font-size: 18px">{{ ucfirst(auth()->user()->emp_code) }}</strong>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('employee.home') ? 'active-bg' : '' }}" href="{{ route('employee.home') }}">
                            <i data-feather="home"></i><span>Dashboard</span></a>
                    </li>


                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('employee.show-change-password') ? 'active-bg' : '' }}" href="{{ route('employee.show-change-password') }}">
                            <i data-feather="lock"></i><span>Change Password</span>
                        </a>
                    </li>


                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('privacy-policy') ? 'active-bg' : '' }}" href="{{ route('privacy-policy') }}">
                            <i data-feather="lock"></i><span>Privacy Policy</span>
                        </a>
                    </li>


                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('employee.logout') ? 'active-bg' : '' }}" onclick="event.preventDefault(); document.getElementById('side-logout-form').submit();" href="{{ route('employee.logout') }}">
                            <i data-feather="log-out"></i><span>Logout</span>
                        </a>
                        <form id="side-logout-form" action="{{ route('employee.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>


                    <li class="dropdown" style="position: absolute; bottom: 6%; background-color: #f1f1f1; padding: 15px">
                        <a class="nav-link menu-title link-nav" id="delete-account">
                            <i data-feather="user-x"></i><span>Delete Account</span>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>


    @push('scripts')
    <script>
        $("#delete-account").click(function(e) {
            e.preventDefault();
            swal({
                title: "Are you sure you want to permanently delete your account",
                icon: "info",
                buttons: ["Cancel", "Confirm"]
            })
            .then((justTransfer) =>
            {
                if (justTransfer)
                {
                    var url = "{{ route('employee.delete-account') }}";

                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (!data.error && !data.error2) {
                                swal("Success!", data.success, "success")
                                    .then((action) => {
                                        window.location.reload();
                                    });
                            } else {
                                if (data.error) {
                                    swal("Error!", data.error, "error");
                                } else {
                                    swal("Error!", data.error2, "error");
                                }
                            }
                        },
                        error: function(error, jqXHR, textStatus, errorThrown) {
                            swal("Error!", "Something went wrong", "error");
                        },
                    });
                }
            });
        });
    </script>
    @endpush
</header>
