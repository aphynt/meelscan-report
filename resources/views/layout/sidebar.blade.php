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
                    <a href="javascript:void(0)" class="tp-link dev-feature">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleksi semua elemen dengan class dev-feature
        const devFeatures = document.querySelectorAll('.dev-feature');

        devFeatures.forEach(feature => {
            feature.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah link pindah halaman

                Swal.fire({
                    title: 'Under Development',
                    text: 'Fitur "Monthly Meals" sedang dalam tahap pengembangan.',
                    icon: 'info',
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#6366f1', // Warna indigo sesuai tema dashboard Anda
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        });
    });
</script>
