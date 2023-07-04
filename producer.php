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
    $room['data'] = json_decode($room['data'], true);
    $room_ids[$room['id']] = [
        'temperature' => $room['data']['temperature'] ?? rand(18, 35),
        'humidity' => $room['data']['humidity'] ?? rand(30, 80),
    ];
}
$room_ids = json_encode($room_ids);

// page
$page = 'myhome';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Producer Home | <?php echo $name; ?></title>
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
        <!-- Dashboard -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 w-100">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="h2 text-light">Producer Home</h4>
                    <!-- Weather -->
                    <span class="fs-4">
                        <span class="me-2">Antalya</span>
                        <?php
                        $randomWeather = rand(1, 5);
                        switch ($randomWeather) {
                            case 1:
                                echo '<i class="fas fa-cloud fa-lg text-white me-2"></i>';
                                echo '25 &deg;';
                                break;
                            case 2:
                                echo '<i class="fas fa-cloud-sun fa-lg text-white me-2"></i>';
                                echo '30 &deg;';
                                break;
                            case 3:
                                echo '<i class="fas fa-cloud-sun-rain fa-lg text-white me-2"></i>';
                                echo '20 &deg;';
                                break;
                            case 4:
                                echo '<i class="fas fa-cloud-showers-heavy fa-lg text-white me-2"></i>';
                                echo '15 &deg;';
                                break;
                            case 5:
                                echo '<i class="fas fa-sun fa-lg text-white me-2"></i>';
                                echo '35 &deg;';
                                break;
                        }
                        ?>
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <!-- Consumers -->
                        <div class="card bg-white-50 rounded-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title"><a href="producer_consumers.php" class="text-decoration-none text-dark">Consumers</a></h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat">
                                            <i class="fa fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    $stmt = $db->prepare("SELECT COUNT(*) FROM consumers");
                                    $stmt->execute();
                                    echo number_format($stmt->fetchColumn());
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Devices -->
                        <div class="card bg-white-50 rounded-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title"><a href="producer_devices.php" class="text-decoration-none text-dark">Devices</a></h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat">
                                            <i class="fa fa-plug fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    $stmt = $db->prepare("SELECT COUNT(*) FROM devices");
                                    $stmt->execute();
                                    echo number_format($stmt->fetchColumn());
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Rooms -->
                        <div class="card bg-white-50 rounded-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title"><a href="producer_rooms.php" class="text-decoration-none text-dark">Rooms</a></h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat">
                                            <i class="fa fa-door-open fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    $stmt = $db->prepare("SELECT COUNT(*) FROM rooms");
                                    $stmt->execute();
                                    echo number_format($stmt->fetchColumn());
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Log / Sensor Data -->
                        <div class="card bg-white-50 rounded-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Log / Sensor Data</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat">
                                            <i class="fa fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">
                                    <?php
                                    $stmt = $db->prepare("SELECT COUNT(*) FROM logs");
                                    $stmt->execute();
                                    $logsCount = $stmt->fetchColumn();
                                    
                                    $stmt = $db->prepare("SELECT COUNT(*) FROM sensor_data");
                                    $stmt->execute();
                                    $sensorDataCount = $stmt->fetchColumn();

                                    echo number_format($logsCount + $sensorDataCount);
                                    ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Consumers -->
                        <a href="producer_consumers.php" class="card bg-white-50 rounded-4 text-decoration-none text-dark py-4">
                            <span class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-users fa-4x"></i>
                                <span class="mt-2 fs-5">Consumers</span>
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Add Device -->
                        <a href="#" class="card bg-white-50 rounded-4 text-decoration-none text-dark py-4" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                            <span class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-plus fa-4x"></i>
                                <span class="mt-2 fs-5">Add Device</span>
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Add Room -->
                        <a href="#" class="card bg-white-50 rounded-4 text-decoration-none text-dark py-4" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                            <span class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-map fa-4x"></i>
                                <span class="mt-2 fs-5">Add Room</span>
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <!-- Send Data -->
                        <a href="#" class="card bg-white-50 rounded-4 text-decoration-none text-dark py-4 send-data-btn" data-id="<?php echo $user_id; ?>" data-rooms="<?php echo htmlentities($room_ids); ?>">
                            <span class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-paper-plane fa-4x"></i>
                                <span class="mt-2 fs-5">Send Data</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/sendDataModal.php';
    include 'components/addDeviceModal.php';
    include 'components/addRoomModal.php';
    ?>
    
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>