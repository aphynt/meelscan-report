@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Peak Hours</h1>
                <p class="mc-page-subtitle">Identifikasi jam layanan paling padat</p>
            </div>
        </div>
        <div class="mc-filterbar">
            <div class="mc-filter-field"><label class="mc-filter-label">From</label><input id="pFrom" type="date"
                    class="form-control" value="{{ now()->subDays(6)->format('Y-m-d') }}"></div>
            <div class="mc-filter-field"><label class="mc-filter-label">To</label><input id="pTo" type="date"
                    class="form-control" value="{{ now()->format('Y-m-d') }}"></div><button id="pApply"
                class="btn btn-primary">Refresh</button>
        </div>
        <div class="mc-grid-2">
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Consumption by Hour</div>
                        <div class="mc-panel-subtitle">Accumulated portions in selected period</div>
                    </div>
                </div>
                <div class="mc-panel-body">
                    <div id="peakChart"></div>
                </div>
            </div>
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Top Peak Windows</div>
                        <div class="mc-panel-subtitle">Highest hourly consumption</div>
                    </div>
                </div>
                <div class="mc-panel-body" id="peakRank"></div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('admin/dist') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
    let pc;
    const nf = new Intl.NumberFormat('id-ID');

    function loadP() {
        const q = new URLSearchParams({
            from: $('#pFrom').val(),
            to: $('#pTo').val()
        });
        fetch(`{{ route('analytics.api') }}?${q}`).then(r => r.json()).then(d => {
            const rows = [...d.hourly].sort((a, b) => Number(b.total) - Number(a.total));
            const max = Number(rows[0] ?.total || 1);
            $('#peakRank').html(rows.slice(0, 8).map((r, i) =>
                `<div class="mc-progress-row"><div class="mc-progress-name"><span class="mc-rank me-2">${i+1}</span>${r.hour}</div><div class="mc-progress-track"><div class="mc-progress-fill" style="width:${Number(r.total)/max*100}%"></div></div><div class="mc-progress-value">${nf.format(r.total)}</div></div>`
                ).join('') || '<div class="mc-empty">No data</div>');
            if (pc) pc.destroy();
            pc = new ApexCharts(document.querySelector('#peakChart'), {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Portions',
                    data: d.hourly.map(x => x.total)
                }],
                xaxis: {
                    categories: d.hourly.map(x => x.hour)
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '55%'
                    }
                },
                colors: ['#2563eb'],
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#edf1f5'
                },
                tooltip: {
                    y: {
                        formatter: v => `${nf.format(v)} portions`
                    }
                }
            });
            pc.render();
        });
    }
    $('#pApply').click(loadP);
    loadP();

</script>
@include('layout.footer')
