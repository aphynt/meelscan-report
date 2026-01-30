@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
            <h4 class="mb-0">Consumption Data</h4>

            <div class="d-flex gap-2">
                <a href="javascript:void(0)"
                    class="btn btn-warning d-flex align-items-center gap-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddManual">
                        <i data-feather="plus-circle"></i>
                        Add Manual
                </a>
                @include('consumptionData.modal.addManual')

                <a href="javascript:void(0)"
                    id="btnExport"
                    class="btn btn-success d-flex align-items-center gap-1">
                        <i data-feather="file-text"></i>
                        Export to Excel
                </a>
            </div>
        </div>
        <!-- FILTER -->
        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Period</label>
                            <input type="text" class="form-control" id="rangecalendar-datepicker" name="period" placeholder="Range Date">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Food Category</label>
                            <select name="category" class="form-select">
                                <option value="">All</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Search (NIK / Name)</label>
                            <input type="text" name="search" class="form-control" placeholder="Type NIK or Name...">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('consumptionData') }}" class="btn btn-light w-100">Reset</a>
                        </div>


                    </div>
                </form>
            </div>
        </div>

        <!-- TABLE -->
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

                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date & Time</th>
                            <th>NIK</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Food Category</th>
                            <th>Position</th>
                            <th>Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="pagination-info" class="text-muted"></div>
                    <ul class="pagination mb-0" id="pagination"></ul>
                </div>

            </div>
        </div>

    </div>
</div>

@include('layout.footer')

<script>
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

    function capitalizeFirst(text) {
        return text ? text.charAt(0).toUpperCase() + text.slice(1) : '-';
    }

    function formatRating(rating) {
        switch (Number(rating)) {
            case 1:
                return 'Sangat Tidak Enak';
            case 2:
                return 'Tidak Enak';
            case 3:
                return 'Cukup';
            case 4:
                return 'Enak';
            case 5:
                return 'Sangat Enak';
            default:
                return '-';
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
        const delta = 2; // jumlah page kiri-kanan

        let start = Math.max(1, current - delta);
        let end   = Math.min(last, current + delta);

        // Prev
        pagination.innerHTML += `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${current - 1})">Prev</a>
            </li>
        `;

        // First + dots
        if (start > 1) {
            pagination.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(1)">1</a>
                </li>
            `;
            if (start > 2) {
                pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            }
        }

        // Middle pages
        for (let i = start; i <= end; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${i})">${i}</a>
                </li>
            `;
        }

        // Last + dots
        if (end < last) {
            if (end < last - 1) {
                pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            }
            pagination.innerHTML += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" onclick="loadAttendance(${last})">${last}</a>
                </li>
            `;
        }

        // Next
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
                didOpen: () => {
                    Swal.showLoading();
                }
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
            search: document.querySelector('[name="search"]').value,
            page: page,
            per_page: perPage
        });

        fetch(`/consumption-data/api?${params}`)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#datatable tbody');
                tbody.innerHTML = '';

                res.data.forEach((row, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${(page - 1) * perPage + index + 1}</td>
                            <td>${formatDateTime(row.attendance_time)}</td>
                            <td>${row.nik}</td>
                            <td>${row.name ?? '-'}</td>
                            <td>${capitalizeFirst(row.meal_type)}</td>
                            <td>${row.quantity}</td>
                            <td>${capitalizeFirst(row.food_category)}</td>
                            <td>${row.position}</td>
                            <td>${formatRating(row.rating)}</td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm bg-danger-subtle"
                                    onclick="deleteConsumption(${row.id})"
                                    title="Delete">
                                    <i class="mdi mdi-delete fs-14 text-danger"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                });

                feather.replace();
                renderPagination(res.meta);
            });
    }

    document.getElementById('perPage').addEventListener('change', () => loadAttendance(1));
    document.getElementById('filterForm').addEventListener('submit', e => {
        e.preventDefault();
        loadAttendance(1);
    });

    document.getElementById('btnExport').addEventListener('click', function () {
        const params = new URLSearchParams({
            period: document.querySelector('[name="period"]').value,
            category: document.querySelector('[name="category"]').value,
            search: document.querySelector('[name="search"]').value,
        });

        window.location.href = `{{ route('consumptionData.export') }}?${params}`;
    });

    // auto search delay
    let typingTimer;
    document.querySelector('[name="search"]').addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => loadAttendance(1), 500);
    });

    loadAttendance();

</script>
