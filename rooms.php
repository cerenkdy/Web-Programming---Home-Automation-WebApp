<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['consumer_login'])) {
    header("Location: login.php");
    exit;
}

// vars
$user_id = intval($_SESSION['user']);

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

<body <?php if(isset($_COOKIE['sidebar-toggle']) && $_COOKIE['sidebar-toggle'] == '1') { echo ' class="sidebar-toggle"'; } ?>>
    <main class="d-flex flex-nowrap">
        <?php include 'components/sidebar.php';?>
        <!-- Rooms -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="rooms w-100">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="flex-grow-1 mr-2 mb-0 text-white">Rooms</h4>
                    <button class="btn btn-secondary btn-sm ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addRoomModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="row">
                    <?php
                    $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rooms as $room) {
                        $stmt = $db->prepare("SELECT * FROM devices WHERE room_id = ?");
                        $stmt->execute([$room['id']]);
                        $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $data = json_decode($room['data'], true);

                        $usedDevices = [];
                        foreach ($devices as $device) {
                            if (in_array($device['type'], $usedDevices)) {
                                continue;
                            }
                            $usedDevices[] = $device['type'];
                        }
                        ?>
                    
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="room.php?id=<?php echo $room['id']; ?>" class="d-flex flex-column p-3 rounded text-dark text-decoration-none bg-white-50 mw-100 h-100 box-shadow">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="mb-0"><?php echo $room['name']; ?></h5>
                                    </div>
                                    <p class="text-muted"><?php echo count($usedDevices); ?> device group, <?php echo count($devices); ?> devices</p>
                                    <div class="d-flex flex-row mt-auto">
                                        <?php if (isset($data['temperature']) && isset($data['temperature_status']) && $data['temperature_status'] == '1') { ?>
                                        <div class="me-3">
                                            <i class="fas fa-thermometer-half me-1"></i>
                                            <span><?php echo $data['temperature']; ?>°C</span>
                                        </div>
                                        <?php
                                        }
                                        if (isset($data['humidity']) && isset($data['humidity_status']) && $data['humidity_status'] == '1') {
                                        ?>
                                        <div class="me-3">
                                            <i class="fas fa-tint me-1"></i>
                                            <span><?php echo $data['humidity']; ?>%</span>
                                        </div>
                                        <?php
                                        }
                                        if (isset($data['fireco']) && $data['fireco'] == '1' && isset($data['fireco_status']) && $data['fireco_status'] == '1') { 
                                        ?>
                                        <div class="me-3">
                                            <i class="fas fa-fire me-1 text-danger"></i>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-chevron-right fa-2x text-secondary text-sh"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/addRoomModal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>