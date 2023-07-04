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

// page
$page = 'devices';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Devices | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body <?php if(isset($_COOKIE['sidebar-toggle']) && $_COOKIE['sidebar-toggle'] == '1') { echo ' class="sidebar-toggle"'; } ?>>
    <main class="d-flex flex-nowrap">
        <?php include 'components/producerSidebar.php';?>
        <div class="d-flex flex-column flex-fill p-3 px-md-4 px-sm-2 mw-100 w-100">
            
            <!-- Devices -->
            <div class="devices w-100">
                <div class="d-flex align-items-center">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h3">Devices</h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-sh dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-door-open fa-lg me-2"></i>
                            <span><?php
                            if (isset($_GET['room']) && intval($_GET['room']) != 0) {
                                $stmt = $db->prepare("SELECT name FROM rooms WHERE id = ? AND user_id = ? LIMIT 1");
                                $stmt->execute([$_GET['room'], $user_id]);
                                $room = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo htmlspecialchars($room['name']);
                            } else {
                                echo 'All Rooms';
                            }
                            ?></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="producer_devices.php">All Rooms</a></li>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
                            $stmt->execute([$user_id]);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li><a class="dropdown-item" href="producer_devices.php?room=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <button class="btn btn-sm text-white ms-2" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus fa-lg"></i>
                    </button>
                </div>
                <?php
                foreach ($rooms as $room) {
                    if(isset($_GET['room']) && intval($_GET['room']) != intval($room['id'])){
                        continue;
                    }
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
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5 me-auto"><?php echo $room_name; ?></h5>
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
                        <button class="btn btn-sm text-white add-device-btn ms-1" data-room="<?php echo $room_id; ?>">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item add-device-btn" href="#" data-room="<?php echo $room_id; ?>">Add Device</a></li>
                                <li><a class="dropdown-item edit-room-btn" href="#" data-id="<?php echo $room_id; ?>">Edit</a></li>
                                <li><a class="dropdown-item delete-room" href="#" data-id="<?php echo $room_id; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-row gap-3 producer-devices">
                        <?php if($room['data']['temperature_status'] == '1') { ?>
                        <!-- Temperature -->
                        <div class="bg-white-50 rounded-4 p-3 shadow-sm temperature" data-room="<?php echo $room['id']; ?>" data-temperature="<?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>" data-chart="<?php echo json_encode($sensors['temperature']) ?>" style="min-width: 250px;">
                            <div class="d-flex mb-3 align-items-center justify-content-start">
                                <i class="fas fa-thermometer-half fa-lg me-2"></i>
                                <h5 class="mb-0 me-2">Temperature</h5>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'temperature_status', this.checked?'1':'0')"<?php if (isset($room['data']['temperature_status']) && $room['data']['temperature_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="set-text me-auto rounded p-1 py-3 h1 h2 mb-0 fw-normal"><?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>&deg;</span>
                                <input type="text" value="<?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>" data-room="<?php echo $room['id']; ?>" name="temperature" class="set-input form-control form-control-sm text-center me-auto rounded p-1 py-3 h1 h2 mb-0 d-none" style="width: 50px;">
                                <div class="d-flex flex-column h-100">
                                    <button class="btn btn-sm btn-sh mb-1 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh mt-1 decrease" type="button">
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
                        <div class="bg-white-50 rounded-4 p-3 shadow-sm humidity" data-room="<?php echo $room['id']; ?>"  data-humidity="<?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>" data-chart="<?php echo json_encode($sensors['humidity']) ?>" style="min-width: 250px;">
                            <div class="d-flex mb-3 align-items-center">
                                <i class="fa fa-tint fa-lg me-2"></i>
                                <h5 class="h5 me-2 mb-0">Humidifier</h5>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'humidity_status', this.checked?'1':'0')"<?php if (isset($room['data']['humidity_status']) && $room['data']['humidity_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="set-text me-auto rounded p-1 py-3 h1 h2 mb-0 fw-normal"><?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>%</span>
                                <input type="text" value="<?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>" data-room="<?php echo $room['id']; ?>" name="humidity" class="set-input form-control form-control-sm text-center me-auto rounded p-1 py-3 h1 h2 mb-0 d-none" style="width: 50px;">
                                <div class="d-flex flex-column">
                                    <button class="btn btn-sm btn-sh mb-1 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh mt-1 decrease" type="button">
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
                        <div class="bg-white-50 shadow-sm p-3 rounded-4 room-device d-flex flex-column" style="width: 250px;">
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
                                    <span class="h5 text-truncate"  style="width: 200px;"><?php echo $device_name; ?></span>
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

            <!-- Doors -->
            <div class="doors mt-5 w-100">
                <h4 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h3">Doors</h4>
                <div class="user w-100 d-flex flex-wrap mt-3">
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
            </div>
        </div>
    </main>

    <?php
    include 'components/addDeviceModal.php';
    include 'components/addRoomModal.php';
    include 'components/editRoomModal.php';
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