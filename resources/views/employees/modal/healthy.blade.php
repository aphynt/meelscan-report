<div class="modal fade" id="healthyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="mdi mdi-food-apple-outline me-1 text-success"></i> Healthy Menu Management</h5>
                    <div class="mc-card-caption mt-1">Register employees or manual entries for the Healthy Menu program.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mc-modal-intro">
                    Healthy Menu members can be selected from the employee directory or added manually when the person is not available in the master employee list.
                </div>

                <div class="card shadow-none mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Input Type</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="input_type" id="typeEmployee" value="employee" checked>
                                    <label class="form-check-label" for="typeEmployee">Employee Directory</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="input_type" id="typeManual" value="manual">
                                    <label class="form-check-label" for="typeManual">Manual Entry</label>
                                </div>
                            </div>
                        </div>

                        <div id="employeeSection">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Employee</label>
                                    <select id="employee_id" class="form-select" style="width:100%"></select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" id="employee_name" class="form-control" placeholder="Automatically filled" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Additional Information</label>
                                    <input type="text" id="employee_additional" class="form-control" placeholder="Optional note">
                                </div>
                            </div>
                        </div>

                        <div id="manualSection" style="display:none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">NIK</label>
                                    <input type="text" id="manual_nik" class="form-control" placeholder="Input NIK">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" id="manual_name" class="form-control" placeholder="Input employee name">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Additional Information</label>
                                    <input type="text" id="manual_additional" class="form-control" placeholder="Optional note">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-primary" id="btnSaveHealthy">
                                <i class="mdi mdi-plus-circle-outline"></i> Add to Healthy Menu
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card shadow-none mb-0">
                    <div class="card-header mc-card-header-inline">
                        <div>
                            <h6 class="mc-card-heading">Registered Members</h6>
                            <div class="mc-card-caption">Employees currently assigned to Healthy Menu.</div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th style="width:130px">NIK</th>
                                    <th>Name</th>
                                    <th>Additional</th>
                                    <th style="width:80px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="healthyTable">
                                <tr><td colspan="5" class="text-center text-muted py-4">No data.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
