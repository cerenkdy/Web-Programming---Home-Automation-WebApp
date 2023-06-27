<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php?type=producers
if (!isset($_SESSION['producer_login'])) {
    header("Location: login.php?type=producers");
    exit;
}

if(isset($_GET['user']) && is_numeric($_GET['user']) && $_GET['user'] > 0) {
    // check user
    $stmt = $db->prepare("SELECT * FROM consumers WHERE id = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$_GET['user']]);
    if($stmt->rowCount() > 0) {
        $_SESSION['user'] = intval($_GET['user']);
    }
}

$user_id = intval($_SESSION['user']);

// get rooms
$stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
$stmt->execute([$user_id]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// room ids to json
$room_ids = [];
foreach($rooms as $room) {
    $room_ids[] = $room['id'];
}
$room_ids = json_encode($room_ids);

// page
$page = 'devices';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Producer | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex flex-row align-items-center p-3 px-md-4 bg-light border-bottom shadow-sm">
        <h5 class="my-0 fw-normal text-dark"><?php echo $name; ?></h5>
        <!-- Send Data Button -->
        <a class="btn btn-sh btn-sm ms-auto send-data-btn" href="#" data-id="<?php echo $user_id; ?>" data-rooms="<?php echo htmlentities($room_ids); ?>">Send Data</a>
        <a class="btn btn-sh btn-sm ms-2" href="producer.php?logout=true"><i class="fas fa-sign-out-alt"></i></a>
    </div>
    <main class="d-flex flex-nowrap lrform">
        <div class="d-flex flex-column flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="user w-100 d-flex flex-wrap">
                <div class="input-group mb-3 w-auto me-2">
                    <label class="input-group-text" for="selectUser">Consumer</label>
                    <select class="form-select" id="selectUser" onchange="location.href = 'producer.php?user=' + this.value;">
                        <?php
                        // get consumers
                        $stmt = $db->prepare("SELECT * FROM consumers");
                        $consumers = $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
                        foreach ($consumers as $consumer) {
                            $consumer_id = intval($consumer['id']);
                            $consumer_name = $consumer['name'];
                        ?>
                        <option value="<?php echo $consumer_id; ?>"<?php echo $consumer_id == $user_id ? ' selected' : ''; ?>><?php echo $consumer_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group mb-3 w-auto me-2">
                    <?php
                    $outdoor_lock = '0';
                    $stmt = $db->prepare("SELECT data FROM home_configs WHERE type = 'outdoor_lock' AND user_id = ? LIMIT 1");
                    if($stmt->execute([$user_id])) {
                        $outdoor_lock = $stmt->fetch(PDO::FETCH_ASSOC);
                        $outdoor_lock = @json_decode($outdoor_lock['data'], true);
                        $outdoor_lock = $outdoor_lock['status'];
                    }
                    ?>
                    <label class="input-group-text" for="outdoorLock">Outdoor Lock</label>
                    <select class="form-select" id="outdoorLock" onchange="setConfig('outdoor_lock', 'status', this.value);">
                        <option value="1"<?php echo $outdoor_lock == '1' ? ' selected' : ''; ?>>Locked</option>
                        <option value="0"<?php echo $outdoor_lock == '0' ? ' selected' : ''; ?>>Unlocked</option>
                    </select>
                </div>
                <?php
                $stmt = $db->prepare("SELECT * FROM home_configs WHERE type = 'door' AND user_id = ?");
                if($stmt->execute([$user_id])) {
                    $doors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($doors as $door) {
                        $door_id = intval($door['id']);
                        $door_data = @json_decode($door['data'], true);
                        $door_name = $door_data['name'];
                        $door_status = $door_data['status'];
                ?>
                <div class="input-group mb-3 w-auto me-2">
                    <label class="input-group-text" for="door-<?php echo $door_id; ?>"><?php echo $door_name; ?></label>
                    <select class="form-select" id="door-<?php echo $door_id; ?>" onchange="setConfig('<?php echo $door_id; ?>', 'status', this.value);">
                        <option value="1"<?php echo $door_status == '1' ? ' selected' : ''; ?>>Closed</option>
                        <option value="0"<?php echo $door_status == '0' ? ' selected' : ''; ?>>Open</option>
                    </select>
                </div>
                <?php
                    }
                }
                ?>
            </div>

            <!-- Devices -->
            <div class="devices w-100">
                <div class="d-flex align-items-center">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h3">Devices</h4>
                </div>
                <?php
                foreach ($rooms as $room) {
                    $room_id = intval($room['id']);
                    $room_name = $room['name'];
                    // get devices
                    $stmt = $db->prepare("SELECT * FROM devices WHERE room_id = ?");
                    $stmt->execute([$room_id]);
                    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $room['data'] = @json_decode($room['data'], true);
                ?>
                <div class="d-flex flex-column mt-4 room-area" id="room-<?php echo $room_id; ?>">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5"><?php echo $room_name; ?></h5>
                        <?php
                        $fireco = isset($room['data']['fireco']) ? $room['data']['fireco'] : '0';
                        $firecoStatus = isset($room['data']['fireco_status']) ? $room['data']['fireco_status'] : '0';
                        if($firecoStatus == '1') {
                        ?>
                        <div class="d-flex align-items-center me-auto">
                            <i class="fas fa-fire fa-lg me-2 mb-1 text-danger"></i>
                            <h5 class="h6 me-2">Fire/CO</h5>
                            <label class="switch ms-auto">
                                <input type="checkbox" class="apple-switch sh-switch"<?php if ($fireco == '1') { echo ' checked'; } ?> onchange="setRoomData('<?php echo $room_id; ?>', 'fireco', this.checked ? '1' : '0');">
                            </label>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3">
                        <?php if($room['data']['temperature_status'] == '1') { ?>
                        <!-- Temperature -->
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column temperature" style="width: 250px;" data-room="<?php echo $room['id']; ?>" data-temperature="<?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>">
                            <div class="d-flex mb-3 align-items-center">
                                <i class="fas fa-thermometer-half fa-lg me-2 mb-1"></i>
                                <h5 class="h5 me-2">Temperature</h5>
                                <label class="switch ms-auto">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'temperature_status', this.checked?'1':'0')"<?php if (isset($room['data']['temperature_status']) && $room['data']['temperature_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column me-auto">
                                    <span class="fs-1 set-text"><?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>&deg;</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <button class="btn btn-sm btn-sh mb-2 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh decrease" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        if($room['data']['humidity_status'] == '1') {
                        ?>
                        <!-- Humidifier -->
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column humidity" style="width: 250px;" data-room="<?php echo $room['id']; ?>"  data-humidity="<?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>">
                            <div class="d-flex mb-3 align-items-center">
                                <i class="fa fa-tint fa-lg me-2 mb-1"></i>
                                <h5 class="h5 me-2">Humidifier</h5>
                                <label class="switch ms-auto">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'humidity_status', this.checked?'1':'0')"<?php if (isset($room['data']['humidity_status']) && $room['data']['humidity_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column me-auto">
                                    <span class="fs-1"><?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 0); ?>%</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <button class="btn btn-sm btn-sh mb-2 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh decrease" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        foreach ($devices as $device) {
                            $device_type = $device['type'];
                            if ($device_type == 'door') {
                                continue;
                            }
                            $device_id = intval($device['id']);
                            $device_name = $device['name'];
                            $device_status = $device['status'];
                            $device = $device_group[$device_type];
                        ?>
                        <div class="bg-white-50 shadow p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
                            <div class="d-flex justify-content-center align-items-start mb-3">
                                <div class="d-flex justify-content-center align-items-center rounded-circle btn-sh text-white me-2"
                                    style="width: 50px; height: 50px;">
                                    <i class="<?php echo $device['icon']; ?> fa-lg"></i>
                                </div>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button" data-id="<?php echo $device_id; ?>" data-status="<?php echo $device_status; ?>">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex h-100">
                                <div class="d-flex flex-column">
                                    <span class="h5"><?php echo $device_name; ?></span>
                                    <span class="text-muted mt-auto"><?php echo ($device_status == 1) ? 'On' : 'Off'; ?></span>
                                </div>
                                <div class="ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark edit-device-producer" data-id="<?php echo $device_id; ?>" type="button">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php
    include 'components/editDeviceDataModal.php';
    include 'components/sendDataModal.php';
    ?>
    
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>