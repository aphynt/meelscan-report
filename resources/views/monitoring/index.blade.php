@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Live Monitoring</h1>
                <p class="mc-page-subtitle">Pantau transaksi meal, face verification, duplicate scan, dan anomali secara
                    cepat.</p>
            </div>
            <div class="mc-page-actions">
                <span class="mc-live-chip"><span class="mc-live-dot"></span> Auto refresh 15s</span>
                <input id="monitorDate" class="form-control" type="date" value="{{ now()->format('Y-m-d') }}"
                    style="width:155px;min-height:36px;border-radius:10px;font-size:11px">
            </div>
        </div>

        <div class="mc-stat-grid">
            <div class="mc-stat-card">
                <div class="mc-stat-label">Total Portions</div>
                <div class="mc-stat-value" id="statTotal">—</div>
                <div class="mc-stat-meta">Selected day</div>
            </div>
            <div class="mc-stat-card is-cyan">
                <div class="mc-stat-label">Transactions</div>
                <div class="mc-stat-value" id="statTransactions">—</div>
                <div class="mc-stat-meta">Attendance records</div>
            </div>
            <div class="mc-stat-card is-success">
                <div class="mc-stat-label">Last 15 Minutes</div>
                <div class="mc-stat-value" id="statRecent">—</div>
                <div class="mc-stat-meta">Realtime intake</div>
            </div>
            <div class="mc-stat-card is-danger">
                <div class="mc-stat-label">Attention Required</div>
                <div class="mc-stat-value" id="statAlerts">—</div>
                <div class="mc-stat-meta">Potential anomaly</div>
            </div>
        </div>

        <div class="mc-grid-2 mb-3">
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Live Meal Activity</div>
                        <div class="mc-panel-subtitle">Latest attendance records</div>
                    </div><span class="mc-soft-chip">Latest 25</span>
                </div>
                <div class="table-responsive">
                    <table class="mc-table-modern">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>NIK / Visitor</th>
                                <th>Meal</th>
                                <th>Order Type</th>
                                <th>Qty</th>
                                <th>Face</th>
                                <th>Location</th>
                                <th>Photo</th>
                            </tr>
                        </thead>
                        <tbody id="activityBody">
                            <tr>
                                <td colspan="8">
                                    <div class="mc-empty"><i class="mdi mdi-loading mdi-spin"></i>Loading data…</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Attention Required</div>
                        <div class="mc-panel-subtitle">Rule-based monitoring dari data face & transaksi</div>
                    </div>
                </div>
                <div class="mc-panel-body">
                    <div class="mc-alert-tabs">
                        <button class="mc-alert-tab active" data-alert="low_confidence">Low Confidence <span
                                id="cntLow">0</span></button>
                        <button class="mc-alert-tab" data-alert="duplicate">Duplicate <span
                                id="cntDup">0</span></button>
                        <button class="mc-alert-tab" data-alert="non_real_face">Non Real Face <span
                                id="cntFake">0</span></button>
                        <button class="mc-alert-tab" data-alert="qty_anomaly">Qty Anomaly <span
                                id="cntQty">0</span></button>
                    </div>
                    <div id="alertContent"></div>
                </div>
            </div>
        </div>

        <div class="mc-grid-3">
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Meal Type</div>
                        <div class="mc-panel-subtitle">Today's composition</div>
                    </div>
                </div>
                <div class="mc-panel-body" id="mealBars"></div>
            </div>
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Order Type</div>
                        <div class="mc-panel-subtitle">Dine In / Take Away / Menu Sehat</div>
                    </div>
                </div>
                <div class="mc-panel-body" id="orderBars"></div>
            </div>
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Verification Rules</div>
                        <div class="mc-panel-subtitle">Current default threshold</div>
                    </div>
                </div>
                <div class="mc-panel-body">
                    <div class="mc-mini-kpi">
                        <div class="mc-mini-kpi-icon"><i class="mdi mdi-face-recognition"></i></div>
                        <div>
                            <div class="mc-mini-kpi-label">Similarity Threshold</div>
                            <div class="mc-mini-kpi-value" id="simThreshold">50%</div>
                        </div>
                    </div>
                    <div class="mc-mini-kpi">
                        <div class="mc-mini-kpi-icon"><i class="mdi mdi-shield-check-outline"></i></div>
                        <div>
                            <div class="mc-mini-kpi-label">Confidence Threshold</div>
                            <div class="mc-mini-kpi-value" id="confThreshold">50%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('consumptionData.modal.showPhoto')
<script>
    let monitorPayload = null;
    let activeAlert = 'low_confidence';
    const esc = v => $('<div>').text(v ?? '').html();
    const fmtPct = v => v == null ? '—' : `${(Number(v)*100).toFixed(1)}%`;
    const fmtTime = v => v ? new Date(v).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    }) : '—';

    function badgeMeal(meal) {
        const cls = meal === 'breakfast' ? 'green' : meal === 'lunch' ? 'orange' : 'purple';
        return `<span class="mc-status ${cls}">${esc(meal || '—')}</span>`;
    }

    function faceBadge(row) {
        const ok = Number(row.is_real_face ?? 1) === 1 && Number(row.confidence_score ?? 1) >= .50;
        return `<span class="mc-status ${ok?'green':'red'}">${fmtPct(row.confidence_score)}</span>`;
    }

    function photo(row) {
        if (!row.photo_path) {
            return '—';
        }

        return `
            <button type="button"
                class="btn btn-sm btn-light"
                onclick="openPhotoModal(${Number(row.id)})"
                title="View Photo">
                <i class="mdi mdi-image-outline"></i>
            </button>
        `;
    }

    function openPhotoModal(id) {
        const modalEl = document.getElementById('photoModal');
        const modalPhoto = document.getElementById('modalPhoto');
        const photoNotFound = document.getElementById('photoNotFound');

        const photoUrl = `{{ url('/consumption-data/photo') }}/${id}`;

        modalPhoto.classList.add('d-none');
        photoNotFound.classList.add('d-none');
        modalPhoto.removeAttribute('src');

        modalPhoto.onload = function () {
            modalPhoto.classList.remove('d-none');
            photoNotFound.classList.add('d-none');
        };

        modalPhoto.onerror = function () {
            modalPhoto.classList.add('d-none');
            photoNotFound.classList.remove('d-none');
        };

        modalPhoto.src = photoUrl;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    function progressRows(obj) {
        const entries = Object.entries(obj || {});
        const total = entries.reduce((s, [, v]) => s + Number(v), 0) || 1;
        if (!entries.length) return '<div class="mc-empty"><i class="mdi mdi-chart-box-outline"></i>No data</div>';
        return entries.map(([name, val]) =>
            `<div class="mc-progress-row"><div class="mc-progress-name">${esc(name)}</div><div class="mc-progress-track"><div class="mc-progress-fill" style="width:${Number(val)/total*100}%"></div></div><div class="mc-progress-value">${val}</div></div>`
            ).join('');
    }

    function renderAlerts() {
        if (!monitorPayload) return;
        const rows = monitorPayload.alerts[activeAlert] || [];
        const el = document.getElementById('alertContent');
        if (!rows.length) {
            el.innerHTML =
                '<div class="mc-empty"><i class="mdi mdi-check-circle-outline"></i><div>No issue detected</div></div>';
            return;
        }
        if (activeAlert === 'duplicate') {
            el.innerHTML = rows.map(r =>
                `<div class="mc-mini-kpi"><div class="mc-mini-kpi-icon" style="background:#fff7ed;color:#c2410c"><i class="mdi mdi-content-duplicate"></i></div><div class="flex-grow-1"><div class="mc-mini-kpi-value">${esc(r.nik)} · ${esc(r.meal_type)}</div><div class="mc-mini-kpi-label">${r.count} records · latest ${fmtTime(r.latest_time)}</div></div><span class="mc-status orange">Review</span></div>`
                ).join('');
            return;
        }
        el.innerHTML = rows.map(r =>
            `<div class="mc-mini-kpi"><div class="mc-mini-kpi-icon" style="background:#fef2f2;color:#dc2626"><i class="mdi mdi-alert-circle-outline"></i></div><div class="flex-grow-1"><div class="mc-mini-kpi-value">${esc(r.nik || r.visitor_name || 'Unknown')} · ${fmtTime(r.attendance_time)}</div><div class="mc-mini-kpi-label">Similarity ${fmtPct(r.similarity_score)} · Confidence ${fmtPct(r.confidence_score)} · Qty ${r.quantity ?? 0}</div></div>${photo(r)}</div>`
            ).join('');
    }

    function render(payload) {
        monitorPayload = payload;
        $('#statTotal').text(payload.summary.total);
        $('#statTransactions').text(payload.summary.transactions);
        $('#statRecent').text(payload.summary.last_15_minutes);
        $('#statAlerts').text(payload.summary.alerts);
        $('#cntLow').text(payload.alerts.low_confidence.length);
        $('#cntDup').text(payload.alerts.duplicate.length);
        $('#cntFake').text(payload.alerts.non_real_face.length);
        $('#cntQty').text(payload.alerts.qty_anomaly.length);
        $('#simThreshold').text(fmtPct(payload.thresholds.similarity));
        $('#confThreshold').text(fmtPct(payload.thresholds.confidence));
        $('#mealBars').html(progressRows(payload.meal_types));
        $('#orderBars').html(progressRows(payload.order_types));
        const rows = payload.activity || [];
        $('#activityBody').html(rows.length ? rows.map(r =>
                `<tr><td>${fmtTime(r.attendance_time)}</td><td><strong>${esc(r.nik || 'Visitor')}</strong><div class="text-muted" style="font-size:9px">${esc(r.visitor_name || '')}</div></td><td>${badgeMeal(r.meal_type)}</td><td>${esc(r.order_type || '—')}</td><td><strong>${r.quantity ?? 0}</strong></td><td>${faceBadge(r)}</td><td>${esc(r.position || '—')}</td><td>${photo(r)}</td></tr>`
                ).join('') :
            '<tr><td colspan="8"><div class="mc-empty"><i class="mdi mdi-tray-remove"></i>No activity on selected date</div></td></tr>'
            );
        renderAlerts();
    }

    function loadMonitoring() {
        const date = $('#monitorDate').val();
        fetch(`{{ route('monitoring.api') }}?date=${encodeURIComponent(date)}`, {
            headers: {
                Accept: 'application/json'
            }
        }).then(r => r.json()).then(render).catch(() => $('#activityBody').html(
            '<tr><td colspan="8"><div class="mc-empty">Unable to load monitoring data.</div></td></tr>'));
    }
    $('.mc-alert-tab').on('click', function () {
        $('.mc-alert-tab').removeClass('active');
        $(this).addClass('active');
        activeAlert = this.dataset.alert;
        renderAlerts();
    });
    $('#monitorDate').on('change', loadMonitoring);
    loadMonitoring();
    setInterval(() => {
        if ($('#monitorDate').val() === '{{ now()->format('Y-m-d') }}') loadMonitoring();
    }, 15000);

</script>
@include('layout.footer')
