<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}
?>
<div class="modal fade" id="addCamModal">
    <div class="modal-dialog">
        <form class="modal-content">
            <input type="hidden" name="room_id" id="camRoomId" value="<?php echo $room_id; ?>">
            <input type="hidden" name="type" value="camera">
            
            <div class="modal-header">
                <h5 class="modal-title">Add Camera</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addCamName" class="form-label">Camera Name</label>
                    <input type="text" class="form-control" id="addCamName" placeholder="Enter camera name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="addCamStatus" class="form-label">Status</label>
                    <select class="form-select" id="addCamStatus" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sh ms-auto">Add</button>
            </div>
        </form>
    </div>
</div>