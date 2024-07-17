<div class="page-main-header close_icon">
    <div class="main-header-right row m-0">
        <div class="main-header-left w-100">
            <div class="logo-wrapper"><a href="#"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt="" style="width: 50px"></a> <strong
                    style="font-size: 18px">{{ strtoupper(auth()->user()->tenant_name) }}</strong> </div>
            <div class="dark-logo-wrapper"><a href="#"><img class="img-fluid" src="{{ asset('assets/images/logo/dark-logo.png') }}" alt="" style="width: 50px"></a> <strong
                    style="font-size: 18px">{{ strtoupper(auth()->user()->tenant_name) }}</strong> </div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i></div>
        </div>
    </div>
</div>
