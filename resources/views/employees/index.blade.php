@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Employees</h1>
                <p class="mc-page-subtitle">Browse active employees and manage Healthy Menu eligibility.</p>
            </div>
            <div class="mc-page-actions">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#healthyModal">
                    <i class="mdi mdi-food-apple-outline"></i>
                    Healthy Menu
                </button>
            </div>
        </div>

        <div class="card mc-filter-card mb-3">
            <div class="card-header mc-card-header-inline">
                <div class="mc-filter-title">
                    <span class="mc-filter-icon"><i data-feather="search"></i></span>
                    Find Employee
                </div>
                <span class="mc-card-caption">Search by NIK or employee name</span>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-7 col-xl-5">
                            <label class="form-label">Search</label>
                            <div class="mc-input-wrap">
                                <span class="mc-input-icon mdi mdi-magnify"></span>
                                <input type="text" name="search" class="form-control" placeholder="Type NIK or employee name…">
                            </div>
                        </div>
                        <div class="col-6 col-md-auto d-grid">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-filter-outline"></i> Filter</button>
                        </div>
                        <div class="col-6 col-md-auto d-grid">
                            <button class="btn btn-light" type="button" id="btnResetEmployees"><i class="mdi mdi-refresh"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('employees.modal.healthy')

        <div class="card mc-table-card">
            <div class="mc-table-toolbar">
                <div>
                    <div class="mc-card-heading">Employee Directory</div>
                    <div class="mc-card-caption" id="employee-caption">Loading employees…</div>
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
                <div id="employeeLoading" class="table-loading-overlay">
                    <div class="table-loading-content">
                        <div class="table-loading-spinner"></div>
                        <div class="table-loading-text">Loading employee data…</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle" id="datatable">
                        <thead>
                            <tr>
                                <th style="width:60px">No</th>
                                <th>Employee</th>
                                <th>NIK</th>
                                <th>Status</th>
                                <th>Room</th>
                                <th>Healthy Menu</th>
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

<script>
    function empEscape(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function empInitials(name, nik) {
        const source = (name || nik || '?').trim();
        return source.split(/\s+/).slice(0, 2).map(x => x.charAt(0)).join('').toUpperCase();
    }

    function renderPagination(meta) {
        const pagination = document.getElementById('pagination');
        const info = document.getElementById('pagination-info');
        const caption = document.getElementById('employee-caption');
        pagination.innerHTML = '';

        const startRow = meta.total ? ((meta.current_page - 1) * meta.per_page) + 1 : 0;
        const endRow = Math.min(meta.current_page * meta.per_page, meta.total);
        info.textContent = meta.total ? `Showing ${startRow}–${endRow} of ${meta.total} employees` : 'No employee found';
        caption.textContent = `${meta.total} active employee${meta.total === 1 ? '' : 's'} match the current search`;

        if (meta.last_page <= 1) return;

        const current = meta.current_page;
        const last = meta.last_page;
        const delta = 2;
        const start = Math.max(1, current - delta);
        const end = Math.min(last, current + delta);

        pagination.insertAdjacentHTML('beforeend', `<li class="page-item ${current === 1 ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current - 1})"><i class="mdi mdi-chevron-left"></i></a></li>`);

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

        pagination.insertAdjacentHTML('beforeend', `<li class="page-item ${current === last ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current + 1})"><i class="mdi mdi-chevron-right"></i></a></li>`);
    }

    function loadAttendance(page = 1) {
        const perPage = document.getElementById('perPage').value;
        const params = new URLSearchParams({
            search: document.querySelector('[name="search"]').value,
            page,
            per_page: perPage
        });
        const loading = document.getElementById('employeeLoading');
        loading?.classList.add('active');

        fetch(`{{ route('employees.api') }}?${params}`, { headers: { 'Accept': 'application/json' } })
            .then(res => {
                if (!res.ok) throw new Error('Failed to load employees');
                return res.json();
            })
            .then(res => {
                const tbody = document.querySelector('#datatable tbody');
                tbody.innerHTML = '';

                if (!res.data || !res.data.length) {
                    tbody.innerHTML = `<tr><td colspan="6"><div class="mc-empty-state"><div class="mc-empty-icon"><i class="mdi mdi-account-search-outline"></i></div><div class="mc-cell-primary">No employee found</div><div class="mc-cell-secondary mt-1">Try a different NIK or name.</div></div></td></tr>`;
                } else {
                    res.data.forEach((row, index) => {
                        const healthy = Number(row.healthy) === 1;
                        tbody.insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="mc-table-index">${(page - 1) * perPage + index + 1}</td>
                                <td>
                                    <div class="mc-user-cell">
                                        <span class="mc-user-avatar">${empEscape(empInitials(row.name, row.nik))}</span>
                                        <div>
                                            <div class="mc-cell-primary">${empEscape(row.name || 'Unknown')}</div>
                                            <div class="mc-cell-secondary">Employee</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="mc-cell-primary">${empEscape(row.nik || '—')}</span></td>
                                <td><span class="mc-badge ${String(row.statusenabled).toLowerCase() === 'active' ? 'mc-badge-green' : 'mc-badge-slate'}"><i class="mdi mdi-circle-medium"></i> ${empEscape(row.statusenabled || '—')}</span></td>
                                <td>${empEscape(row.room || '—')}</td>
                                <td>${healthy ? '<span class="mc-badge mc-badge-green"><i class="mdi mdi-food-apple-outline"></i> Registered</span>' : '<span class="mc-badge mc-badge-slate">Standard</span>'}</td>
                            </tr>`);
                    });
                }
                renderPagination(res.meta);
            })
            .catch(error => {
                console.error(error);
                document.querySelector('#datatable tbody').innerHTML = `<tr><td colspan="6"><div class="mc-empty-state"><div class="mc-empty-icon" style="background:#fef2f2;color:#dc2626"><i class="mdi mdi-alert-circle-outline"></i></div><div class="mc-cell-primary">Unable to load employees</div><div class="mc-cell-secondary mt-1">${empEscape(error.message)}</div></div></td></tr>`;
            })
            .finally(() => loading?.classList.remove('active'));
    }

    document.getElementById('perPage').addEventListener('change', () => loadAttendance(1));
    document.getElementById('filterForm').addEventListener('submit', e => { e.preventDefault(); loadAttendance(1); });
    document.getElementById('btnResetEmployees').addEventListener('click', () => {
        document.querySelector('[name="search"]').value = '';
        loadAttendance(1);
    });

    const healthyModal = document.getElementById('healthyModal');

    $('input[name="input_type"]').change(function () {
        const employeeMode = $(this).val() === 'employee';
        $('#employeeSection').toggle(employeeMode);
        $('#manualSection').toggle(!employeeMode);
    });

    healthyModal.addEventListener('shown.bs.modal', function () {
        $('input[value="employee"]').prop('checked', true);
        $('#employeeSection').show();
        $('#manualSection').hide();
        $('#employee_name, #employee_additional, #manual_nik, #manual_name, #manual_additional').val('');
        $('#employee_id').val(null).trigger('change');
        loadHealthyMenu();
    });

    $('#employee_id').select2({
        dropdownParent: $('#healthyModal'),
        width: '100%',
        placeholder: 'Search NIK / Name',
        ajax: {
            url: "{{ route('employees.search') }}",
            dataType: 'json',
            delay: 300,
            data: params => ({ q: params.term }),
            processResults: data => ({
                results: data.map(item => ({ id: item.nik, text: item.nik + ' - ' + item.name, name: item.name }))
            })
        }
    });

    $('#employee_id').on('select2:select', function (e) {
        $('#employee_name').val(e.params.data.name);
    });

    $('#btnSaveHealthy').click(function () {
        const type = $('input[name=input_type]:checked').val();
        const payload = { type };

        if (type === 'employee') {
            const nik = $('#employee_id').val();
            if (!nik) {
                Swal.fire('Select Employee', 'Please select an employee first.', 'warning');
                return;
            }
            payload.nik = nik;
            payload.additional = $('#employee_additional').val().trim();
        } else {
            const nik = $('#manual_nik').val().trim();
            const name = $('#manual_name').val().trim();
            if (!nik || !name) {
                Swal.fire('Required Fields', 'NIK and Name are required.', 'warning');
                return;
            }
            payload.nik = nik;
            payload.name = name;
            payload.additional = $('#manual_additional').val().trim();
        }

        fetch("{{ route('employees.setHealthy') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to save Healthy Menu data');
            return data;
        })
        .then(res => {
            Swal.fire({ icon: 'success', title: 'Saved', text: res.message, timer: 1400, showConfirmButton: false });
            $('#employee_id').val(null).trigger('change');
            $('#employee_name, #employee_additional, #manual_nik, #manual_name, #manual_additional').val('');
            loadHealthyMenu();
            loadAttendance();
        })
        .catch(error => Swal.fire('Failed', error.message, 'error'));
    });

    function loadHealthyMenu() {
        const target = document.getElementById('healthyTable');
        target.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">Loading…</td></tr>`;

        fetch("{{ route('employees.healthy') }}", { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(rows => {
                if (!rows.length) {
                    target.innerHTML = `<tr><td colspan="5"><div class="mc-empty-state py-4"><div class="mc-cell-primary">No Healthy Menu employees yet</div><div class="mc-cell-secondary mt-1">Add an employee using the form above.</div></div></td></tr>`;
                    return;
                }

                target.innerHTML = rows.map((row, index) => `
                    <tr>
                        <td class="mc-table-index">${index + 1}</td>
                        <td><span class="mc-cell-primary">${empEscape(row.nik)}</span></td>
                        <td>${empEscape(row.name)}</td>
                        <td>${empEscape(row.additional || '—')}</td>
                        <td class="text-center"><button type="button" class="btn btn-light mc-icon-btn" onclick="removeHealthy('${String(row.nik).replaceAll("'", "\\'")}')" title="Remove"><i class="mdi mdi-delete-outline text-danger"></i></button></td>
                    </tr>`).join('');
            });
    }

    function removeHealthy(nik) {
        Swal.fire({
            title: 'Remove from Healthy Menu?',
            text: 'This employee will return to the standard meal category.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remove',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc2626'
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch("{{ route('employees.removeHealthy') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ nik })
            })
            .then(res => res.json())
            .then(res => {
                Swal.fire({ icon: 'success', title: 'Removed', text: res.message, timer: 1300, showConfirmButton: false });
                loadHealthyMenu();
                loadAttendance();
            });
        });
    }

    let typingTimer;
    document.querySelector('[name="search"]').addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => loadAttendance(1), 500);
    });

    loadAttendance();
</script>

@include('layout.footer')
