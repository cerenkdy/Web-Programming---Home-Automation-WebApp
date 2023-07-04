<?php
if(!isset($device_group)) {
    header('Location: /myhome.php');
    exit;
}
?>

<div id="addRoomModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <?php if(isset($_SESSION['producer_login'])) { ?>
                    <div class="form-group mb-3">
                        <label for="roomConsumer" class="form-label">Consumer</label>
                        <select class="form-select" id="roomConsumer" name="consumer" required>
                            <option value="" selected disabled>Select Consumer</option>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM consumers WHERE deleted_at IS NULL");
                            $stmt->execute();
                            $consumers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach($consumers as $consumer) {
                                echo '<option value="'.$consumer['id'].'" '.($consumer['id'] == $_SESSION['user'] ? 'selected' : '').'>'.$consumer['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                <?php } ?>
                <div class="form-group mb-3">
                    <label for="roomName" class="form-label">Room Name</label>
                    <input type="text" class="form-control" id="roomName" name="roomName" required>
                </div>
                <!-- Sensors -->
                <div class="d-flex row">
                    <div class="form-label col-12">Sensors</div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-thermometer-half fs-3"></i>
                            <span class="mb-2 mt-1">Temperature</span>
                            <input type="checkbox" class="apple-switch" id="temperature" name="temperature" value="25" checked>
                        </label>
                    </div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-tint fs-3"></i>
                            <span class="mb-2 mt-1">Humidity</span>
                            <input type="checkbox" class="apple-switch" id="humidity" name="humidity" value="50" checked>
                        </label>
                    </div>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light">
                            <i class="fas fa-fire fs-3"></i>
                            <span class="mb-2 mt-1">Fire/CO</span>
                            <input type="checkbox" class="apple-switch" id="roomFireCo" name="fireco" value="0" checked>
                        </label>
                    </div>
                </div>
                <div class="d-flex row">
                    <div class="form-label col-12">Available Devices</div>
                    <?php
                    foreach ($device_group as $deviceId => $device) {
                    ?>
                    <div class="col-md-2 col-4 mt-1 mb-2">
                        <label class="d-flex flex-column align-items-center card p-2 mw-50px cursor-pointer h-100 btn-sh text-light" for="device-<?php echo $deviceId; ?>">
                            <i class="<?php echo $device['icon']; ?> fs-3"></i>
                            <span class="mb-2 mt-1"><?php echo $device['short']; ?></span>
                            <input type="checkbox" class="apple-switch" id="device-<?php echo $deviceId; ?>" name="device[]" value="<?php echo $deviceId; ?>">
                        </label>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sh">Add</button>
            </div>
        </form>
    </div>
</div>