<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <h5 class="mb-0" id="greeting"></h5>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">

                        <span class="pro-user-name ms-1">
                            {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Selamat Datang!</h6>
                        </div>


                        <!-- item -->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                            <span>Keluar</span>
                        </a>

                    </div>
                </li>

            </ul>
        </div>

    </div>

</div>
<script>
    const hour = new Date().getHours();
    let greeting = "Hello";

    if (hour >= 5 && hour < 11) {
        greeting = "Good Morning";
    } else if (hour >= 11 && hour < 14) {
        greeting = "Good Afternoon";
    } else if (hour >= 14 && hour < 18) {
        greeting = "Good Evening";
    } else {
        greeting = "Good Night";
    }

    const userName = @json(auth()->user()->name);

    document.getElementById("greeting").innerText =
        `${greeting}, ${userName}`;
</script>
