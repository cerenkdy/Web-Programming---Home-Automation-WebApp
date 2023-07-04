<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['consumer_login'])) {
    header("Location: login.php?type=consumers");
    exit;
}

// vars
$room_id = intval($_GET['id']);
$user_id = intval($_SESSION['user']);

// get room data
$stmt = $db->prepare("SELECT * FROM rooms WHERE id = ? AND user_id = ?");
$stmt->execute([$room_id, $user_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
$room['data'] = @json_decode($room['data'], true);
if (!$room['data']) {
    $room['data'] = [];
}

// if room not found, redirect to rooms.php
if (!isset($room['id'])) {
    header("Location: rooms.php");
    exit;
}

// have devices
$have = [];
foreach ($device_group as $device_key => $device_data) {
    $have[$device_key] = false;
}

// get devices
$stmt = $db->prepare("SELECT * FROM devices WHERE room_id = ? AND user_id = ?");
$stmt->execute([$room_id, $user_id]);
$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($devices as $device) {
    if($have[$device['type']] !== false){
        continue;
    }
    $have[$device['type']] = $device;
    $have[$device['type']]['data'] = @json_decode($device['data'], true);
}

// get camera datas
$cameras = [];
$stmt = $db->prepare("SELECT id, name FROM devices WHERE room_id = ? AND user_id = ? AND type = 'camera' AND status = '1'");
$stmt->execute([$room_id, $user_id]);
$camCounter = 0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $camCounter++;
    $cameras[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'src' => 'img/camera'.$camCounter.'.jpg',
    ];
    if ($camCounter == 3) {
        $camCounter = 0;
    }
}

// get sensor datas
$sensorLimit = 7;
$sensors = [
    'temperature' => array_fill(0, $sensorLimit, 0),
    'humidity' => array_fill(0, $sensorLimit, 0),
];
$stmt = $db->prepare("SELECT temperature, humidity FROM sensor_data WHERE room_id = ? AND user_id = ? ORDER BY id DESC LIMIT 7");
$stmt->execute([$room_id, $user_id]);
$sensorCounter = $sensorLimit - 1;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sensors['temperature'][$sensorCounter] = $row['temperature'];
    $sensors['humidity'][$sensorCounter] = $row['humidity'];
    $sensorCounter--;
}

// room current temperature and humidity
$roomTemperature = @end($sensors['temperature']);
$roomHumidity = @end($sensors['humidity']);
if (!$roomTemperature) {
    $roomTemperature = $room['data']['temperature'] + rand(-2, 2);
} else {
    $roomTemperature = rand(18, 30);
}
if (!$roomHumidity) {
    $roomHumidity = $room['data']['humidity'] + rand(-2, 2);
} else {
    $roomHumidity = rand(30, 80);
}

// page
$page = 'rooms';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($room['name']) ?> | <?php echo $name; ?></title>
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
        <!-- Room -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 mw-100">
            <div class="room w-100">
                <div class="d-flex align-items-center mb-3">
                    <a href="rooms.php" class="text-decoration-none text-light me-3">
                        <i class="fas fa-chevron-left fa-lg"></i>
                    </a>
                    <h4 class="flex-grow-1 mr-2 mb-0 fw-normal text-white"><?php echo htmlspecialchars($room['name']) ?></h4>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-sh dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-door-open fa-lg me-2"></i>
                            <span><?php echo htmlspecialchars($room['name']) ?></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <?php
                            $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
                            $stmt->execute([$user_id]);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li><a class="dropdown-item" href="room.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <button class="btn btn-sm text-white ms-2 edit-room-btn" type="button" data-id="<?php echo $room['id']; ?>">
                        <i class="fas fa-ellipsis-v fa-lg"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Lamps -->
                        <div class="card bg-white-50 rounded-4 h-100 p-3 pe-2 pb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb fa-lg me-2"></i>
                                <h2 class="h5 m-0 me-2">Lamps</h2>
                                <button class="btn btn-sm btn-sh ms-auto" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addLampModal">
                                    <i class="fas fa-plus fa-lg"></i>
                                </button>
                            </div>
                            <ul class="list-unstyled p-0 m-0 overflow-auto pe-1 mt-2" style="max-height: 170px">
                                <?php
                                foreach ($devices as $device) {
                                    if ($device['type'] == 'light') {
                                        $deviceSettings = json_decode($device['data'], true);
                                        $device['color'] = isset($deviceSettings['color']) ? $deviceSettings['color'] : '#ffffff';
                                ?>
                                    <li class="d-flex align-items-center mb-2 mt-1">
                                        <i class="fas fa-circle fa-lg me-2" style="color: <?php echo $device['color']; ?>"></i>
                                        <span class="fs-5 me-2"><?php echo htmlspecialchars($device['name']); ?></span>
                                        <button class="btn btn-sm p-1 px-2 btn-sh me-2 edit-lamp-btn" type="button" data-id="<?php echo $device['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <label class="switch ms-auto">
                                            <input type="checkbox" class="apple-switch sh-switch" onchange="deviceStatus(<?php echo $device['id']; ?>, this.checked)" <?php if ($device['status'] == '1') { echo ' checked'; } ?>>
                                        </label>
                                    </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Temperature -->
                        <div class="bg-white-50 rounded-4 p-3 me-3 w-100 h-100 shadow-sm temperature" data-room="<?php echo $room['id']; ?>" data-temperature="<?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>" data-chart="<?php echo json_encode($sensors['temperature']) ?>">
                            <div class="d-flex mb-3 align-items-center justify-content-start">
                                <i class="fas fa-thermometer-half fa-lg me-2"></i>
                                <h5 class="mb-0 me-2">Temperature</h5>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'temperature_status', this.checked?'1':'0')"<?php if (isset($room['data']['temperature_status']) && $room['data']['temperature_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column me-auto">
                                    <span class="fs-1 fs-2"><?php echo $roomTemperature; ?>&deg;</span>
                                </div>
                                <span class="set-text me-2 rounded btn-sh p-1 py-3 shadow-sm h1 h2 mb-0 fw-normal"><?php echo (isset($room['data']['temperature']) ? $room['data']['temperature'] : 25); ?>&deg;</span>
                                <div class="d-flex flex-column h-100">
                                    <button class="btn btn-sm btn-sh mb-1 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh mt-1 decrease" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-auto">
                                <canvas id="temperatureChart" width="100%" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Humidifier -->
                        <div class="bg-white-50 rounded-4 p-3 w-100 h-100 shadow-sm humidity" data-room="<?php echo $room['id']; ?>"  data-humidity="<?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>" data-chart="<?php echo json_encode($sensors['humidity']) ?>">
                            <div class="d-flex mb-3 align-items-center">
                                <i class="fa fa-tint fa-lg me-2"></i>
                                <h5 class="h5 me-2 mb-0">Humidifier</h5>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="setRoomData(<?php  echo $room['id']; ?>, 'humidity_status', this.checked?'1':'0')"<?php if (isset($room['data']['humidity_status']) && $room['data']['humidity_status'] == '1') { echo ' checked'; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-column me-auto">
                                    <span class="fs-1 fs-2"><?php echo $roomHumidity; ?>%</span>
                                </div>
                                <span class="set-text me-2 rounded btn-sh p-1 py-3 shadow-sm h1 h2 mb-0 fw-normal"><?php echo (isset($room['data']['humidity']) ? $room['data']['humidity'] : 50); ?>%</span>
                                <div class="d-flex flex-column">
                                    <button class="btn btn-sm btn-sh mb-1 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh mt-1 decrease" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-auto">
                                <canvas id="humidityChart" width="100%" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($cameras) && count($cameras) > 0) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Camera -->
                        <div class="card bg-white-50 rounded-4 shadow-sm h-100">
                            <div class="d-flex position-relative security-cam static-cam overflow-hidden position-relative" data-id="<?php echo (isset($cameras[0])) ? $cameras[0]['id'] : ''; ?>">
                                <div class="position-absolute top-0 start-0 end-0 mt-2 ms-3 me-3 d-flex flex-row">
                                    <h2 class="h5 text-white-50 mb-0 cam-name">
                                        <button class="btn btn-sm me-2 badge bg-white-50 text-dark fw-normal"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="name"><?php echo (isset($cameras[0])) ? $cameras[0]['name'] : 'No Camera'; ?></span>
                                            <i class="fas fa-chevron-down fa-sm"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php
                                            $camImg = 1;
                                            foreach ($cameras as $camKey => $camera) {
                                                echo '<li><a class="dropdown-item'. ($camKey == 0 ? ' active btn-sh' : '') . '" href="#" data-id="' . $camera['id'] . '" data-src="img/camera' . $camImg . '.jpg">' . htmlentities($camera['name']) . '</a></li>';
                                                $camImg++;
                                                if ($camImg > 3) {
                                                    $camImg = 1;
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </h2>
                                    <button class="btn btn btn-sm btn-sh ms-auto edit-cam-btn">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh ms-2 add-cam-btn">
                                        <i class="fas fa-plus fa-lg"></i>
                                    </button>
                                </div>
                                <div
                                    class="position-absolute bottom-0 start-0 end-0 mb-2 ms-3 me-3 d-flex flex-row flex-wrap justify-content-center">
                                    <button class="btn btn-sm btn-sh" data-cam="prev">
                                        <i class="fas fa-chevron-left fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh ms-2" data-cam="next">
                                        <i class="fas fa-chevron-right fa-lg"></i>
                                    </button>
                                </div>
                                <img src="img/camera.jpg" alt="" class="w-100 rounded-4">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($have['window']) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <div class="bg-white-50 h-100 rounded-4 shade-control position-relative" data-id="<?php echo (isset($have['window']['id']) ? $have['window']['id'] : 0); ?>" data-shade="<?php echo (isset($have['window']['data']['shade']) ? $have['window']['data']['shade'] : 50); ?>">
                            <div class="d-flex align-items-center p-2 px-3 pb-3">
                                <i class="fas fa-person-booth fa-lg me-2"></i>
                                <h2 class="h5 mb-0">Shading Control</h2>
                            </div>
                            <div class="d-flex align-items-center px-3 pb-3">
                                <div class="d-flex flex-column flex-fill me-4">
                                    <span class="fs-1"><?php echo (isset($have['window']['data']['shade']) ? $have['window']['data']['shade'] : 50); ?>%</span>
                                    <input type="range" class="form-range ms-2 me-2" min="0" max="100" step="1"
                                        value="<?php echo (isset($have['window']['data']['shade']) ? $have['window']['data']['shade'] : 50); ?>">
                                </div>
                                <div class="d-flex flex-column ms-auto">
                                    <button class="btn btn-sm btn-sh mb-2 increase" type="button">
                                        <i class="fas fa-chevron-up fa-lg"></i>
                                    </button>
                                    <button class="btn btn-sm btn-sh decrease" type="button">
                                        <i class="fas fa-chevron-down fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($have['router']) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Wifi -->
                        <div class="bg-white-50 h-100 rounded-4 room-wifi position-relative">
                            <div class="d-flex align-items-center p-2 px-3 pb-0">
                                <i class="fas fa-wifi fa-lg text-dark me-2"></i>
                                <h2 class="h5 mb-0 me-2">Wi-Fi</h2>
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button" data-id="<?php echo (isset($have['router']) && $have['router']) ? $have['router']['id'] : '0'; ?>" data-status="<?php echo (isset($have['router']) && $have['router']['status'] == 1) ? '1' : '0'; ?>">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center px-3">
                                <div class="d-flex flex-column flex-fill my-3 pb-1">
                                    <span class="fs-4 text-muted"><?php echo (isset($have['router']) && isset($have['router']['status']) && $have['router'] && $have['router']['status'] == 1) ? 'Connected' : 'Disconnected'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($have['ac']) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Air Conditioner -->
                        <div class="bg-white-50 h-100 rounded-4 position-relative air-conditioner d-flex flex-column" data-id="<?php echo (isset($have['ac']['id']) ? $have['ac']['id'] : 0); ?>">
                            <div class="d-flex align-items-center p-2 px-3">
                                <i class="fa fa-snowflake fa-lg me-2"></i>
                                <h2 class="h5 mb-0">Air Conditioner</h2>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="deviceStatus(<?php echo $have['ac']['id']; ?>, this.checked)" <?php if(isset($have['ac']['status']) && $have['ac']['status'] == 1) { echo "checked"; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center justify-content-center px-3 h-100">
                                <div class="d-flex align-items-center justify-content-start gap-2 w-100 mb-2">
                                    <?php foreach($device_group['ac']['modes'] as $mode_code => $mode) { ?>
                                    <button style="width: 66px" class="btn btn-<?php echo (isset($have['ac']['data']['mode']) && $have['ac']['data']['mode'] == $mode_code) ? 'sh' : 'light'; ?> rounded-2 btn-mode d-flex flex-column justify-content-center gap-1 p-1 align-items-center" data-mode="<?php echo $mode_code; ?>">
                                        <i class="<?php echo $mode['icon']; ?> mt-2"></i>
                                        <?php echo $mode['name']; ?>
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($have['tv']) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- TV control -->
                        <div class="card bg-white-50 h-100 rounded-4 tv-control position-relative flex-fill" data-id="<?php echo (isset($have['tv']['id']) ? $have['tv']['id'] : 0); ?>" data-channel="<?php echo (isset($have['tv']['data']['channel']) ? $have['tv']['data']['channel'] : 1); ?>" data-volume="<?php echo (isset($have['tv']['data']['volume']) ? $have['tv']['data']['volume'] : 50); ?>">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <i class="fas fa-tv fa-lg text-dark me-2"></i>
                                <h2 class="h5 mb-0 me-auto">TV Control</h2>
                                <!-- On/Off -->
                                <button class="btn btn-sm text-dark ms-auto on-off-btn" type="button" data-id="<?php echo (isset($have['tv'])) ? $have['tv']['id'] : '0'; ?>" data-status="<?php echo (isset($have['tv']) && $have['tv']['status'] == 1) ? '1' : '0'; ?>">
                                    <i class="fas fa-power-off fa-lg"></i>
                                </button>
                            </div>
                            <div class="card-body on<?php echo (isset($have['tv']) && $have['tv']['status'] == 1) ? '' : ' d-none'; ?>">
                                <!-- Channel -->
                                <div class="d-flex flex-row align-items-center mb-3">
                                    <h5 class="flex-grow-1 mb-0">Channel <span class="channel-name"><?php echo (isset($have['tv']['data']['channel']) ? $have['tv']['data']['channel'] : 1); ?></span></h5>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-dark decrease" type="button">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-dark increase" type="button">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <i class="fas fa-volume-up fa-lg text-dark me-2"></i>
                                    <input type="range" class="form-range" min="0" max="100" step="1" value="<?php echo (isset($have['tv']['data']['volume']) ? $have['tv']['data']['volume'] : 50); ?>">
                                </div>
                            </div>
                            <div class="card-body mt-auto d-flex off justify-content-start align-items-end text-muted<?php echo (isset($have['tv']) && $have['tv']['status'] == 1) ? ' d-none' : ''; ?>">
                                Off
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if(isset($room['data']['fireco_status']) && $room['data']['fireco_status'] == '1') { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Fire/CO Alarm -->
                        <div class="card bg-white-50 h-100 rounded-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center p-2 px-3">
                                <h2 class="h5 mb-0">Fire/CO Alarm</h2>
                                <i class="fas fa-fire fa-lg text-dark"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="d-flex flex-row align-items-center">
                                        <?php if(isset($room['data']['fireco']) && $room['data']['fireco'] == 1 && isset($room['data']['fireco_status']) && $room['data']['fireco_status'] == '1') { ?>
                                        <i class="fas fa-exclamation-triangle fa-lg text-danger me-2"></i>
                                        <span class="fs-4 text-danger">Fire/Gas detected</span>
                                        <?php } else { ?>
                                            <i class="fas fa-smog fa-2x text-dark me-2"></i>
                                            <span class="text-dark">No smoke detected</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM devices WHERE room_id = ? AND user_id = ? AND type = 'speaker' ORDER BY id ASC LIMIT 1");
                    $stmt->execute([$room_id, $user_id]);
                    if ($stmt->rowCount() > 0) {
                        $speaker = $stmt->fetch(PDO::FETCH_ASSOC);
                        $speaker_data = @json_decode($speaker['data'], true);
                        $speaker_id = $speaker['id'];
                        $speaker_status = $speaker_data['status'];
                        $speaker_volume = $speaker_data['volume'];
                        ?>
                        <div class="col-xl-3 col-lg-6 mb-3">
                            <div class="card bg-white-50 rounded-4 h-100 speaker position-relative">
                                <div class="d-flex align-items-center p-2 px-3">
                                    <i class="fas fa-music fa-lg text-dark me-2"></i>
                                    <h2 class="h5 mb-0 me-2">Speaker</h2>
                                    <div class="ms-auto d-flex align-items-center justify-content-center">
                                        <button class="btn btn-sm rounded-circle mr-2 btn-mute">
                                            <i class="fas fa-lg <?php if(isset($speaker_volume) && $speaker_volume < 1) { echo "fa-volume-mute"; } else { echo "fa-volume-up"; } ?>"></i>
                                        </button>
                                        <input type="range" class="form-range volume-range" min="0" max="100" data-id="<?php echo isset($speaker_id) ? $speaker_id : 0; ?>" value="<?php echo (isset($speaker_volume)) ? $speaker_volume : 100; ?>" step="1" style="max-width: 125px">
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-row w-100">
                                    <img src="https://i.scdn.co/image/ab67616d0000b273b6d4566db0d12894a1a3b7a2" alt=""
                                        class="rounded-2 shadow object-fit-cover song-cover" width="50" height="60">
                                    <div class="ms-3 d-flex flex-column" style="max-width: calc(100% - 150px)">
                                        <span class="song-title text-truncate">Undisclosed Desires</span>
                                        <span class="text-muted song-artist text-truncate">Muse</span>
                                        <span class="text-muted song-album text-truncate">The Resistance</span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-2 ms-auto">
                                        <button class="btn btn-sm p-0 rounded-4 my-auto d-flex align-items-center btn-prev"><i
                                                class="fas fa-backward"></i></button>
                                        <button class="btn btn-sm p-0 btn-play" data-id="<?php echo isset($speaker_id) ? $speaker_id : 0; ?>">
                                            <i class="fas text-sh fs-1 <?php if(isset($speaker_volume) && $speaker_status == 1) { echo "fa-pause-circle"; } else { echo "fa-play-circle"; } ?>"></i>
                                        </button>
                                        <button class="btn btn-sm p-0 rounded-4 my-auto d-flex align-items-center btn-next"><i
                                                class="fas fa-forward"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($have['refrigerator']) { ?>
                    <div class="col-xl-3 col-lg-6 mb-3">
                        <!-- Refrigerator -->
                        <div class="bg-white-50 h-100 rounded-4 position-relative refrigerator d-flex flex-column" data-id="<?php echo $have['refrigerator']['id']; ?>">
                            <div class="d-flex align-items-center p-2 px-3">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" width="24px" height="24px"><path d="M70,70L65,70L65,60L25,60L25,85C25,87.761,27.239,90,30,90L70,90C72.761,90,75,87.761,75,85L75,60L70,60L70,70Z" stroke="none"></path><path d="M65,45L70,45L70,55L75,55L75,15C75,12.239,72.761,10,70,10L30,10C27.239,10,25,12.239,25,15L25,55L65,55L65,45Z" stroke="none"></path></svg>
                                <h2 class="h5 mb-0">Refrigerator</h2>
                                <label class="switch ms-auto d-flex align-items-center justify-content-start">
                                    <input type="checkbox" class="apple-switch sh-switch" onchange="deviceStatus(<?php echo $have['refrigerator']['id']; ?>, this.checked)" <?php if(isset($have['refrigerator']['data']['status']) && $have['refrigerator']['data']['status'] == 1) { echo "checked"; } ?>>
                                </label>
                            </div>
                            <div class="d-flex align-items-center justify-content-center px-3 h-100">
                                <div class="d-flex align-items-center justify-content-start gap-2 w-100 mb-2">
                                    <?php foreach($device_group['refrigerator']['modes'] as $mode_code => $mode) { ?>
                                    <button style="width: 66px" class="btn btn-<?php echo (isset($have['refrigerator']['data']['mode']) && $have['refrigerator']['data']['mode'] == $mode_code) ? 'sh' : 'light'; ?> rounded-2 btn-mode d-flex flex-column justify-content-center gap-1 p-1 align-items-center" data-mode="<?php echo $mode_code; ?>" data-temperature="<?php echo $mode['temperature']; ?>">
                                        <i class="<?php echo $mode['icon']; ?> mt-2"></i>
                                        <?php echo $mode['name']; ?>
                                    </button>
                                    <?php } ?>
                                    <span class="set-text ms-auto h1 h2 mb-0 fw-normal"><?php echo $device_group['refrigerator']['modes'][$have['refrigerator']['data']['mode']]['temperature']; ?>&deg;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="d-flex align-items-center mb-3 mt-3">
                        <h5 class="flex-grow-1 mr-2 mb-0 text-white fw-bold h5">Devices</h5>
                        <button class="btn btn-sm text-white add-device-btn" data-room="<?php echo $room_id; ?>">
                            <i class="fas fa-plus fa-lg"></i>
                        </button>
                        <!-- toggle menu -->
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm text-white" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item add-device-btn" href="#" data-room="<?php echo $room_id; ?>">Add Device</a></li>
                                <li><a class="dropdown-item edit-room-btn" href="devices.php#room-<?php echo $room_id; ?>">Show All Devices</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <!-- device list -->
                        <div class="row d-flex">
                            <?php
                            $deviceCounter = 0;
                            $skipFirstModuleDevices = [];
                            foreach ($devices as $device) {
                                // skip lights
                                if($device['type'] == 'light') {
                                    continue;
                                }

                                /*
                                / skip first module devices
                                if(in_array($device['type'], ['speaker', 'ac', 'tv']) && !in_array($device['type'], $skipFirstModuleDevices)) {
                                    $skipFirstModuleDevices[] = $device['type'];
                                    continue;
                                }
                                */

                                // get device data
                                $device_id = intval($device['id']);
                                $device_name = $device['name'];
                                $device_status = $device['status'];
                                $device_type = $device['type'];
                                $device = $device_group[$device_type];
                                $deviceCounter++;
                            ?>
                            <div class="col-xl-2 col-md-4 col-sm-6 col-xs-12 room-device mb-3">
                                <div class="bg-white-50 shadow-sm p-3 rounded-4 d-flex flex-column">
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
                            </div>
                            <?php } ?>
                            <?php if ($deviceCounter == 0) { ?>
                            <div class="col-xl-12 d-flex">
                                <div class="bg-white-50 d-flex align-items-center justify-content-center shadow-sm p-3 px-4 rounded-4 mr-auto">
                                    <i class="fas fa-exclamation-circle fa-1x me-2 text-muted"></i> No device found
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/editLampModal.php';
    include 'components/addDeviceModal.php';
    include 'components/editRoomModal.php';
    include 'components/addLampModal.php';
    include 'components/editCamModal.php';
    include 'components/addCamModal.php';
    include 'components/editDeviceDataModal.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>