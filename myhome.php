<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['user'])) {
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
                                            5 KWh
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
                                            10 L
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
                                            5 M3
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 mb-3">
                                <!-- Outdoor Lock Status -->
                                <div class="card bg-white-50 rounded-4 h-100">
                                    <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                        <h2 class="h6 pb-0">Outdoor Lock</h2>
                                        <span class="fs-4 m-auto">
                                            <i class="fas fa-lg text-success fa-lock-open"></i>
                                            Open
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
                        <div class="d-flex security-cam" data-cams="[
                        {
                            &quot;name&quot;: &quot;Camera 1&quot;,
                            &quot;src&quot;: &quot;img/camera1.jpg&quot;
                        },
                        {
                            &quot;name&quot;: &quot;Camera 2&quot;,
                            &quot;src&quot;: &quot;img/camera2.jpg&quot;
                        },
                        {
                            &quot;name&quot;: &quot;Camera 3&quot;,
                            &quot;src&quot;: &quot;img/camera3.jpg&quot;
                        }
                        ]">
                            <div class="position-absolute too-0 start-0 mt-2 ms-4">
                                <span class="badge bg-white-50">
                                    <i class="fas fa-video fa-lg text-dark"></i>
                                    <span class="text-dark">Camera 1</span>
                                </span>
                            </div>
                            <img src="img/camera.jpg" alt="" class="w-100 rounded-4">
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
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Main Lamp</span>
                                    <label class="switch">
                                        <input type="checkbox" class="apple-switch" checked>
                                    </label>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Bedroom Lamp</span>
                                    <label class="switch">
                                        <input type="checkbox" class="apple-switch">
                                    </label>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Kitchen Lamp</span>
                                    <label class="switch">
                                        <input type="checkbox" class="apple-switch" checked>
                                    </label>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Bathroom Lamp</span>
                                    <label class="switch">
                                        <input type="checkbox" class="apple-switch">
                                    </label>
                                </li>
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
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Main Door</span>
                                    <span class="badge bg-success rounded-pill p-2">
                                        <i class="fas fa-lock fa-lg"></i>
                                        <span>Locked</span>
                                    </span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Exit Door</span>
                                    <span class="badge bg-danger rounded-pill p-2">
                                        <i class="fas fa-lock-open fa-lg"></i>
                                        <span>Unlocked</span>
                                    </span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Balcony Door</span>
                                    <span class="badge bg-success rounded-pill p-2">
                                        <i class="fas fa-lock fa-lg"></i>
                                        <span>Locked</span>
                                    </span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center p-2 px-3">
                                    <span class="fs-5">Garage Door</span>
                                    <span class="badge bg-success rounded-pill p-2">
                                        <i class="fas fa-lock fa-lg"></i>
                                        <span>Locked</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card bg-white-50 rounded-4 h-100 speaker">
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
                                    <button class="btn btn-sm fa-2x btn-play">
                                        <i class="fas fa-play-circle fa-2x text-sh"></i>
                                    </button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-next"><i
                                            class="fas fa-forward fs-5"></i></button>
                                    <button class="btn btn-sm rounded-4 my-auto d-flex align-items-center btn-toggle"><i
                                            class="fas fa-redo fs-5"></i></button>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-sm rounded-circle mr-2 btn-mute">
                                        <i class="fas fa-volume-up"></i>
                                    </button>
                                    <input type="range" class="form-range volume-range" min="0" max="100" value="50"
                                        style="max-width: 220px">
                                </div>
                            </div>
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
                                    <!-- action 1 -->
                                    <li class="d-flex mb-2 align-items-start">
                                        <span><i class="fas fa-circle fa-sm text-success me-2"></i></span>
                                        <div class="d-flex flex-column justify-content-center">
                                            <strong>Smart TV</strong>
                                            <div>turned on by <span class="text-muted">Test User</span></div>
                                        </div>
                                        <date class="ms-auto text-secondary">2 day ago</date>
                                    </li>
                                    <!-- action 2 -->
                                    <li class="d-flex mb-2 align-items-start">
                                        <span><i class="fas fa-circle fa-sm text-success me-2"></i></span>
                                        <div class="d-flex flex-column justify-content-center">
                                            <strong>Smart TV</strong>
                                            <div>turned on by <span class="text-muted">Test User</span></div>
                                        </div>
                                        <date class="ms-auto text-secondary">2 day ago</date>
                                    </li>
                                    <!-- action 3 -->
                                    <li class="d-flex mb-2 align-items-start">
                                        <span><i class="fas fa-circle fa-sm text-danger me-2"></i></span>
                                        <div class="d-flex flex-column">
                                            <strong>Air Conditioner</strong>
                                            <div>turned off by <span class="text-muted">Test User</span></div>
                                        </div>
                                        <date class="ms-auto text-secondary">2 day ago</date>
                                    </li>
                                    <!-- action 4 -->
                                    <li class="d-flex align-items-start">
                                        <span><i class="fas fa-circle fa-sm text-danger me-2"></i></span>
                                        <div class="d-flex flex-column">
                                            <strong>Lamps</strong>
                                            <div>turned off by <span class="text-muted">Test User</span></div>
                                        </div>
                                        <date class="ms-auto text-secondary">2 day ago</date>
                                    </li>
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