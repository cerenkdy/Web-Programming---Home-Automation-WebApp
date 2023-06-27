<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}
?>

<div class="modal fade" id="editLampModal">
    <div class="modal-dialog">
        <form class="modal-content">
            <input type="hidden" name="id" id="editLampId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lamp</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editLampName" class="form-label">Lamp Name</label>
                    <input type="text" class="form-control" id="editLampName" placeholder="Enter lamp name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="editLampColor" class="form-label">Lamp Color</label>
                    <input type="color" class="form-control" id="editLampColor" name="color" required>
                </div>
                <div class="mb-3">
                    <label for="editLampBrightness" class="form-label">Lamp Brightness</label>
                    <input type="range" class="form-range" min="0" max="100" step="1" value="100"
                        id="editLampBrightness" name="brightness" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger me-auto delete-lamp-btn" data-id="0">Delete</button>
                <button class="btn btn-sh ms-auto">Save</button>
            </div>
        </form>
    </div>
</div>