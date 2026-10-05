<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title"><i class="mdi mdi-image-outline me-1 text-primary"></i> Attendance Documentation</h5>
                    <div class="mc-card-caption mt-1">Captured attendance image for verification.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-light-subtle">
                <img id="modalPhoto" src="" class="img-fluid protected-image d-none" alt="Attendance Photo" draggable="false" oncontextmenu="return false;">
                <div id="photoNotFound" class="mc-empty-state d-none">
                    <div class="mc-empty-icon"><i class="mdi mdi-image-off-outline"></i></div>
                    <div class="mc-cell-primary">Photo unavailable</div>
                    <div class="mc-cell-secondary mt-1">The photo has not been uploaded or the file cannot be found.</div>
                </div>
            </div>
        </div>
    </div>
</div>
