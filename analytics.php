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
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data = json_decode($row['data'], true);
    if(isset($data['temperature']) && $data['temperature'] > 0 && isset($data['temperature_status']) && $data['temperature_status'] == '1') {
        $temperatures[] = $data['temperature'];
    }
}

// page
$page = 'analytics';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics | <?php echo $name; ?></title>
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
                    <h4 class="text-light">Analytics</h4>
                    <!-- Period -->
                    <div class="btn-group">
                        <button class="btn btn-sm btn-sh dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-calendar-alt fa-lg me-2"></i>
                            <span>
                                <?php
                                if(!isset($_GET['period'])) {
                                    $_GET['period'] = 'all';
                                }
                                switch ($_GET['period']) {
                                    case 'today':
                                        echo 'Today';
                                        break;
                                    case 'yesterday':
                                        echo 'Yesterday';
                                        break;
                                    case 'week':
                                        echo 'This Week';
                                        break;
                                    case 'month':
                                        echo 'This Month';
                                        break;
                                    case 'year':
                                        echo 'This Year';
                                        break;
                                    default:
                                        echo 'Period';
                                        $_GET['period'] = 'all';
                                        break;
                                    }
                                ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item<?php if($_GET['period'] == 'today') { echo ' active'; } ?>" href="analytics.php?period=today">Today</a></li>
                            <li><a class="dropdown-item<?php if($_GET['period'] == 'yesterday') { echo ' active'; } ?>" href="analytics.php?period=yesterday">Yesterday</a></li>
                            <li><a class="dropdown-item<?php if($_GET['period'] == 'week') { echo ' active'; } ?>" href="analytics.php?period=week">This Week</a></li>
                            <li><a class="dropdown-item<?php if($_GET['period'] == 'month') { echo ' active'; } ?>" href="analytics.php?period=month">This Month</a></li>
                            <li><a class="dropdown-item<?php if($_GET['period'] == 'all') { echo ' active'; } ?>" href="analytics.php">This Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
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
                                    <span class="water-usage"><?php echo rand(0, 5) . ' m<sup>3</sup>'; ?></span>
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
                                    <span class="gas-usage"><?php echo rand(0, 5) . ' m<sup>3</sup>'; ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <!-- Temperature -->
                        <div class="card bg-white-50 rounded-4 h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start p-3">
                                <h2 class="h6 pb-0">Temperature</h2>
                                <span class="fs-4 m-auto">
                                    <i class="fas fa-lg text-info fa-thermometer-half"></i>
                                    <?php
                                    echo (count($temperatures) > 0 ? round(array_sum($temperatures) / count($temperatures), 1) : 0) . '&deg;';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap">
                    <div class="col-lg-12 mb-3">
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
                                <canvas id="consumptionChart" width="100%" height="400" data-period="<?php echo htmlentities($_GET['period']); ?>"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/app.js"></script>
</body>

</html>