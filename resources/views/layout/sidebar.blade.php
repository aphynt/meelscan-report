<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="javascript:void(0);" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('admin/dist') }}/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('admin/dist') }}/assets/images/logo-light.png" alt="" height="24">
                    </span>
                </a>
                <a href="javascript:void(0);" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('admin/dist') }}/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo') }}/logo-full.png" alt="" height="30">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Home</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employees') }}" class="tp-link">
                        <i data-feather="users"></i>
                        <span>Employees</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('consumptionData') }}" class="tp-link">
                        <i data-feather="database"></i>
                        <span>Consumption Data</span>
                    </a>
                </li>
                <li class="menu-title">Report</li>
                <li>
                    <a href="#" class="tp-link">
                        <i data-feather="file-text"></i>
                        <span>Monthly Meals</span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<div class="content-page">
