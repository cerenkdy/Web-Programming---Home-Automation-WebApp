<?php
if(!isset($device_group)) {
    header('Location: myhome.php');
    exit;
}

// check user and get if not set
if(!isset($user)) {
    $user = $_SESSION['user'];
}

// Check rooms and get if not set
if(!isset($rooms)) {
    $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
    $stmt->execute([$user]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Edit Device Modal -->
<div class="modal fade" id="editDeviceModal">
    <div class="modal-dialog">
        <form class="modal-content">
            <input id="editDeviceId" type="hidden" name="id" value="">
            <div class="modal-header">
                <h5 class="modal-title">Edit Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Device Name -->
                <div class="mb-3">
                    <label for="editDeviceName" class="form-label">Device Name</label>
                    <input type="text" class="form-control" id="editDeviceName" placeholder="Enter device name" name="name">
                </div>

                <!-- Device Room -->
                <div class="mb-3">
                    <label for="editDeviceRoom" class="form-label">Device Room</label>
                    <select class="form-select" id="editDeviceRoom" name="room_id">
                        <option selected disabled>Select device room</option>
                        <?php
                        foreach ($rooms as $room) {
                            $room_id = intval($room['id']);
                            $room_name = $room['name'];
                        ?>
                        <option value="<?php echo $room_id; ?>"><?php echo $room_name; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- device status -->
                <div class="mb-3">
                    <label for="editDeviceStatus" class="form-label">Device Status</label>
                    <select class="form-select" id="editDeviceStatus" name="status">
                        <option value="1" selected>On</option>
                        <option value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sh">Edit Device</button>
            </div>
        </form>
    </div>
</div>