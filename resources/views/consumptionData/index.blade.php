@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Consumption Data</h1>
                <p class="mc-page-subtitle">Monitor, filter, and review employee or visitor meal attendance records.</p>
            </div>
            <div class="mc-page-actions">
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAddManual">
                    <i data-feather="plus-circle"></i>
                    Add Manual
                </button>
                <button type="button" id="btnExport" class="btn btn-success">
                    <i data-feather="download"></i>
                    Export to Excel
                </button>
            </div>
        </div>

        @include('consumptionData.modal.addManual')

        <div class="card mc-filter-card mb-3">
            <div class="card-header mc-card-header-inline">
                <div class="mc-filter-title">
                    <span class="mc-filter-icon"><i data-feather="sliders"></i></span>
                    Filter Consumption
                </div>
                <span class="mc-card-caption">Default view displays today's data</span>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-6 col-xl-2">
                            <label class="form-label">Period</label>
                            <div class="mc-input-wrap">
                                <span class="mc-input-icon mdi mdi-calendar-range"></span>
                                <input type="text" class="form-control" id="rangecalendar-datepicker" name="period" placeholder="Select date range">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label">Meal Type</label>
                            <select class="form-select" name="category">
                                <option value="">All meals</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label">Order Type</label>
                            <select class="form-select" name="order_type_filter">
                                <option value="">All types</option>
                                <option value="Dine In">Dine In</option>
                                <option value="Take Away">Take Away</option>
                                <option value="Menu Sehat">Menu Sehat</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <label class="form-label">Added By</label>
                            <select class="form-select" name="created_by_filter">
                                <option value="">All sources</option>
                                <option value="system">System</option>
                                <option value="non_system">Non System</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-xl-2">
                            <label class="form-label">Search</label>
                            <div class="mc-input-wrap">
                                <span class="mc-input-icon mdi mdi-magnify"></span>
                                <input type="text" class="form-control" name="search" placeholder="NIK or name">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl-1 d-grid">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-filter-outline"></i> Filter</button>
                        </div>
                        <div class="col-6 col-md-3 col-xl-1 d-grid">
                            <button type="button" class="btn btn-light" id="btnResetFilter"><i class="mdi mdi-refresh"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mc-table-card">
            <div class="mc-table-toolbar">
                <div>
                    <div class="mc-card-heading">Attendance Records</div>
                    <div class="mc-card-caption" id="records-caption">Loading consumption records…</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="mc-card-caption d-none d-sm-inline">Rows</span>
                    <select id="perPage" class="form-select" style="width:82px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="table-loading-wrapper">
                <div id="tableLoading" class="table-loading-overlay">
                    <div class="table-loading-content">
                        <div class="table-loading-spinner"></div>
                        <div class="table-loading-text">Loading attendance data…</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle" id="datatable">
                        <thead>
                            <tr>
                                <th style="width:52px">No</th>
                                <th>Date & Time</th>
                                <th>Person</th>
                                <th>Meal</th>
                                <th>Qty</th>
                                <th>Face</th>
                                <th>Order Type</th>
                                <th>Food Category</th>
                                <th>Position</th>
                                <th>Rating</th>
                                <th>Added By</th>
                                <th>Photo</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="mc-pagination-wrap d-flex justify-content-between align-items-center">
                <div id="pagination-info" class="mc-card-caption"></div>
                <ul class="pagination mb-0" id="pagination"></ul>
            </div>
        </div>
    </div>
</div>

@include('consumptionData.modal.showPhoto')

<script>
    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function getInitials(name, nik) {
        const source = (name || nik || '?').trim();
        return source.split(/\s+/).slice(0, 2).map(x => x.charAt(0)).join('').toUpperCase();
    }

    function formatDateTime(datetime) {
        if (!datetime) return '<span class="text-muted">—</span>';
        const date = new Date(datetime);
        if (Number.isNaN(date.getTime())) return escapeHtml(datetime);
        return `
            <div class="mc-cell-primary">${date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</div>
            <div class="mc-cell-secondary">${date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
        `;
    }

    function badgeRealFace(value) {
        if (value === true || value === 1 || value === '1') return '<span class="mc-badge mc-badge-green"><i class="mdi mdi-check-circle-outline"></i> Real</span>';
        if (value === false || value === 0 || value === '0') return '<span class="mc-badge mc-badge-red"><i class="mdi mdi-alert-circle-outline"></i> Fake</span>';
        return '<span class="mc-badge mc-badge-slate">N/A</span>';
    }

    function mealBadge(meal) {
        const key = String(meal || '').toLowerCase();
        const cls = key === 'breakfast' ? 'mc-badge-green' : (key === 'lunch' ? 'mc-badge-orange' : (key === 'dinner' ? 'mc-badge-blue' : 'mc-badge-slate'));
        const label = key ? key.charAt(0).toUpperCase() + key.slice(1) : '—';
        return `<span class="mc-badge ${cls}">${escapeHtml(label)}</span>`;
    }

    function orderBadge(type) {
        const key = String(type || '').toLowerCase();
        const cls = key.includes('dine') ? 'mc-badge-blue' : (key.includes('take') ? 'mc-badge-orange' : (key.includes('sehat') ? 'mc-badge-green' : 'mc-badge-slate'));
        return `<span class="mc-badge ${cls}">${escapeHtml(type || '—')}</span>`;
    }

    function formatRating(rating) {
        const score = Number(rating || 0);
        if (!score) return '<span class="text-muted">—</span>';
        const labels = { 1: 'Very Poor', 2: 'Poor', 3: 'Fair', 4: 'Good', 5: 'Excellent' };
        return `<span class="mc-badge mc-badge-orange"><i class="mdi mdi-star"></i> ${score} · ${labels[score] || ''}</span>`;
    }

    function renderPagination(meta) {
        const pagination = document.getElementById('pagination');
        const info = document.getElementById('pagination-info');
        const caption = document.getElementById('records-caption');
        pagination.innerHTML = '';

        const startRow = meta.total ? ((meta.current_page - 1) * meta.per_page) + 1 : 0;
        const endRow = Math.min(meta.current_page * meta.per_page, meta.total);
        info.textContent = meta.total ? `Showing ${startRow}–${endRow} of ${meta.total} records` : 'No records found';
        caption.textContent = `${meta.total} active attendance record${meta.total === 1 ? '' : 's'} match the current filter`;

        if (meta.last_page <= 1) return;

        const current = meta.current_page;
        const last = meta.last_page;
        const delta = 2;
        const start = Math.max(1, current - delta);
        const end = Math.min(last, current + delta);

        pagination.insertAdjacentHTML('beforeend', `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current - 1})" aria-label="Previous"><i class="mdi mdi-chevron-left"></i></a>
            </li>`);

        if (start > 1) {
            pagination.insertAdjacentHTML('beforeend', `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadAttendance(1)">1</a></li>`);
            if (start > 2) pagination.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><span class="page-link">…</span></li>`);
        }

        for (let i = start; i <= end; i++) {
            pagination.insertAdjacentHTML('beforeend', `<li class="page-item ${i === current ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${i})">${i}</a></li>`);
        }

        if (end < last) {
            if (end < last - 1) pagination.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            pagination.insertAdjacentHTML('beforeend', `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${last})">${last}</a></li>`);
        }

        pagination.insertAdjacentHTML('beforeend', `
            <li class="page-item ${current === last ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current + 1})" aria-label="Next"><i class="mdi mdi-chevron-right"></i></a>
            </li>`);
    }

    function deleteConsumption(id) {
        const deleteConsumptionUrl = "{{ route('consumptionData.destroy', ':id') }}";
        const url = deleteConsumptionUrl.replace(':id', id);

        Swal.fire({
            title: 'Delete this record?',
            text: 'The record will be disabled and removed from this list.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({ title: 'Deleting…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to delete data');
                return res.json();
            })
            .then(res => {
                Swal.fire({ icon: 'success', title: 'Deleted', text: res.message ?? 'Data deleted successfully', timer: 1400, showConfirmButton: false });
                loadAttendance();
            })
            .catch(err => Swal.fire({ icon: 'error', title: 'Failed', text: err.message || 'An error occurred' }));
        });
    }

    function loadAttendance(page = 1) {
        const perPage = document.getElementById('perPage').value;
        const params = new URLSearchParams({
            period: document.querySelector('[name="period"]').value,
            category: document.querySelector('[name="category"]').value,
            order_type: document.querySelector('[name="order_type_filter"]').value,
            created_by: document.querySelector('[name="created_by_filter"]').value,
            search: document.querySelector('[name="search"]').value,
            page: page,
            per_page: perPage
        });

        const loading = document.getElementById('tableLoading');
        const pagination = document.getElementById('pagination');
        loading?.classList.add('active');
        if (pagination) { pagination.style.pointerEvents = 'none'; pagination.style.opacity = '.5'; }

        fetch(`/consumption-data/api?${params}`, { headers: { 'Accept': 'application/json' } })
            .then(res => {
                if (!res.ok) throw new Error('Failed to retrieve data from server');
                return res.json();
            })
            .then(res => {
                const tbody = document.querySelector('#datatable tbody');
                tbody.innerHTML = '';

                if (!res.data || res.data.length === 0) {
                    tbody.innerHTML = `
                        <tr><td colspan="13">
                            <div class="mc-empty-state">
                                <div class="mc-empty-icon"><i class="mdi mdi-database-search-outline"></i></div>
                                <div class="mc-cell-primary">No consumption data found</div>
                                <div class="mc-cell-secondary mt-1">Try changing the filters or date range.</div>
                            </div>
                        </td></tr>`;
                } else {
                    res.data.forEach((row, index) => {
                        const name = row.name || row.visitor_name || 'Unknown';
                        const nik = row.nik || '—';
                        tbody.insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="mc-table-index">${(page - 1) * perPage + index + 1}</td>
                                <td>${formatDateTime(row.attendance_time)}</td>
                                <td>
                                    <div class="mc-user-cell">
                                        <span class="mc-user-avatar">${escapeHtml(getInitials(name, nik))}</span>
                                        <div>
                                            <div class="mc-cell-primary">${escapeHtml(name)}</div>
                                            <div class="mc-cell-secondary">${escapeHtml(nik)}${row.attendance_type === 'visitor' ? ' · Visitor' : ''}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${mealBadge(row.meal_type)}</td>
                                <td><span class="mc-cell-primary">${escapeHtml(row.quantity ?? '—')}</span></td>
                                <td>${badgeRealFace(row.is_real_face)}</td>
                                <td>${orderBadge(row.order_type)}</td>
                                <td>${escapeHtml(row.food_category || '—')}</td>
                                <td>${escapeHtml(row.position || '—')}</td>
                                <td>${formatRating(row.rating)}</td>
                                <td>${escapeHtml(row.created_by || '—')}</td>
                                <td>
                                    <button type="button" class="btn mc-btn-soft-primary btn-sm btn-view-photo" data-id="${row.id}">
                                        <i class="mdi mdi-image-outline"></i> View
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-light mc-icon-btn" onclick="deleteConsumption(${row.id})" title="Delete record">
                                        <i class="mdi mdi-delete-outline text-danger"></i>
                                    </button>
                                </td>
                            </tr>`);
                    });
                }

                renderPagination(res.meta);
                if (typeof feather !== 'undefined') feather.replace();
            })
            .catch(error => {
                console.error('API Error:', error);
                document.querySelector('#datatable tbody').innerHTML = `
                    <tr><td colspan="13">
                        <div class="mc-empty-state">
                            <div class="mc-empty-icon" style="background:#fef2f2;color:#dc2626"><i class="mdi mdi-alert-circle-outline"></i></div>
                            <div class="mc-cell-primary">Unable to load data</div>
                            <div class="mc-cell-secondary mt-1">${escapeHtml(error.message || 'Please try again.')}</div>
                        </div>
                    </td></tr>`;
            })
            .finally(() => {
                loading?.classList.remove('active');
                if (pagination) { pagination.style.pointerEvents = ''; pagination.style.opacity = ''; }
            });
    }

    $(document).on('click', '.btn-view-photo', function () {
        const id = $(this).data('id');
        $('#photoNotFound').addClass('d-none');
        $('#modalPhoto').removeClass('d-none');
        $('#modalPhoto').off('error').on('error', function () {
            $(this).addClass('d-none');
            $('#photoNotFound').removeClass('d-none');
        });
        $('#modalPhoto').attr('src', `/consumption-data/photo/${id}`);
        $('#photoModal').modal('show');
    });

    document.getElementById('perPage').addEventListener('change', () => loadAttendance(1));
    document.getElementById('filterForm').addEventListener('submit', e => { e.preventDefault(); loadAttendance(1); });

    document.getElementById('btnResetFilter').addEventListener('click', () => {
        document.querySelector('[name="period"]').value = '';
        document.querySelector('[name="category"]').value = '';
        document.querySelector('[name="order_type_filter"]').value = '';
        document.querySelector('[name="created_by_filter"]').value = '';
        document.querySelector('[name="search"]').value = '';
        if (typeof flatpickr !== 'undefined') {
            const picker = document.querySelector('#rangecalendar-datepicker')._flatpickr;
            if (picker) picker.clear();
        }
        loadAttendance(1);
    });

    // Export route and query parameters intentionally preserve the existing export workflow.
    document.getElementById('btnExport').addEventListener('click', function () {
        const params = new URLSearchParams({
            period: document.querySelector('[name="period"]').value,
            category: document.querySelector('[name="category"]').value,
            order_type: document.querySelector('[name="order_type_filter"]').value,
            created_by: document.querySelector('[name="created_by_filter"]').value,
            search: document.querySelector('[name="search"]').value
        });
        window.location.href = `{{ route('consumptionData.export') }}?${params}`;
    });

    let typingTimer;
    document.querySelector('[name="search"]').addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => loadAttendance(1), 500);
    });

    loadAttendance();
</script>

@include('layout.footer')
