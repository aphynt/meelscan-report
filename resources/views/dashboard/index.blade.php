@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">

        <!-- PAGE TITLE -->
        <div class="py-3 d-flex align-items-center">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold mb-0">Meelcount Dashboard</h4>
                <small class="text-muted">Food Consumption & Attendance Overview</small>
            </div>
        </div>

        <!-- KPI SUMMARY -->
        <div class="row">

            <!-- TOTAL -->
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Consumption Today</p>
                            <h3 class="mb-0 text-primary" id="kpi-total-today">0</h3>
                        </div>
                        <div class="avatar bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-food text-primary fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BREAKFAST -->
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Breakfast</p>
                            <h3 class="mb-0 text-success" id="kpi-breakfast">0</h3>
                        </div>
                        <div class="avatar bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-coffee text-success fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LUNCH -->
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Lunch</p>
                            <h3 class="mb-0 text-warning" id="kpi-lunch">0</h3>
                        </div>
                        <div class="avatar bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-silverware-fork-knife text-warning fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DINNER -->
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Dinner</p>
                            <h3 class="mb-0 text-info" id="kpi-dinner">0</h3>
                        </div>
                        <div class="avatar bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-food-steak text-info fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- CHART -->
        <div class="row">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meal Consumption Trend (Last 7 Days)</h5>
                    </div>
                    <div class="card-body">
                        <div id="mealTrendChart"></div>
                    </div>
                </div>
            </div>

            <!-- RATING -->
            {{-- <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Average Food Rating</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-warning mb-1">4.6</h2>
                        <p class="mb-2 text-muted">Out of 5</p>
                        <div class="fs-18">⭐⭐⭐⭐☆</div>
                    </div>
                </div>
            </div> --}}

        </div>

    </div>
</div>
<!-- Apexcharts JS -->
        <script src="{{ asset('admin/dist') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
@include('layout.footer')

<!-- APEXCHART -->
<script>
    let mealChart;

    fetch("{{ route('dashboard.api') }}")
        .then(res => res.json())
        .then(res => {

            // ===== KPI =====
            document.getElementById('kpi-total-today').innerText = res.kpi.today.total;
            document.getElementById('kpi-breakfast').innerText   = res.kpi.today.breakfast;
            document.getElementById('kpi-lunch').innerText       = res.kpi.today.lunch;
            document.getElementById('kpi-dinner').innerText      = res.kpi.today.dinner;

            // ===== CHART DATA =====
            const labels    = res.trend.map(i => i.attendance_date);
            const breakfast = res.trend.map(i => i.breakfast);
            const lunch     = res.trend.map(i => i.lunch);
            const dinner    = res.trend.map(i => i.dinner);

            if (mealChart) {
                mealChart.destroy();
            }

            mealChart = new ApexCharts(
                document.querySelector("#mealTrendChart"),
                {
                    chart: {
                        type: 'line',
                        height: 320,
                        toolbar: { show: false }
                    },
                    series: [
                        { name: 'Breakfast', data: breakfast },
                        { name: 'Lunch', data: lunch },
                        { name: 'Dinner', data: dinner },
                    ],
                    xaxis: {
                        categories: labels
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#22c55e', '#f59e0b', '#0ea5e9'],
                    markers: {
                        size: 4
                    },
                    legend: {
                        position: 'top'
                    }
                }
            );

            mealChart.render();
        });
</script>
