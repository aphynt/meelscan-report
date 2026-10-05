@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content"><div class="container-fluid">
    <div class="mc-page-header"><div><h1 class="mc-page-title">Consumption Analytics</h1><p class="mc-page-subtitle">Analisis volume konsumsi, unique people, rating, dan tren berdasarkan periode.</p></div></div>
    <div class="mc-filterbar">
        <div class="mc-filter-field"><label class="mc-filter-label">From</label><input id="aFrom" type="date" class="form-control" value="{{ now()->subDays(6)->format('Y-m-d') }}"></div>
        <div class="mc-filter-field"><label class="mc-filter-label">To</label><input id="aTo" type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"></div>
        <button class="btn btn-primary" id="aApply"><i class="mdi mdi-chart-box-outline me-1"></i>Analyze</button>
    </div>
    <div class="mc-stat-grid">
        <div class="mc-stat-card"><div class="mc-stat-label">Total Portions</div><div class="mc-stat-value" id="aTotal">—</div><div class="mc-stat-meta">Selected period</div></div>
        <div class="mc-stat-card is-cyan"><div class="mc-stat-label">Transactions</div><div class="mc-stat-value" id="aTrx">—</div><div class="mc-stat-meta">Attendance records</div></div>
        <div class="mc-stat-card is-success"><div class="mc-stat-label">Unique People</div><div class="mc-stat-value" id="aPeople">—</div><div class="mc-stat-meta">Unique NIK</div></div>
        <div class="mc-stat-card is-orange"><div class="mc-stat-label">Average Rating</div><div class="mc-stat-value" id="aRating">—</div><div class="mc-stat-meta" id="aRated">Rated transactions</div></div>
    </div>
    <div class="mc-grid-2 mb-3">
        <div class="mc-panel"><div class="mc-panel-head"><div><div class="mc-panel-title">Consumption Trend</div><div class="mc-panel-subtitle">Breakfast, lunch, dinner per day</div></div></div><div class="mc-panel-body"><div id="consTrend"></div></div></div>
        <div class="mc-panel"><div class="mc-panel-head"><div><div class="mc-panel-title">Meal Composition</div><div class="mc-panel-subtitle">Total portions by meal</div></div></div><div class="mc-panel-body"><div id="mealComposition"></div></div></div>
    </div>
    <div class="mc-panel"><div class="mc-panel-head"><div><div class="mc-panel-title">Order Type Utilization</div><div class="mc-panel-subtitle">Share of Dine In, Take Away, Menu Sehat, and other order types</div></div></div><div class="mc-panel-body" id="aOrderBars"></div></div>
</div></div>
<script src="{{ asset('admin/dist') }}/assets/libs/apexcharts/apexcharts.min.js"></script>
<script>
let ct,cm; const nf=new Intl.NumberFormat('id-ID'); const esc=v=>$('<div>').text(v??'').html();
function bars(rows){const total=(rows||[]).reduce((s,r)=>s+Number(r.total),0)||1;return (rows||[]).map(r=>`<div class="mc-progress-row"><div class="mc-progress-name">${esc(r.name)}</div><div class="mc-progress-track"><div class="mc-progress-fill" style="width:${Number(r.total)/total*100}%"></div></div><div class="mc-progress-value">${nf.format(r.total)}</div></div>`).join('')||'<div class="mc-empty">No data</div>';}
function loadA(){const q=new URLSearchParams({from:$('#aFrom').val(),to:$('#aTo').val()});fetch(`{{ route('analytics.api') }}?${q}`).then(r=>r.json()).then(d=>{ $('#aTotal').text(nf.format(d.summary.total));$('#aTrx').text(nf.format(d.summary.transactions));$('#aPeople').text(nf.format(d.summary.unique_people));$('#aRating').text(Number(d.summary.average_rating||0).toFixed(1));$('#aRated').text(`${d.summary.rated_count} rated transactions`);$('#aOrderBars').html(bars(d.order_types)); if(ct)ct.destroy(); if(cm)cm.destroy(); ct=new ApexCharts(document.querySelector('#consTrend'),{chart:{type:'area',height:310,toolbar:{show:false}},series:[{name:'Breakfast',data:d.daily.map(x=>x.breakfast)},{name:'Lunch',data:d.daily.map(x=>x.lunch)},{name:'Dinner',data:d.daily.map(x=>x.dinner)}],xaxis:{categories:d.daily.map(x=>x.date)},stroke:{curve:'smooth',width:2.5},colors:['#16a34a','#ea580c','#0891b2'],dataLabels:{enabled:false},fill:{type:'gradient',gradient:{opacityFrom:.18,opacityTo:.02}},legend:{position:'top'}});ct.render(); cm=new ApexCharts(document.querySelector('#mealComposition'),{chart:{type:'donut',height:310},series:d.meal_types.map(x=>x.total),labels:d.meal_types.map(x=>x.name),colors:['#16a34a','#ea580c','#0891b2'],legend:{position:'bottom'},dataLabels:{enabled:false},plotOptions:{pie:{donut:{size:'72%',labels:{show:true,total:{show:true,label:'Total',formatter:()=>nf.format(d.summary.total)}}}}}});cm.render();});}
$('#aApply').click(loadA);loadA();
</script>
@include('layout.footer')
