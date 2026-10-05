@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Face Verification</h1>
                <p class="mc-page-subtitle">Review similarity, confidence, real-face status, dan foto attendance.</p>
            </div>
        </div>
        <div class="mc-filterbar">
            <div class="mc-filter-field"><label class="mc-filter-label">From</label><input id="fFrom" type="date"
                    class="form-control" value="{{ now()->subDays(6)->format('Y-m-d') }}"></div>
            <div class="mc-filter-field"><label class="mc-filter-label">To</label><input id="fTo" type="date"
                    class="form-control" value="{{ now()->format('Y-m-d') }}"></div><button id="fApply"
                class="btn btn-primary">Refresh</button>
        </div>
        <div class="mc-stat-grid">
            <div class="mc-stat-card">
                <div class="mc-stat-label">Verified Records</div>
                <div class="mc-stat-value" id="fTotal">—</div>
                <div class="mc-stat-meta">Records with face metrics</div>
            </div>
            <div class="mc-stat-card is-danger">
                <div class="mc-stat-label">Low Confidence</div>
                <div class="mc-stat-value" id="fConf">—</div>
                <div class="mc-stat-meta">Confidence &lt; 50%</div>
            </div>
            <div class="mc-stat-card is-orange">
                <div class="mc-stat-label">Low Similarity</div>
                <div class="mc-stat-value" id="fSim">—</div>
                <div class="mc-stat-meta">Similarity &lt; 50%</div>
            </div>
            <div class="mc-stat-card is-danger">
                <div class="mc-stat-label">Non Real Face</div>
                <div class="mc-stat-value" id="fFake">—</div>
                <div class="mc-stat-meta">is_real_face = 0</div>
            </div>
        </div>
        <div class="mc-panel">
            <div class="mc-panel-head">
                <div>
                    <div class="mc-panel-title">Verification Log</div>
                    <div class="mc-panel-subtitle">Latest 100 records</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="mc-table-modern">
                    <thead>
                        <tr>
                            <th>Date / Time</th>
                            <th>NIK</th>
                            <th>Meal</th>
                            <th>Similarity</th>
                            <th>Confidence</th>
                            <th>Real Face</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody id="fBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('consumptionData.modal.showPhoto')
<script>
    const esc = v => $('<div>').text(v ?? '').html();

    const pct = v => {
        return v == null
            ? '—'
            : `${(Number(v) * 100).toFixed(1)}%`;
    };

    function openPhotoModal(id) {

        const modalEl = document.getElementById('photoModal');
        const modalPhoto = document.getElementById('modalPhoto');
        const photoNotFound = document.getElementById('photoNotFound');

        if (!modalEl) {
            console.error('Modal #photoModal tidak ditemukan.');
            return;
        }

        if (!modalPhoto) {
            console.error('Image #modalPhoto tidak ditemukan.');
            return;
        }

        const photoUrl =
            `{{ url('/consumption-data/photo') }}/${id}`;

        /*
         * Reset modal
         */
        modalPhoto.classList.add('d-none');

        if (photoNotFound) {
            photoNotFound.classList.add('d-none');
        }

        modalPhoto.onload = function() {

            modalPhoto.classList.remove('d-none');

            if (photoNotFound) {
                photoNotFound.classList.add('d-none');
            }
        };

        modalPhoto.onerror = function() {

            modalPhoto.classList.add('d-none');

            if (photoNotFound) {
                photoNotFound.classList.remove('d-none');
            }
        };

        modalPhoto.src = photoUrl;

        /*
         * Support Bootstrap 5 maupun Bootstrap 4
         */
        if (
            typeof bootstrap !== 'undefined' &&
            bootstrap.Modal
        ) {

            bootstrap.Modal
                .getOrCreateInstance(modalEl)
                .show();

        } else if (
            typeof $ !== 'undefined' &&
            typeof $.fn.modal !== 'undefined'
        ) {

            $('#photoModal').modal('show');

        } else {

            console.error(
                'Bootstrap modal tidak ditemukan.'
            );
        }
    }

    function loadF() {

        const q = new URLSearchParams({
            from: $('#fFrom').val(),
            to: $('#fTo').val()
        });

        fetch(`{{ route('analytics.api') }}?${q}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {

            if (!response.ok) {
                throw new Error(
                    `HTTP ${response.status}`
                );
            }

            return response.json();
        })
        .then(d => {

            $('#fTotal').text(
                d.face_summary?.total ?? 0
            );

            $('#fConf').text(
                d.face_summary?.low_confidence ?? 0
            );

            $('#fSim').text(
                d.face_summary?.low_similarity ?? 0
            );

            $('#fFake').text(
                d.face_summary?.non_real ?? 0
            );

            const rows = d.face_rows || [];

            if (!rows.length) {

                $('#fBody').html(`
                    <tr>
                        <td colspan="7">
                            <div class="mc-empty">
                                No face verification data
                            </div>
                        </td>
                    </tr>
                `);

                return;
            }

            const html = rows.map(x => {

                const confidence =
                    x.confidence_score != null
                        ? Number(x.confidence_score)
                        : null;

                const similarity =
                    x.similarity_score != null
                        ? Number(x.similarity_score)
                        : null;

                const isRealFace =
                    Number(x.is_real_face ?? 1);

                const risky =
                    (confidence !== null && confidence < 0.50) ||
                    (similarity !== null && similarity < 0.50) ||
                    isRealFace === 0;

                const time =
                    x.attendance_time
                        ? new Date(
                            x.attendance_time
                        ).toLocaleTimeString(
                            'id-ID',
                            {
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        )
                        : '';

                const similarityClass =
                    similarity !== null &&
                    similarity < 0.50
                        ? 'red'
                        : 'green';

                const confidenceClass =
                    confidence !== null &&
                    confidence < 0.50
                        ? 'red'
                        : 'green';

                const faceClass =
                    isRealFace === 1
                        ? 'green'
                        : 'red';

                const faceText =
                    isRealFace === 1
                        ? 'Real'
                        : 'Review';

                const photoButton =
                    x.photo_path
                        ? `
                            <button
                                type="button"
                                class="btn btn-sm ${
                                    risky
                                        ? 'btn-outline-danger'
                                        : 'btn-light'
                                }"
                                onclick="openPhotoModal(${Number(x.id)})"
                                title="View Photo"
                            >
                                <i class="mdi mdi-image-outline"></i>
                            </button>
                        `
                        : '—';

                return `
                    <tr>

                        <td>
                            ${esc(x.attendance_date)}

                            <div
                                class="text-muted"
                                style="font-size:9px"
                            >
                                ${time}
                            </div>
                        </td>

                        <td>
                            <strong>
                                ${esc(x.nik)}
                            </strong>
                        </td>

                        <td>
                            ${esc(x.meal_type)}
                        </td>

                        <td>
                            <span
                                class="mc-status ${similarityClass}"
                            >
                                ${pct(similarity)}
                            </span>
                        </td>

                        <td>
                            <span
                                class="mc-status ${confidenceClass}"
                            >
                                ${pct(confidence)}
                            </span>
                        </td>

                        <td>
                            <span
                                class="mc-status ${faceClass}"
                            >
                                ${faceText}
                            </span>
                        </td>

                        <td>
                            ${photoButton}
                        </td>

                    </tr>
                `;

            }).join('');

            $('#fBody').html(html);

        })
        .catch(error => {

            console.error(
                'Face Verification Error:',
                error
            );

            $('#fBody').html(`
                <tr>
                    <td colspan="7">
                        <div class="mc-empty">
                            Gagal mengambil data Face Verification.
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    $('#fApply').on('click', function() {
        loadF();
    });

    loadF();
</script>
@include('layout.footer')
