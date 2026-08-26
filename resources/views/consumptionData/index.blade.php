@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<style>
    .table-loading-wrapper {
        position: relative;
    }
    .table-loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.75);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        min-height: 200px;
        border-radius: 4px;
        backdrop-filter: blur(1px);
    }
    .table-loading-overlay.active {
        display: flex;
    }
    .table-loading-content {
        text-align: center;
    }
    .table-loading-spinner {
        width: 42px;
        height: 42px;
        border: 4px solid #e9ecef;
        border-top-color: #0d6efd;
        border-radius: 50%;
        animation: tableSpin .8s linear infinite;
        margin: 0 auto 10px;
    }
    .table-loading-text {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }
    @keyframes tableSpin {
        to {
            transform: rotate(360deg);
        }
    }
    .table-loading-wrapper table {
        margin-bottom: 0;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <h4 class="mb-0">Consumption Data</h4>
            <div class="d-flex gap-2">
                <a href="javascript:void(0)" class="btn btn-warning d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAddManual">
                    <i data-feather="plus-circle"></i>
                    Add Manual
                </a>
                @include('consumptionData.modal.addManual')
                <a href="javascript:void(0)" id="btnExport" class="btn btn-success d-flex align-items-center gap-1">
                    <i data-feather="file-text"></i>
                    Export to Excel
                </a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Period</label>
                            <input type="text" class="form-control" id="rangecalendar-datepicker" name="period" placeholder="Range Date">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Order Type</label>
                            <select class="form-select" name="order_type_filter">
                                <option value="">All</option>
                                <option value="Dine In">Dine In</option>
                                <option value="Take Away">Take Away</option>
                                <option value="Menu Sehat">Menu Sehat</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Add By</label>
                            <select class="form-select" name="created_by_filter">
                                <option value="">All</option>
                                <option value="system">System</option>
                                <option value="non_system">Non System</option>
                            </select>
                        </div>
                       <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" placeholder="Search NIK / Name...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-filter-outline"></i>
                                    Filter
                                </button>
                                <button type="button" class="btn btn-light" id="btnResetFilter">
                                    <i class="mdi mdi-refresh"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Attendance Summary</h5>
                <select id="perPage" class="form-select w-auto">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="card-body">
                <div class="table-loading-wrapper">
                    <div id="tableLoading" class="table-loading-overlay">
                        <div class="table-loading-content">
                            <div class="table-loading-spinner"></div>
                            <div class="table-loading-text">Memuat data...</div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date & Time</th>
                                    <th>NIK</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Face</th>
                                    <th>Order Type</th>
                                    <th>Food Category</th>
                                    <th>Position</th>
                                    <th>Rating</th>
                                    <th>Add By</th>
                                    <th>Documentation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="pagination-info" class="text-muted"></div>
                    <ul class="pagination mb-0" id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('consumptionData.modal.showPhoto')
@include('layout.footer')

<script>
$(document).on('click', '.btn-view-photo', function() {
    let id = $(this).data('id');
    $('#photoNotFound').addClass('d-none');
    $('#modalPhoto').removeClass('d-none');
    $('#modalPhoto').off('error').on('error', function() {
        $(this).addClass('d-none');
        $('#photoNotFound').removeClass('d-none');
    });
    $('#modalPhoto').attr('src', `/consumption-data/photo/${id}`);
    $('#photoModal').modal('show');
});

function formatDateTime(datetime) {
    if (!datetime) return '-';
    return new Date(datetime).toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function badgeRealFace(value) {
    if (value === null || value === undefined) {
        return '<span class="badge bg-secondary">N/A</span>';
    }
    if (value === true || value === 1 || value === "1") {
        return '<span class="badge bg-success">Real</span>';
    }
    if (value === false || value === 0 || value === "0") {
        return '<span class="badge bg-danger">Fake</span>';
    }
    return '<span class="badge bg-secondary">N/A</span>';
}

function capitalizeFirst(text) {
    return text ? text.charAt(0).toUpperCase() + text.slice(1) : '-';
}

function formatRating(rating) {
    switch (Number(rating)) {
        case 1: return 'Sangat Tidak Enak';
        case 2: return 'Tidak Enak';
        case 3: return 'Cukup';
        case 4: return 'Enak';
        case 5: return 'Sangat Enak';
        default: return '-';
    }
}

function renderPagination(meta) {
    const pagination = document.getElementById('pagination');
    const info = document.getElementById('pagination-info');

    pagination.innerHTML = '';
    info.innerText = `Page ${meta.current_page} of ${meta.last_page} · Total ${meta.total} data`;

    if (meta.last_page <= 1) return;

    const current = meta.current_page;
    const last = meta.last_page;
    const delta = 2;
    let start = Math.max(1, current - delta);
    let end = Math.min(last, current + delta);

    pagination.innerHTML += `
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current - 1})">Prev</a>
        </li>
    `;

    if (start > 1) {
        pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(1)">1</a>
            </li>
        `;

        if (start > 2) {
            pagination.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link">…</span>
                </li>
            `;
        }
    }

    for (let i = start; i <= end; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === current ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${i})">${i}</a>
            </li>
        `;
    }

    if (end < last) {
        if (end < last - 1) {
            pagination.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link">…</span>
                </li>
            `;
        }

        pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${last})">${last}</a>
            </li>
        `;
    }

    pagination.innerHTML += `
        <li class="page-item ${current === last ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current + 1})">Next</a>
        </li>
    `;
}

function deleteConsumption(id) {
    const deleteConsumptionUrl = "{{ route('consumptionData.destroy', ':id') }}";
    const url = deleteConsumptionUrl.replace(':id', id);

    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Gagal menghapus data');
            return res.json();
        })
        .then(res => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message ?? 'Data berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
            });
            loadAttendance();
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: err.message || 'Terjadi kesalahan'
            });
        });
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

    if (loading) loading.classList.add('active');
    if (pagination) {
        pagination.style.pointerEvents = 'none';
        pagination.style.opacity = '0.5';
    }

    fetch(`/consumption-data/api?${params}`)
        .then(res => {
            if (!res.ok) throw new Error('Gagal mengambil data dari server');
            return res.json();
        })
        .then(res => {
            const tbody = document.querySelector('#datatable tbody');
            tbody.innerHTML = '';

            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="14" class="text-center text-muted py-4">
                            <div class="py-3">
                                <i class="mdi mdi-database-off-outline fs-3"></i>
                                <div class="mt-2">Tidak ada data</div>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                res.data.forEach((row, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${(page - 1) * perPage + index + 1}</td>
                            <td>${formatDateTime(row.attendance_time)}</td>
                            <td>${row.nik ?? '-'}</td>
                            <td>${row.name ?? '-'}</td>
                            <td>${capitalizeFirst(row.meal_type)}</td>
                            <td>${row.quantity ?? '-'}</td>
                            <td>${badgeRealFace(row.is_real_face)}</td>
                            <td>${capitalizeFirst(row.order_type)}</td>
                            <td>${capitalizeFirst(row.food_category)}</td>
                            <td>${row.position ?? '-'}</td>
                            <td>${formatRating(row.rating)}</td>
                            <td>${capitalizeFirst(row.created_by)}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-view-photo" data-id="${row.id}">
                                    <i class="mdi mdi-image-outline"></i>
                                    Show Photo
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm bg-danger-subtle" onclick="deleteConsumption(${row.id})" title="Delete">
                                    <i class="mdi mdi-delete fs-14 text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            renderPagination(res.meta);
        })
        .catch(error => {
            console.error('API Error:', error);
            const tbody = document.querySelector('#datatable tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="14" class="text-center py-4">
                        <div class="text-danger">
                            <i class="mdi mdi-alert-circle-outline fs-3"></i>
                            <div class="mt-2">Gagal memuat data</div>
                            <small class="text-muted">${error.message || 'Silakan coba lagi.'}</small>
                        </div>
                    </td>
                </tr>
            `;
        })
        .finally(() => {
            if (loading) loading.classList.remove('active');
            if (pagination) {
                pagination.style.pointerEvents = '';
                pagination.style.opacity = '';
            }
        });
}

document.getElementById('perPage').addEventListener('change', () => loadAttendance(1));

document.getElementById('filterForm').addEventListener('submit', e => {
    e.preventDefault();
    loadAttendance(1);
});

document.getElementById('btnResetFilter').addEventListener('click', () => {
    document.querySelector('[name="period"]').value = '';
    document.querySelector('[name="category"]').value = '';
    document.querySelector('[name="order_type_filter"]').value = '';
    document.querySelector('[name="created_by_filter"]').value = '';
    document.querySelector('[name="search"]').value = '';

    if (typeof flatpickr !== 'undefined') {
        const periodPicker = document.querySelector('#rangecalendar-datepicker')._flatpickr;
        if (periodPicker) periodPicker.clear();
    }

    loadAttendance(1);
});

document.getElementById('btnExport').addEventListener('click', function() {
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
