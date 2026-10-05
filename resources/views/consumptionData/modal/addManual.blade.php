<div class="modal fade" id="modalAddManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="mdi mdi-account-plus-outline me-1 text-primary"></i> Add Manual Attendance</h5>
                    <div class="mc-card-caption mt-1">Create a meal attendance record manually.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="manualForm" action="{{ route('consumptionData.addManual') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mc-modal-intro">
                        Use this form only when attendance cannot be captured automatically. Employee and visitor records use the same attendance history.
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Attendance Type</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input attendance-type" type="radio" name="attendance_type" id="employeeType" value="employee" checked>
                                <label class="form-check-label" for="employeeType">Employee</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input attendance-type" type="radio" name="attendance_type" id="visitorType" value="visitor">
                                <label class="form-check-label" for="visitorType">Visitor</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6" id="nikContainer">
                            <label class="form-label">Employee</label>
                            <select name="nik" id="nikSelect" class="form-select"></select>
                        </div>

                        <div class="col-md-6 d-none" id="visitorNikContainer">
                            <label class="form-label">Visitor NIK</label>
                            <input type="text" name="visitor_nik" id="visitorNik" class="form-control" placeholder="Input visitor NIK">
                        </div>

                        <div class="col-md-6 d-none" id="visitorContainer">
                            <label class="form-label">Visitor Name</label>
                            <input type="text" name="visitor_name" id="visitorName" class="form-control" placeholder="Input visitor name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Meal Type</label>
                            <select name="meal_type" class="form-select" required>
                                <option value="">Select meal type</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Position</label>
                            <select name="position" class="form-select" required>
                                <option value="">Select position</option>
                                <option value="Mess SIMS">Mess SIMS</option>
                                <option value="Mess Iwaco">Mess Iwaco</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Food Category</label>
                            <select name="food_category" class="form-select" required>
                                <option value="">Select category</option>
                                <option value="1">Basic</option>
                                <option value="2">Special</option>
                                <option value="4">Lunchbox</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Order Type</label>
                            <select name="order_type" class="form-select" required>
                                <option value="">Select order type</option>
                                <option value="Dine In">Dine In</option>
                                <option value="Take Away">Take Away</option>
                                <option value="Menu Sehat">Menu Sehat</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Attendance Date</label>
                            <input type="date" name="attendance_date" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i data-feather="save"></i> Save Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('change', '.attendance-type', function () {
        const type = $(this).val();
        const isEmployee = type === 'employee';

        $('#nikContainer').toggleClass('d-none', !isEmployee);
        $('#visitorContainer, #visitorNikContainer').toggleClass('d-none', isEmployee);
        $('#nikSelect').prop('required', isEmployee);
        $('#visitorName, #visitorNik').prop('required', !isEmployee);

        if (!isEmployee) $('#nikSelect').val(null).trigger('change');
    });

    $('#modalAddManual').on('shown.bs.modal', function () {
        if ($('#nikSelect').hasClass('select2-hidden-accessible')) return;

        $('#nikSelect').select2({
            width: '100%',
            dropdownParent: $('#modalAddManual'),
            placeholder: 'Search NIK or name…',
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('employees.search') }}",
                dataType: 'json',
                delay: 300,
                data: params => ({ q: params.term }),
                processResults: data => ({
                    results: data.map(item => ({ id: item.nik, text: item.nik + ' - ' + item.name }))
                })
            }
        });
    });
</script>
