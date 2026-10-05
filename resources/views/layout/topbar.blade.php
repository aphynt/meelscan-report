<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center gap-2">
                <li>
                    <button class="button-toggle-menu nav-link" type="button" aria-label="Toggle sidebar">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-sm-block">
                    <div class="mc-greeting-kicker">Meal Management System</div>
                    <div class="mc-greeting" id="greeting">Welcome, {{ Auth::user()->name }}</div>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center gap-1">
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link mc-topbar-icon" data-toggle="fullscreen" title="Fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user mc-profile-trigger d-flex align-items-center" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="mc-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="mc-profile-name">{{ Auth::user()->name }}</span>
                        <i class="mdi mdi-chevron-down ms-1 text-muted"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <div class="px-2 pt-1 pb-2">
                            <div class="small fw-semibold text-dark">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size:11px">Signed in to {{ config('app.name') }}</div>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item text-danger">
                            <i class="mdi mdi-logout fs-16 align-middle me-1"></i>
                            <span>Keluar</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    (function () {
        const hour = new Date().getHours();
        let greeting = 'Hello';

        if (hour >= 5 && hour < 11) greeting = 'Good Morning';
        else if (hour >= 11 && hour < 15) greeting = 'Good Afternoon';
        else if (hour >= 15 && hour < 19) greeting = 'Good Evening';
        else greeting = 'Good Night';

        const userName = @json(auth()->user()->name);
        const el = document.getElementById('greeting');
        if (el) el.textContent = `${greeting}, ${userName}`;
    })();
</script>
