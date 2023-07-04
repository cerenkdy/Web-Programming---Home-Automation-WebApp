<?php
if(!isset($device_group)) {
    header('Location: /myhome.php');
    exit;
}
?>

<div class="modal fade" id="addLampModal">
    <div class="modal-dialog">
        <form class="modal-content">
            <input type="hidden" name="id" id="lampRoomId" value="<?php echo $room_id; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Add Lamp</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="lampName" class="form-label">Lamp Name</label>
                    <input type="text" class="form-control" id="lampName" placeholder="Enter lamp name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="lampColor" class="form-label">Lamp Color</label>
                    <input type="color" class="form-control" id="lampColor" name="color" value="#ffffff" required>
                </div>
                <div class="mb-3">
                    <label for="lampBrightness" class="form-label">Lamp Brightness</label>
                    <input type="range" class="form-range" min="0" max="100" step="1" value="100"
                        id="lampBrightness" name="brightness" required>
                </div>
                <div class="mb-3">
                    <label for="lampStatus" class="form-label">Lamp Status</label>
                    <select class="form-select" id="lampStatus" name="status" required>
                        <option value="1">On</option>
                        <option value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary mr-auto" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-sh">Add</button>
            </div>
        </form>
    </div>
</div>