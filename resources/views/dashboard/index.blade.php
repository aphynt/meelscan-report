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
                            <h3 class="mb-0 text-info" id="kpi-total-today">0</h3>
                        </div>
                        <div class="avatar bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-food text-info fs-22"></i>
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
                            <h3 class="mb-0" id="kpi-breakfast" style="color: rgb(34,197,94)">0</h3>
                        </div>
                        <div class="avatar bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-coffee fs-22"></i>
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
                            <h3 class="mb-0 text-success" id="kpi-dinner">0</h3>
                        </div>
                        <div class="avatar bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-food-steak text-success fs-22"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Today's Peak Hours</h5>
                    </div>
                    <div class="card-body">
                        <div id="hourlyChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meal Proportion (Today)</h5>
                    </div>
                    <div class="card-body">
                        <div id="sessionDonutChart"></div>
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



            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Average Food Rating</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="text-warning mb-1" id="avg-rating-text">0.0</h2>
                        <p class="mb-2 text-muted">Out of 5</p>
                        <div class="fs-18" id="avg-rating-stars"></div>
                    </div>
                </div>
            </div>

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

            const hourlyLabels = res.hourly.map(i => i.hour + ":00");
            const hourlyData   = res.hourly.map(i => i.total);

            hourlyChart = new ApexCharts(document.querySelector("#hourlyChart"), {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                series: [{ name: 'Total Porsi', data: hourlyData }],
                xaxis: { categories: hourlyLabels },
                colors: ['#6366f1'], // Indigo color
                title: { text: 'Consumption by Hour', align: 'left', style: { color: '#666' } }
            });
            hourlyChart.render();

            // 3. SESSION PROPORTION CHART (Donut Chart Baru)
            donutChart = new ApexCharts(document.querySelector("#sessionDonutChart"), {
                chart: { type: 'donut', height: 320 },
                series: [res.kpi.today.breakfast, res.kpi.today.lunch, res.kpi.today.dinner],
                labels: ['Breakfast', 'Lunch', 'Dinner'],
                colors: ['#22c55e', '#f59e0b', '#0ea5e9'],
                legend: { position: 'bottom' },
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 200 }, legend: { position: 'bottom' } }
                }]
            });
            donutChart.render();
        });
</script>
