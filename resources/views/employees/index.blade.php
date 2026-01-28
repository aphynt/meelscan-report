@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')

<div class="content">
    <div class="container-fluid">

        <h4 class="mt-3">Employees</h4>

        <!-- FILTER -->
        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm">
                    <div class="row g-3 align-items-end">

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
                            <th>NIK</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Room</th>
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


    function loadAttendance(page = 1) {
        const perPage = document.getElementById('perPage').value;

        const params = new URLSearchParams({
            search: document.querySelector('[name="search"]').value,
            page: page,
            per_page: perPage
        });

        fetch(`{{ route('employees.api') }}?${params}`)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#datatable tbody');
                tbody.innerHTML = '';

                res.data.forEach((row, index) => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${(page - 1) * perPage + index + 1}</td>
                        <td>${row.nik}</td>
                        <td>${row.name}</td>
                        <td>${row.statusenabled}</td>
                        <td>${row.room}</td>
                    </tr>
                `;
                });


                renderPagination(res.meta);
            });
    }

    document.getElementById('perPage').addEventListener('change', () => loadAttendance(1));
    document.getElementById('filterForm').addEventListener('submit', e => {
        e.preventDefault();
        loadAttendance(1);
    });

    // auto search delay
    let typingTimer;
    document.querySelector('[name="search"]').addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => loadAttendance(1), 500);
    });

    loadAttendance();

</script>
