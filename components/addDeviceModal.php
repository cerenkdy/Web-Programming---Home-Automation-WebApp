<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}

// Check rooms and get if not set
if(!isset($rooms)) {
    $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
    $stmt->execute([$user]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Add Device Modal -->
<div class="modal fade" id="addDeviceModal">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Device Type -->
                <div class="mb-3">
                    <label for="deviceType" class="form-label">Device Type</label>
                    <select class="form-select" id="deviceType" name="type">
                        <option selected disabled>Select device type</option>
                        <?php
                        foreach ($device_group as $type => $device) {
                            $device_name = $device['name'];
                        ?>
                        <option value="<?php echo $type; ?>"><?php echo $device_name; ?></option>
                        <?php 
                        } 
                        ?>
                    </select>
                </div>

                <!-- Device Name -->
                <div class="mb-3">
                    <label for="deviceName" class="form-label">Device Name</label>
                    <input type="text" class="form-control" id="deviceName" placeholder="Enter device name" name="name" required>
                </div>

                <!-- Device Room -->
                <div class="mb-3">
                    <label for="deviceRoom" class="form-label">Device Room</label>
                    <select class="form-select" id="deviceRoom" name="room_id" required>
                        <option selected disabled>Select device room</option>
                        <?php
                        foreach ($rooms as $room) {
                        ?>
                        <option value="<?php echo intval($room['id']); ?>"><?php echo htmlentities($room['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Device Status -->
                <div class="mb-3">
                    <label for="deviceStatus" class="form-label">Device Status</label>
                    <select class="form-select" id="deviceStatus" name="status">
                        <option selected disabled>Select device status</option>
                        <option value="1" selected>On</option>
                        <option value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sh">Add Device</button>
            </div>
        </form>
    </div>
</div>