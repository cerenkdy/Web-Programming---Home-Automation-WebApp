<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['consumer_login'])) {
    header("Location: login.php");
    exit;
}

// get room datas
$user_id = intval($_SESSION['user']);
$stmt = $db->prepare("SELECT data FROM rooms WHERE user_id = ?");
$stmt->execute([$user_id]);
$temperatures = [];
$humidities = [];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data = json_decode($row['data'], true);
    if(isset($data['temperature']) && $data['temperature'] > 0 && isset($data['temperature_status']) && $data['temperature_status'] == '1') {
        $temperatures[] = $data['temperature'];
    }
    if(isset($data['humidity']) && $data['humidity'] > 0 && isset($data['humidity_status']) && $data['humidity_status'] == '1') {
        $humidities[] = $data['humidity'];
    }
}

// get camera datas
$cameras = [];
$stmt = $db->prepare("SELECT name FROM devices WHERE user_id = ? AND type = 'camera' AND status = '1'");
$stmt->execute([$user_id]);
$camCounter = 0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $camCounter++;
    $cameras[] = [
        'name' => $row['name'],
        'src' => 'img/camera'.$camCounter.'.jpg',
    ];
    if ($camCounter == 3) {
        $camCounter = 0;
    }
}

// page
$page = 'myhome';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $name; ?></title>
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
        <!-- Dashboard -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="h2 text-light">My Home</h4>
                    <!-- Weather -->
                    <span class="fs-4">
                        <span class="me-2">Antalya</span>
                        <?php
                        $randomWeather = rand(1, 5);
                        switch ($randomWeather) {
                            case 1:
                                echo '<i class="fas fa-cloud fa-lg text-white me-2"></i>';
                                echo '25 &deg;C';
                                break;
                            case 2:
                                echo '<i class="fas fa-cloud-sun fa-lg text-white me-2"></i>';
                                echo '30 &deg;C';
                                break;
                            case 3:
                                echo '<i class="fas fa-cloud-sun-rain fa-lg text-white me-2"></i>';
                                echo '20 &deg;C';
                                break;
                            case 4:
                                echo '<i class="fas fa-cloud-showers-heavy fa-lg text-white me-2"></i>';
                                echo '15 &deg;C';
                                break;
                            case 5:
                                echo '<i class="fas fa-sun fa-lg text-white me-2"></i>';
                                echo '35 &deg;C';
                                break;
                        }
                        ?>
                    </span>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-8 rounded-4 position-relative mb-3">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 mb-3">
                                <!-- Electricity Usage -->
                                <div class="card bg-white-50 rounded-4 h-100">
                                    <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                        <h2 class="h6 pb-0">Electricity Usage</h2>
                                        <span class="fs-4 m-auto">
                                            <i class="fas fa-bolt fa-lg text-warning"></i>
                                            <span class="electricity-usage">
                                            <?php
                                            $query = "SELECT COUNT(*) FROM devices WHERE user_id = ? AND status = 1 AND electricity = 1";
                                            $stmt = $db->prepare($query);
                                            $stmt->execute([$_SESSION['user']]);
                                            echo $stmt->fetchColumn() . ' KWh';
                                            ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 mb-3">
                                <!-- Water Usage -->
                                <div class="card bg-white-50 rounded-4 h-100">
                                    <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                        <h2 class="h6 pb-0">Water Usage</h2>
                                        <span class="fs-4 m-auto">
                                            <i class="fas fa-lg text-primary fa-tint"></i>
                                            <span class="water-usage"><?php echo rand(1, 5) ?> L</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 mb-3">
                                <!-- Gas Usage -->
                                <div class="card bg-white-50 rounded-4 h-100">
                                    <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                        <h2 class="h6 pb-0">Gas Usage</h2>
                                        <span class="fs-4 m-auto">
                                            <i class="fas fa-lg text-danger fa-fire"></i>
                                            <span class="gas-usage"><?php echo rand(1, 5) ?> m<sup>3</sup></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 mb-3">
                                <!-- Outdoor Lock Status -->
                                <div class="card bg-white-50 rounded-4 h-100 outdoor-lock">
                                    <?php
                                    $stmt = $db->prepare("SELECT data FROM home_configs WHERE user_id = ? AND type = 'outdoor_lock' LIMIT 1");
                                    $stmt->execute([$_SESSION['user']]);
                                    $outdoorLock = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $outdoorLock = json_decode($outdoorLock['data'], true);
                                    $lock = $outdoorLock['status'];
                                    ?>
                                    <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                        <div class="d-flex flex-row w-100">
                                            <h2 class="h6 pb-0 me-2">Outdoor Lock</h2>
                                            <?php if($lock) { ?>
                                            <button class="btn btn-sm ms-auto btn-secondary"><i class="fas fa-lg fa-lock"></i></button>
                                            <?php } else { ?>
                                            <button class="btn btn-sm ms-auto btn-sh"><i class="fas fa-lg fa-lock-open"></i></button>
                                            <?php } ?>
                                        </div>
                                        <span class="fs-4 m-auto">
                                            <i class="fas fa-lg text-dark lock-icon <?php echo $lock ? 'fa-lock' : 'fa-lock-open' ?>"></i>
                                            <span class="lock-text"><?php echo $lock ? 'Locked' : 'Unlocked' ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <!-- Indoor Temperature -->
                                <div class="card bg-white-50 rounded-4 h-100 indoor-temperature">
                                    <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                        <h2 class="h5 pb-0">Indoor Temperature</h2>
                                        <span class="fs-4">
                                            <i class="fas fa-thermometer-half fa-lg text-warning"></i>
                                            <span class="text">
                                                <?php
                                                $temperature = count($temperatures) > 0 ? round(array_sum($temperatures) / count($temperatures), 1) : 0;
                                                echo $temperature;
                                                ?> &deg;C
                                            </span>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mt-3">
                                            <div class="progress-bar bg-warning btn-sh" role="progressbar" style="width: <?php echo $temperature; ?>%"
                                                aria-valuenow="<?php echo $temperature; ?>" aria-valuemin="0" aria-valuemax="50"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <!-- Humidifier -->
                                <div class="card bg-white-50 rounded-4 h-100 indoor-humidifier">
                                    <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                        <h2 class="h5 pb-0">Humidifier</h2>
                                        <span class="fs-4">
                                            <i class="fas fa-tint fa-lg text-primary"></i>
                                            <span class="text">
                                                <?php
                                                $humidity = count($humidities) > 0 ? round(array_sum($humidities) / count($humidities), 1) : '0';
                                                echo $humidity;
                                                ?> %
                                            </span>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mt-3">
                                            <div class="progress-bar bg-primar btn-sh" role="progressbar" style="width: <?php echo $humidity; ?>%" 
                                                aria-valuenow="<?php echo $humidity; ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Security Cam -->
                    <div class="col-lg-4 rounded-4 position-relative mb-3">
                        <div class="d-flex security-cam" data-cams="<?php echo htmlentities(json_encode($cameras)); ?>">
                            <div class="position-absolute too-0 start-0 mt-2 ms-4">
                                <span class="badge bg-white-50">
                                    <i class="fas fa-video fa-lg text-dark"></i>
                                    <span class="text-dark"><?php echo (isset($cameras[0]) && isset($cameras[0]['name'])) ? $cameras[0]['name'] : 'No Camera'; ?></span>
                                </span>
                            </div>
                            <img src="img/camera.jpg" alt="" class="w-100 rounded-4<?php echo (isset($cameras[0]) && isset($cameras[0]['name'])) ? '' : ' invisible'; ?>">
                        </div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-4 mb-3">
                        <!-- Lamps control -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5">Lamps Control</h2>
                                <i class="fas fa-lightbulb fa-lg text-dark"></i>
                            </div>
                            <ul class="list-unstyled overflow-auto" style="max-height: 210px">
                                <?php
                                $stmt = $db->prepare("SELECT * FROM devices WHERE type = 'light' AND user_id = ?");
                                $stmt->execute([$user_id]);
                                $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($devices as $device) {
                                    $device_id = $device['id'];
                                    $device_name = $device['name'];
                                    $device_status = $device['status'];
                                    ?>
                                <li class="d-flex align-items-center p-2 px-3">
                                    <span class="fs-5 mr-2"><?php echo $device_name; ?></span>
                                    <label class="switch ms-auto">
                                        <input type="checkbox" class="apple-switch sh-switch" onchange="deviceStatus(<?php echo $device_id; ?>, this.checked)" <?php if($device_status == 1) echo "checked"; ?>>
                                    </label>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <!-- Doors control -->
                        <div class="card bg-white-50 rounded-4 h-100 doors-control">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5">Doors Control</h2>
                                <i class="fas fa-door-open fa-lg text-dark"></i>
                            </div>
                            <ul class="list-unstyled overflow-auto" style="max-height: 210px">
                                <?php
                                $stmt = $db->prepare("SELECT * FROM home_configs WHERE type = 'door' AND user_id = ?");
                                $stmt->execute([$user_id]);
                                $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($devices as $device) {
                                    $device_id = $device['id'];
                                    $device = @json_decode($device['data'], true);
                                    $device_name = $device['name'];
                                    $device_status = $device['status'];
                                    ?>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5"><?php echo $device_name; ?></span>
                                    <span class="badge rounded-pill p-2 <?php if($device_status == 1){ echo "bg-secondary"; } else { echo "btn-sh"; } ?>" data-device="<?php echo $device_id; ?>">
                                        <i class="fas fa-lg <?php if($device_status == 1) { echo "fa-lock"; } else { echo "fa-lock-open"; } ?>"></i>
                                        <span><?php echo ($device_status == 0) ? "Unlocked" : "Locked"; ?></span>
                                    </span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <?php
                        $have_speaker = false;
                        $stmt = $db->prepare("SELECT * FROM devices WHERE type = 'speaker' AND user_id = ? ORDER BY id ASC LIMIT 1");
                        $stmt->execute([$user_id]);
                        if ($stmt->rowCount() > 0) {
                            $speaker = $stmt->fetch(PDO::FETCH_ASSOC);
                            $speaker_data = @json_decode($speaker['data'], true);
                            $speaker_id = $speaker['id'];
                            $speaker_status = $speaker_data['status'];
                            $speaker_volume = $speaker_data['volume'];
                        }
                        ?>
                        <div class="card bg-white-50 rounded-4 h-100 speaker position-relative">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5">Speaker</h2>
                                <i class="fas fa-music fa-lg text-dark"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <img src="https://i.scdn.co/image/ab67616d0000b273b6d4566db0d12894a1a3b7a2" alt=""
                                        class="rounded-2 shadow song-cover" width="75" height="75">
                                    <div class="ms-3">
                                        <span class="d-block song-title">Undisclosed Desires</span>
                                        <span class="text-muted d-block song-artist">Muse</span>
                                        <span class="text-muted song-album">The Resistance</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-3 gap-2">
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-random"><i
                                            class="fas fa-random fs-5"></i></button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-prev"><i
                                            class="fas fa-backward fs-5"></i></button>
                                    <button class="btn btn-sm fa-2x btn-play" data-id="<?php echo isset($speaker_id) ? $speaker_id : 0; ?>">
                                        <i class="fas fa-2x text-sh <?php if(isset($speaker_volume) && $speaker_status == 1) { echo "fa-pause-circle"; } else { echo "fa-play-circle"; } ?>"></i>
                                    </button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-next"><i
                                            class="fas fa-forward fs-5"></i></button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-toggle"><i
                                            class="fas fa-redo fs-5"></i></button>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-sm rounded-circle mr-2 btn-mute">
                                        <i class="fas fa-lg <?php if(isset($speaker_volume) && $speaker_volume < 1) { echo "fa-volume-mute"; } else { echo "fa-volume-up"; } ?>"></i>
                                    </button>
                                    <input type="range" class="form-range volume-range" min="0" max="100" data-id="<?php echo isset($speaker_id) ? $speaker_id : 0; ?>" value="<?php echo (isset($speaker_volume)) ? $speaker_volume : 100; ?>" step="1" style="max-width: 220px">
                                </div>
                            </div>
                            <?php if(!isset($speaker)) { ?>
                                <div class="bg-light opacity-75 shadow rounded-4 text-dark d-flex flex-column align-items-center justify-content-center w-100 h-100 position-absolute top-0 bottom-0">
                                    <p>
                                        <i class="fas fa-exclamation-circle fa-md me-1"></i>
                                        <span>Device not plugged</span>
                                    </p>
                                    <a href="devices.php" class="rounded-1 btn btn-sm btn-sh mt-0">
                                        <i class="fas fa-plug fa-lg me-2"></i>
                                        <span>Plug device</span>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-4 mb-3">
                        <!-- History -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5 pb-0">History</h2>
                                <a href="#" class="text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#historyModal">View All</a>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <?php
                                    $stmt = $db->prepare("SELECT * FROM logs INNER JOIN devices ON logs.device_id = devices.id WHERE devices.user_id = ? ORDER BY logs.id DESC LIMIT 4");
                                    $stmt->execute([$user_id]);
                                    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $logs_user = [
                                        'producers' => [],
                                        'consumers' => [],
                                    ];
                                    foreach ($logs as $log) {
                                        $device_name = $log['name'];
                                        $device_type = $log['type'];
                                        $device_status = $log['status'];
                                        $log_action = $log['action'];
                                        $log_user_id = $log['user_id'];
                                        $log_table = $log['user_type'] =='producers' ? 'producers' : 'consumers';
                                        if ($log_action == 1) {
                                            $log_action_text = "turned on";
                                        } else {
                                            $log_action_text = "turned off";
                                        }
                                        if(!isset($logs_user[$log_table][$log_user_id])) {
                                            $stmt = $db->prepare("SELECT * FROM `".$log_table."` WHERE id = ?");
                                            $stmt->execute([$log_user_id]);
                                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                            $logs_user[$log_table][$log_user_id] = $user['name'];
                                        }
                                        ?>
                                    <li class="d-flex mb-2 align-items-start">
                                        <span class="me-2 text-<?php echo ($log_action == 1) ? "sh" : "light"; ?> shadow-sm"><i class="fas fa-circle fa-sm"></i></span>
                                        <div class="d-flex flex-column justify-content-center">
                                            <strong><?php echo $device_name; ?></strong>
                                            <div><?php echo $log_action_text; ?> by <span class="text-muted"><?php echo $logs_user[$log_table][$log_user_id]; ?></span></div>
                                        </div>
                                        <date class="ms-auto text-secondary"><?php echo diffForHumans($log['created_at']); ?></date>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mb-3">
                        <div class="card bg-white-50 rounded-4 h-100 consumption">
                            <div class="d-flex justify-content-between align-items-center  p-2 px-3">
                                <h2 class="h5">Consumption</h2>
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item">
                                        <a href="#electric" data-consumption="Electric"
                                            class="nav-link text-dark">Electric</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#water" data-consumption="Water" class="nav-link text-dark">Water</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#gas" data-consumption="Gas" class="nav-link text-dark">Gas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#all" data-consumption="all" class="nav-link active btn-sh">All</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <canvas id="consumptionChart" width="100%" height="146"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/historyModal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>