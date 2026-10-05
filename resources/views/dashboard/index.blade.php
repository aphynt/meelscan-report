@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Meal Management Dashboard</h1>
            </div>
            <div class="mc-page-actions"><span class="mc-live-chip"><span class="mc-live-dot"></span> Live
                    overview</span><span class="mc-date-chip"><i class="mdi mdi-calendar-blank-outline"></i>
                    {{ now()->format('d M Y') }}</span><a
                    href="{{ route('consumptionData.export', ['period'=>now()->format('Y-m-d')]) }}"
                    class="btn btn-primary"><i class="mdi mdi-download-outline me-1"></i>Export</a></div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-2">
                <div class="card mc-kpi-card">
                    <div class="mc-kpi-top">
                        <div>
                            <div class="mc-kpi-label">Total Meals Today</div>
                            <div class="mc-kpi-value" id="kTotal">—</div>
                        </div>
                        <div class="mc-kpi-icon"><i class="mdi mdi-food-outline"></i></div>
                    </div>
                    <div class="mc-kpi-meta" id="kCompare">vs yesterday</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card mc-kpi-card mc-kpi-green">
                    <div class="mc-kpi-top">
                        <div>
                            <div class="mc-kpi-label">Breakfast</div>
                            <div class="mc-kpi-value" id="kBreakfast">—</div>
                        </div>
                        <div class="mc-kpi-icon"><i class="mdi mdi-coffee-outline"></i></div>
                    </div>
                    <div class="mc-kpi-meta" id="kBreakfastPct">—</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card mc-kpi-card mc-kpi-orange">
                    <div class="mc-kpi-top">
                        <div>
                            <div class="mc-kpi-label">Lunch</div>
                            <div class="mc-kpi-value" id="kLunch">—</div>
                        </div>
                        <div class="mc-kpi-icon"><i class="mdi mdi-silverware-fork-knife"></i></div>
                    </div>
                    <div class="mc-kpi-meta" id="kLunchPct">—</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card mc-kpi-card mc-kpi-cyan">
                    <div class="mc-kpi-top">
                        <div>
                            <div class="mc-kpi-label">Dinner</div>
                            <div class="mc-kpi-value" id="kDinner">—</div>
                        </div>
                        <div class="mc-kpi-icon"><i class="mdi mdi-weather-night"></i></div>
                    </div>
                    <div class="mc-kpi-meta" id="kDinnerPct">—</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card mc-kpi-card mc-kpi-purple">
                    <div class="mc-kpi-top">
                        <div>
                            <div class="mc-kpi-label">Average Rating</div>
                            <div class="mc-kpi-value" id="kRating">—</div>
                        </div>
                        <div class="mc-kpi-icon"><i class="mdi mdi-star-outline"></i></div>
                    </div>
                    <div class="mc-kpi-meta">Current month</div>
                </div>
            </div>
            <div class="col-6 col-xl-2"><a href="{{ route('monitoring.index') }}" class="text-decoration-none">
                    <div class="card mc-kpi-card" style="--accent:#dc2626">
                        <div class="mc-kpi-top">
                            <div>
                                <div class="mc-kpi-label">Alerts</div>
                                <div class="mc-kpi-value text-danger" id="kAlerts">—</div>
                            </div>
                            <div class="mc-kpi-icon" style="background:#fef2f2;color:#dc2626"><i
                                    class="mdi mdi-alert-outline"></i></div>
                        </div>
                        <div class="mc-kpi-meta">Needs review</div>
                    </div>
                </a></div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mc-card-heading">Consumption by Hour (Today)</h5>
                        <div class="mc-card-caption">Peak time & hourly consumption</div>
                    </div>
                    <div class="card-body">
                        <div id="hourChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mc-card-heading">Meal Proportion</h5>
                        <div class="mc-card-caption">Breakfast / Lunch / Dinner</div>
                    </div>
                    <div class="card-body">
                        <div id="mealDonut"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mc-card-heading">Order Type Distribution</h5>
                        <div class="mc-card-caption">Dine In / Take Away / Menu Sehat</div>
                    </div>
                    <div class="card-body">
                        <div id="orderDonut"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mc-card-heading">Meal Consumption Trend</h5>
                        <div class="mc-card-caption">Last 7 days</div>
                    </div>
                    <div class="card-body">
                        <div id="trendChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="mc-panel h-100">
                    <div class="mc-panel-head">
                        <div>
                            <div class="mc-panel-title">Today at a Glance</div>
                            <div class="mc-panel-subtitle">Quick operational insights</div>
                        </div>
                    </div>
                    <div class="mc-panel-body">
                        <div class="mc-mini-kpi">
                            <div class="mc-mini-kpi-icon"><i class="mdi mdi-clock-fast"></i></div>
                            <div>
                                <div class="mc-mini-kpi-label">Peak Hour</div>
                                <div class="mc-mini-kpi-value" id="iPeak">—</div>
                            </div>
                        </div>
                        <div class="mc-mini-kpi">
                            <div class="mc-mini-kpi-icon" style="background:#ecfdf3;color:#15803d"><i
                                    class="mdi mdi-food"></i></div>
                            <div>
                                <div class="mc-mini-kpi-label">Most Consumed Meal</div>
                                <div class="mc-mini-kpi-value" id="iMeal">—</div>
                            </div>
                        </div>
                        <div class="mc-mini-kpi">
                            <div class="mc-mini-kpi-icon" style="background:#fff7ed;color:#c2410c"><i
                                    class="mdi mdi-calendar-month-outline"></i></div>
                            <div>
                                <div class="mc-mini-kpi-label">This Month</div>
                                <div class="mc-mini-kpi-value" id="iMonth">—</div>
                            </div>
                        </div>
                        <div class="mc-mini-kpi">
                            <div class="mc-mini-kpi-icon" style="background:#f5f3ff;color:#7c3aed"><i
                                    class="mdi mdi-star-outline"></i></div>
                            <div>
                                <div class="mc-mini-kpi-label">Food Satisfaction</div>
                                <div class="mc-mini-kpi-value" id="iRating">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mc-grid-2 mb-3">
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Live Meal Activity</div>
                        <div class="mc-panel-subtitle">Latest transactions today</div>
                    </div><a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="mc-table-modern">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>NIK</th>
                                <th>Meal</th>
                                <th>Order Type</th>
                                <th>Qty</th>
                                <th>Face</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody id="liveBody"></tbody>
                    </table>
                </div>
            </div>
            <div>
                <div class="mc-panel mb-3">
                    <div class="mc-panel-head">
                        <div>
                            <div class="mc-panel-title">Attention Required</div>
                            <div class="mc-panel-subtitle">Potential verification & transaction anomalies</div>
                        </div><a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-light">Review</a>
                    </div>
                    <div class="mc-panel-body" id="alertBox"></div>
                </div>
                <div class="mc-panel">
                    <div class="mc-panel-head">
                        <div>
                            <div class="mc-panel-title">Recent Feedback / Rating</div>
                            <div class="mc-panel-subtitle">Rated meals today</div>
                        </div><a href="{{ route('analytics.rating') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="mc-table-modern">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>NIK</th>
                                    <th>Meal</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody id="ratingBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('admin/dist') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
    const nf = new Intl.NumberFormat('id-ID');
    const esc = v => $('<div>').text(v ?? '').html();
    const pct = (v, t) => t ? `${(v/t*100).toFixed(1)}%` : '0%';
    const fmtTime = v => v ? new Date(v).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    }) : '—';
    const fmtFace = v => v == null ? '—' : `${(Number(v)*100).toFixed(0)}%`;
    fetch(`{{ route('dashboard.api') }}`).then(r => {
        if (!r.ok) throw new Error('Dashboard API failed');
        return r.json()
    }).then(d => {
        const k = d.kpi.today,
            total = Number(k.total || 0);
        $('#kTotal').text(nf.format(total));
        $('#kBreakfast').text(nf.format(k.breakfast));
        $('#kLunch').text(nf.format(k.lunch));
        $('#kDinner').text(nf.format(k.dinner));
        $('#kRating').text(Number(d.rating || 0).toFixed(1));
        $('#kAlerts').text(d.alerts.total);
        $('#kBreakfastPct').text(`${pct(k.breakfast,total)} of today`);
        $('#kLunchPct').text(`${pct(k.lunch,total)} of today`);
        $('#kDinnerPct').text(`${pct(k.dinner,total)} of today`);
        const cmp = d.kpi.comparison.pct;
        $('#kCompare').html(cmp == null ? 'No yesterday baseline' :
            `${cmp>=0?'↑':'↓'} ${Math.abs(cmp)}% vs yesterday`);
        const peak = [...d.hourly].sort((a, b) => Number(b.total) - Number(a.total))[0];
        $('#iPeak').text(peak ? `${String(peak.hour).padStart(2,'0')}:00 · ${peak.total} portions` : 'No data');
        const meals = [
            ['Breakfast', k.breakfast],
            ['Lunch', k.lunch],
            ['Dinner', k.dinner]
        ].sort((a, b) => b[1] - a[1]);
        $('#iMeal').text(`${meals[0][0]} · ${nf.format(meals[0][1])}`);
        $('#iMonth').text(`${nf.format(d.kpi.month.total)} portions`);
        $('#iRating').text(`${Number(d.rating||0).toFixed(1)} / 5`);
        new ApexCharts(document.querySelector('#hourChart'), {
            chart: {
                type: 'bar',
                height: 295,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Portions',
                data: d.hourly.map(x => x.total)
            }],
            xaxis: {
                categories: d.hourly.map(x => `${String(x.hour).padStart(2,'0')}:00`)
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '55%'
                }
            },
            colors: ['#6366f1'],
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#edf1f5'
            }
        }).render();
        new ApexCharts(document.querySelector('#mealDonut'), {
            chart: {
                type: 'donut',
                height: 295
            },
            series: [k.breakfast, k.lunch, k.dinner],
            labels: ['Breakfast', 'Lunch', 'Dinner'],
            colors: ['#16a34a', '#ea580c', '#0891b2'],
            legend: {
                position: 'bottom',
                fontSize: '10px'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: () => nf.format(total)
                            }
                        }
                    }
                }
            }
        }).render();
        new ApexCharts(document.querySelector('#orderDonut'), {
            chart: {
                type: 'donut',
                height: 295
            },
            series: d.order_types.map(x => x.total),
            labels: d.order_types.map(x => x.name),
            colors: ['#2563eb', '#f59e0b', '#7c3aed', '#0891b2', '#16a34a'],
            legend: {
                position: 'bottom',
                fontSize: '9px'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            }
        }).render();
        new ApexCharts(document.querySelector('#trendChart'), {
            chart: {
                type: 'area',
                height: 310,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Breakfast',
                data: d.trend.map(x => x.breakfast)
            }, {
                name: 'Lunch',
                data: d.trend.map(x => x.lunch)
            }, {
                name: 'Dinner',
                data: d.trend.map(x => x.dinner)
            }],
            xaxis: {
                categories: d.trend.map(x => x.attendance_date)
            },
            stroke: {
                curve: 'smooth',
                width: 2.4
            },
            colors: ['#16a34a', '#ea580c', '#0891b2'],
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'top'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: .15,
                    opacityTo: .02
                }
            }
        }).render();
        $('#liveBody').html(d.live_activity.length ? d.live_activity.map(x =>
            `<tr><td>${fmtTime(x.attendance_time)}</td><td><strong>${esc(x.nik||'Visitor')}</strong></td><td><span class="mc-status orange">${esc(x.meal_type)}</span></td><td>${esc(x.order_type||'—')}</td><td>${x.quantity}</td><td><span class="mc-status ${Number(x.confidence_score??1)<.75?'red':'green'}">${fmtFace(x.confidence_score)}</span></td><td>${esc(x.position||'—')}</td></tr>`
            ).join('') : '<tr><td colspan="7"><div class="mc-empty">No activity today</div></td></tr>');
        const a = d.alerts;
        $('#alertBox').html(
            `<div class="mc-mini-kpi"><div class="mc-mini-kpi-icon" style="background:#fef2f2;color:#dc2626"><i class="mdi mdi-face-recognition"></i></div><div class="flex-grow-1"><div class="mc-mini-kpi-value">Low Confidence</div><div class="mc-mini-kpi-label">Face metrics below threshold</div></div><span class="mc-status red">${a.low_confidence.length}</span></div><div class="mc-mini-kpi"><div class="mc-mini-kpi-icon" style="background:#fff7ed;color:#c2410c"><i class="mdi mdi-content-duplicate"></i></div><div class="flex-grow-1"><div class="mc-mini-kpi-value">Duplicate Scan</div><div class="mc-mini-kpi-label">Same NIK + meal type</div></div><span class="mc-status orange">${a.duplicate_count}</span></div><div class="mc-mini-kpi"><div class="mc-mini-kpi-icon"><i class="mdi mdi-shield-alert-outline"></i></div><div class="flex-grow-1"><div class="mc-mini-kpi-value">Other Anomalies</div><div class="mc-mini-kpi-label">Non-real face / quantity</div></div><span class="mc-status red">${a.non_real_count+a.qty_anomaly_count}</span></div>`
            );
        $('#ratingBody').html(d.recent_ratings.length ? d.recent_ratings.map(x =>
            `<tr><td>${fmtTime(x.attendance_time)}</td><td><strong>${esc(x.nik)}</strong></td><td>${esc(x.meal_type)}</td><td><span class="mc-stars">★</span> ${x.rating}</td></tr>`
            ).join('') : '<tr><td colspan="4"><div class="mc-empty">No rating today</div></td></tr>');
    }).catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Dashboard data gagal dimuat',
            text: 'Periksa koneksi database atau field attendance_logs.'
        });
    });

</script>
@include('layout.footer')
