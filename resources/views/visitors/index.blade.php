@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content"><div class="container-fluid">
    <div class="mc-page-header"><div><h1 class="mc-page-title">Visitors</h1><p class="mc-page-subtitle">Riwayat meal untuk tamu/visitor yang menggunakan visitor_name.</p></div></div>
    <div class="mc-filterbar">
        <div class="mc-filter-field flex-grow"><label class="mc-filter-label">Search</label><input id="vSearch" class="form-control" placeholder="Visitor name / NIK"></div>
        <div class="mc-filter-field"><label class="mc-filter-label">Date</label><input id="vDate" type="date" class="form-control"></div>
        <button class="btn btn-primary" id="vApply"><i class="mdi mdi-filter-outline me-1"></i>Apply</button>
        <button class="btn btn-light" id="vReset">Reset</button>
    </div>
    <div class="mc-panel"><div class="mc-panel-head"><div><div class="mc-panel-title">Visitor Meal Activity</div><div class="mc-panel-subtitle" id="vMeta">Loading…</div></div></div>
        <div class="table-responsive"><table class="mc-table-modern"><thead><tr><th>Date / Time</th><th>Visitor</th><th>ID</th><th>Meal</th><th>Order Type</th><th>Qty</th><th>Location</th><th>Created By</th></tr></thead><tbody id="vBody"></tbody></table></div>
        <div class="p-3 d-flex justify-content-between align-items-center"><button class="btn btn-sm btn-light" id="vPrev">Previous</button><span class="text-muted" id="vPage" style="font-size:11px"></span><button class="btn btn-sm btn-light" id="vNext">Next</button></div>
    </div>
</div></div>
<script>
let vPage=1,vLast=1; const esc=v=>$('<div>').text(v??'').html();
function loadVisitors(){const q=new URLSearchParams({page:vPage,per_page:15,search:$('#vSearch').val(),date:$('#vDate').val()}); fetch(`{{ route('visitors.api') }}?${q}`).then(r=>r.json()).then(res=>{vLast=res.last_page||1; $('#vMeta').text(`${res.total||0} visitor records`); $('#vPage').text(`Page ${res.current_page||1} of ${vLast}`); $('#vPrev').prop('disabled',vPage<=1); $('#vNext').prop('disabled',vPage>=vLast); $('#vBody').html((res.data||[]).length?(res.data||[]).map(r=>`<tr><td>${esc(r.attendance_date)}<div class="text-muted" style="font-size:9px">${r.attendance_time?new Date(r.attendance_time).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}):''}</div></td><td><strong>${esc(r.visitor_name)}</strong></td><td>${esc(r.nik||'—')}</td><td><span class="mc-status orange">${esc(r.meal_type)}</span></td><td>${esc(r.order_type||'—')}</td><td><strong>${r.quantity}</strong></td><td>${esc(r.position||'—')}</td><td>${esc(r.created_by||'—')}</td></tr>`).join(''):'<tr><td colspan="8"><div class="mc-empty">No visitor data found.</div></td></tr>');});}
$('#vApply').click(()=>{vPage=1;loadVisitors()}); $('#vReset').click(()=>{$('#vSearch,#vDate').val('');vPage=1;loadVisitors()}); $('#vPrev').click(()=>{if(vPage>1){vPage--;loadVisitors()}}); $('#vNext').click(()=>{if(vPage<vLast){vPage++;loadVisitors()}}); loadVisitors();
</script>
@include('layout.footer')
