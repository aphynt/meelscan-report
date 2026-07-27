<div class="modal fade" id="healthyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-food-apple me-2"></i>
                    Add Employee to Healthy Menu
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- FORM TAMBAH -->
                <div class="card shadow-sm mb-3">

                    <div class="card-body">

                        <!-- Jenis Input -->
                        <div class="mb-3">
                            <label class="form-label">Input Type</label>

                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="radio"
                                        name="input_type"
                                        id="typeEmployee"
                                        value="employee"
                                        checked>

                                    <label class="form-check-label" for="typeEmployee">
                                        By Employee
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="radio"
                                        name="input_type"
                                        id="typeManual"
                                        value="manual">

                                    <label class="form-check-label" for="typeManual">
                                        Manual
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">

                            <!-- Employee -->
                            <!-- Employee -->
                            <div id="employeeSection">

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee (Search NIK)</label>

                                        <select id="employee_id"
                                                class="form-select"
                                                style="width:100%">
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee Name</label>

                                        <input type="text"
                                            id="employee_name"
                                            class="form-control"
                                            readonly>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <label class="form-label">Additional</label>

                                        <input type="text"
                                            id="employee_additional"
                                            class="form-control"
                                            placeholder="Additional information (optional)">
                                    </div>

                                </div>

                            </div>

                            <!-- Manual -->
                            <!-- Manual -->
                            <div id="manualSection" style="display:none;">

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIK</label>

                                        <input type="text"
                                            id="manual_nik"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee Name</label>

                                        <input type="text"
                                            id="manual_name"
                                            class="form-control">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <label class="form-label">Additional</label>

                                        <input type="text"
                                            id="manual_additional"
                                            class="form-control"
                                            placeholder="Additional information (optional)">
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12">
                                <button type="button"
                                        class="btn btn-success w-100"
                                        id="btnSaveHealthy">
                                    <i class="mdi mdi-plus me-1"></i>
                                    Tambah
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- DAFTAR HEALTHY -->
                <div class="card shadow-sm">

                    <div class="card-header bg-light">
                        <strong>List of Employees Registered in the Healthy Menu</strong>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th width="60">No</th>
                                    <th width="120">NIK</th>
                                    <th>Name</th>
                                    <th>Additional</th>
                                    <th width="120" class="text-center">Action</th>
                                </tr>

                            </thead>

                            <tbody id="healthyTable">

                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No Data.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Tutup
                </button>

            </div>

        </div>
    </div>
</div>
