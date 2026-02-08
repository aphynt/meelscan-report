<div class="modal fade" id="modalAddManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i data-feather="edit-3" class="me-1"></i>
                    Add Manual Attendance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Form -->
            <form id="manualForm" action="{{ route('consumptionData.addManual') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- NIK -->
                        <div class="col-md-4">
                            <label class="form-label">NIK</label>
                            <select name="nik" id="nikSelect" class="form-select" required></select>
                        </div>
                        <!-- Meal Type -->
                        <div class="col-md-4">
                            <label class="form-label">Meal Type</label>
                            <select name="meal_type" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>

                        <!-- Position -->
                        <div class="col-md-4">
                            <label class="form-label">Position</label>
                            <select name="position" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Mess SIMS">Mess SIMS</option>
                                <option value="Mess Iwaco">Mess Iwaco</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Food Category</label>
                            <select name="food_category" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="1">Basic</option>
                                <option value="2">Special</option>
                                <option value="4">Lunchbox</option>
                            </select>
                        </div>

                        <!-- Attendance Date -->
                        <div class="col-md-4">
                            <label class="form-label">Attendance Date</label>
                            <input type="date" name="attendance_date" class="form-control" required>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1"></i>
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('#modalAddManual').on('shown.bs.modal', function () {

    $('#nikSelect').select2({
        width: '100%',
        dropdownParent: $('#modalAddManual'),
        placeholder: 'Search NIK or Name...',
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('employees.search') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.nik,
                        text: item.nik + ' - ' + item.name
                    }))
                };
            }
        }
    });

});
</script>
