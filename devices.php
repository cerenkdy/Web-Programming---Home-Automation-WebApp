<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['consumer_login'])) {
    header("Location: login.php");
    exit;
}

$user = intval($_SESSION['user']);

// get rooms
$stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
$stmt->execute([$user]);
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
        <?php include 'components/sidebar.php';?>
        <!-- Devices -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="devices w-100">
                <div class="d-flex align-items-center">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white">Devices</h4>
                    <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus fa-lg"></i>
                    </button>
                </div>
                <?php
                foreach ($rooms as $room) {
                    $room_id = intval($room['id']);
                    $room_name = $room['name'];
                    // get devices
                    $stmt = $db->prepare("SELECT * FROM devices WHERE room_id = ?");
                    $stmt->execute([$room_id]);
                    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="d-flex flex-column mt-4 room-area" id="room-<?php echo $room_id; ?>">
                    <div class="d-flex align-items-center mb-3">
                        <a href="room.php?id=<?php echo $room_id; ?>" class="text-decoration-none text-white flex-grow-1 mr-2 mb-0 text-white fw-bold h5">
                            <h5 class="mb-0"><?php echo $room_name; ?></h5>
                        </a>
                        <button class="btn btn-sm text-white add-device-btn" data-room="<?php echo $room_id; ?>">
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
                    <div class="d-flex flex-wrap flex-row gap-3">
                        <?php
                        $deviceCounter = 0;
                        foreach ($devices as $device) {
                            $device_type = $device['type'];
                            if ($device_type == 'door') {
                                continue;
                            }
                            $device_id = intval($device['id']);
                            $device_name = $device['name'];
                            $device_status = $device['status'];
                            $device = $device_group[$device_type];
                            $deviceCounter++;
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
                                    <span class="h5 text-truncate w-100"><?php echo $device_name; ?></span>
                                    <span class="text-muted mt-auto" style="width: 200px;"><?php echo ($device_status == 1) ? 'On' : 'Off'; ?></span>
                                </div>
                                <div class="dropdown ms-auto mt-auto">
                                    <button class="btn btn-sm text-dark" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item edit-device" href="#" data-id="<?php echo $device_id; ?>">Edit</a></li>
                                        <li><a class="dropdown-item delete-device" href="#" data-id="<?php echo $device_id; ?>">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        if ($deviceCounter == 0) {
                        ?>
                        <div class="bg-white-50 d-flex align-items-center justify-content-center shadow-sm p-3 px-4 rounded-4 mr-auto" style="min-width: 250px;">
                            <i class="fas fa-exclamation-circle fa-1x me-2 text-muted"></i> No device found
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php
    include 'components/addDeviceModal.php';
    include 'components/editDeviceDataModal.php';
    include 'components/addRoomModal.php';
    include 'components/editRoomModal.php';
    ?>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>