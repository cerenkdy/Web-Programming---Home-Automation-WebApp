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

// delete room
if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete'] > 0) {
    $getDevices = $db->prepare("SELECT * FROM devices WHERE room_id = ?");
    $getDevices->execute([$_GET['delete']]);
    $devices = $getDevices->fetchAll(PDO::FETCH_ASSOC);
    foreach($devices as $device) {
        $stmt = $db->prepare("DELETE FROM logs WHERE device_id = ?");
        $stmt->execute([$device['id']]);
    }
    $stmt = $db->prepare("DELETE FROM devices WHERE room_id = ?");
    $stmt->execute([$_GET['delete']]);
    $stmt = $db->prepare("DELETE FROM sensor_data WHERE room_id = ?");
    $stmt->execute([$_GET['delete']]);
    $stmt = $db->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = 'Room deleted successfully.';

}

// get rooms
$stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
$stmt->execute([$user_id]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// page
$page = 'rooms';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooms | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
<body <?php if(isset($_COOKIE['sidebar-toggle']) && $_COOKIE['sidebar-toggle'] == '1') { echo ' class="sidebar-toggle"'; } ?>>
    <main class="d-flex flex-nowrap">
        <?php include 'components/producerSidebar.php';?>
        <!-- Consumers -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 w-100">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-white">Rooms</h4>
                    <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus fa-lg"></i>
                    </button>
                </div>
                <?php if(isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php } else if(isset($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Consumer</th>
                                <th scope="col">Room</th>
                                <th scope="col">Devices</th>
                                <th scope="col">Last Update</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php
                            if(count($rooms) > 0) {
                                foreach($rooms as $room) {
                                    // get consumer
                                    $stmt = $db->prepare("SELECT * FROM consumers WHERE id = ? LIMIT 1");
                                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                    $stmt->execute([$room['user_id']]);
                                    $consumer = $stmt->fetch();

                                    // get room devices
                                    $stmt = $db->prepare("SELECT id FROM devices WHERE room_id = ?");
                                    $stmt->execute([$room['id']]);
                                    $getRoomDevices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $deviceCount = count($getRoomDevices);
                                    $roomDevices = [];
                                    foreach ($getRoomDevices as $device) {
                                        $roomDevices[] = $device['id'];
                                    }

                                    // get last update
                                    if (count($roomDevices) > 0) {
                                        $roomDevicesIn = str_repeat('?,', count($roomDevices) - 1) . '?';
                                        $stmt = $db->prepare("SELECT * FROM logs WHERE device_id IN (" . $roomDevicesIn . ") ORDER BY id DESC LIMIT 1");
                                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                        $stmt->execute($roomDevices);
                                        $lastUpdate = $stmt->fetch();
                                        if($lastUpdate) {
                                            $lastUpdate = diffForHumans($lastUpdate['created_at']);
                                        } else {
                                            $lastUpdate = '-';
                                        }
                                    } else {
                                        $lastUpdate = '-';
                                    }
                                    ?>
                                <th scope="row"><?php echo $room['id']; ?></th>
                                <td><?php echo $consumer['name']; ?></td>
                                <td><?php echo $room['name']; ?></td>
                                <td><a href="producer_devices.php?room=<?php echo $room['id']; ?>"><?php echo $deviceCount; ?> Devices</a></td>
                                <td><?php echo $lastUpdate; ?></td>
                                <td style="min-width: 90px;">
                                    <a href="#editRoomModal" class="btn btn-sm btn-outline-secondary edit-room-btn" data-id="<?php echo $room['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $room['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <td colspan="6" class="text-center">No rooms found.</td>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/sendDataModal.php';
    include 'components/addRoomModal.php';
    include 'components/editRoomModal.php';
    ?>
    
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
    <script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    </script>
</body>

</html>