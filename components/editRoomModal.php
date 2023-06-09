<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}
?>

<div id="editRoomModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <input type="hidden" name="id" id="editRoomId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="form-group mb-3">
                    <label for="editRoomName" class="form-label">Room Name</label>
                    <input type="text" class="form-control" id="editRoomName" name="roomName" required>
                </div>
                <!-- Sensors -->
                <div class="d-flex row">
                    <div class="form-label col-12">Sensors</div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-thermometer-half fs-3"></i>
                            <span class="mb-2 mt-1">Temperature</span>
                            <input type="checkbox" class="apple-switch" id="editRoomTemperature" name="temperature" value="25" checked>
                        </label>
                    </div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-tint fs-3"></i>
                            <span class="mb-2 mt-1">Humidity</span>
                            <input type="checkbox" class="apple-switch" id="editRoomHumidity" name="humidity" value="50" checked>
                        </label>
                    </div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-fire fs-3"></i>
                            <span class="mb-2 mt-1">Fire/CO</span>
                            <input type="checkbox" class="apple-switch" id="editRoomFireCo" name="fireco" value="0" checked>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sh">Edit Room</button>
            </div>
        </form>
    </div>
</div>