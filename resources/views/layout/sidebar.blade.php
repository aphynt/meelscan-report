<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="mc-sidebar-logo" aria-label="SIMS Jaya Kaltim">
                    <span class="mc-sidebar-mark">SIMS</span>
                    <img src="{{ asset('logo/logo-full.png') }}" alt="SIMS Jaya Kaltim">
                </a>
            </div>

            <ul id="side-menu">
                <li class="menu-title">Overview</li>
                <li class="{{ request()->routeIs('dashboard*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="tp-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                        <i data-feather="grid"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('monitoring*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('monitoring.index') }}" class="tp-link {{ request()->routeIs('monitoring*') ? 'active' : '' }}">
                        <i data-feather="activity"></i><span>Live Monitoring</span><span class="mc-menu-live"></span>
                    </a>
                </li>

                <li class="menu-title">Meal Management</li>
                <li class="{{ request()->routeIs('consumptionData*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('consumptionData') }}" class="tp-link {{ request()->routeIs('consumptionData*') ? 'active' : '' }}">
                        <i data-feather="database"></i><span>Meal Transactions</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('employees*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('employees') }}" class="tp-link {{ request()->routeIs('employees*') ? 'active' : '' }}">
                        <i data-feather="users"></i><span>Employees</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('visitors*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('visitors.index') }}" class="tp-link {{ request()->routeIs('visitors*') ? 'active' : '' }}">
                        <i data-feather="user-plus"></i><span>Visitors</span>
                    </a>
                </li>

                <li class="menu-title">Analytics</li>
                <li class="{{ request()->routeIs('analytics.consumption') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('analytics.consumption') }}" class="tp-link {{ request()->routeIs('analytics.consumption') ? 'active' : '' }}">
                        <i data-feather="bar-chart-2"></i><span>Consumption Analytics</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('analytics.peakHours') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('analytics.peakHours') }}" class="tp-link {{ request()->routeIs('analytics.peakHours') ? 'active' : '' }}">
                        <i data-feather="clock"></i><span>Peak Hours</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('analytics.orderType') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('analytics.orderType') }}" class="tp-link {{ request()->routeIs('analytics.orderType') ? 'active' : '' }}">
                        <i data-feather="pie-chart"></i><span>Order Type Analysis</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('analytics.rating') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('analytics.rating') }}" class="tp-link {{ request()->routeIs('analytics.rating') ? 'active' : '' }}">
                        <i data-feather="star"></i><span>Rating & Feedback</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('analytics.faceVerification') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('analytics.faceVerification') }}" class="tp-link {{ request()->routeIs('analytics.faceVerification') ? 'active' : '' }}">
                        <i data-feather="check-circle"></i><span>Face Verification</span>
                    </a>
                </li>

                <li class="menu-title">Reports</li>
                <li class="{{ request()->routeIs('reports.daily') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('reports.daily') }}" class="tp-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}">
                        <i data-feather="file-text"></i><span>Daily Report</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('reports.monthly') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('reports.monthly') }}" class="tp-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                        <i data-feather="calendar"></i><span>Monthly Report</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('reports.employeeHistory') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('reports.employeeHistory') }}" class="tp-link {{ request()->routeIs('reports.employeeHistory') ? 'active' : '' }}">
                        <i data-feather="user-check"></i><span>Employee History</span>
                    </a>
                </li>

                <li class="menu-title">Master Data</li>
                <li class="{{ request()->routeIs('master.mealPlan*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('master.mealPlan') }}" class="tp-link {{ request()->routeIs('master.mealPlan*') ? 'active' : '' }}">
                        <i data-feather="clipboard"></i><span>Meal Plan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('master.foodCategory') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('master.foodCategory') }}" class="tp-link {{ request()->routeIs('master.foodCategory') ? 'active' : '' }}">
                        <i data-feather="tag"></i><span>Food Category</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('master.messLocation') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('master.messLocation') }}" class="tp-link {{ request()->routeIs('master.messLocation') ? 'active' : '' }}">
                        <i data-feather="map-pin"></i><span>Mess Location</span>
                    </a>
                </li>

                {{-- <li class="menu-title">System</li>
                <li class="{{ request()->routeIs('system.settings*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('system.settings') }}" class="tp-link {{ request()->routeIs('system.settings*') ? 'active' : '' }}">
                        <i data-feather="settings"></i><span>Settings</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('system.users') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('system.users') }}" class="tp-link {{ request()->routeIs('system.users') ? 'active' : '' }}">
                        <i data-feather="shield"></i><span>User Management</span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="content-page">
