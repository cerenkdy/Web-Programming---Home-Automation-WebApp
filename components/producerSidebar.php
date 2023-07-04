<?php
// If the user is not logged in, redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit;
}

// variables
$user_id = intval($_SESSION['user']);

if(!isset($room_ids)) {
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
}

// consumers
$stmt = $db->prepare("SELECT * FROM consumers WHERE deleted_at IS NULL");
$stmt->execute();
$get_consumers = $stmt->fetchAll(PDO::FETCH_ASSOC);
$consumers = [];
foreach($get_consumers as $consumer) {
    $consumers[$consumer['id']] = $consumer;
}
?>

<!-- Sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-light sidebar">
    <div class="d-flex align-items-center mb-3 mb-md-0 px-md-3">
        <button class="btn btn-sm p-0 m-0" id="sidebarToggle" type="button">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <a href="producer.php" class="fs-5 text-dark text-decoration-none ms-2"><?php echo $name; ?></a>
    </div>
    <nav class="d-flex flex-column flex-fill">
        <br>
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Select Consumer -->
            <li class="nav-item dropdown border-top border-bottom mb-2 pb-2">
                <a href="#" class="nav-link text-dark d-flex justify-content-betsween align-items-center dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-friends fa-lg me-2"></i>
                    <span class="text-truncate me-auto ms-1"><?php echo ($user_id && isset($consumers[$user_id]['name'])) ? $consumers[$user_id]['name'] : 'Select Consumer'; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-top" aria-labelledby="dropdownMenuButton1">
                    <?php
                    // get consumers
                    foreach($consumers as $consumer) {
                        echo '<li><a class="dropdown-item" href="?user=' . $consumer['id'] . '">' . $consumer['name'] . '</a></li>';
                    }
                    ?>
                </ul>
            </li>
            <li class="nav-item">
                <a href="producer.php" class="nav-link text-dark<?php echo $page == 'myhome' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-home fa-lg me-2"></i>
                    <span>Producer Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="producer_consumers.php" class="nav-link text-dark<?php echo $page == 'consumers' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-users fa-lg me-2"></i>
                    <span>Consumers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="producer_devices.php" class="nav-link text-dark<?php echo $page == 'devices' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-microchip fa-lg me-2"></i>
                    <span>Devices</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="producer_rooms.php" class="nav-link text-dark<?php echo $page == 'rooms' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-door-open fa-lg me-2"></i>
                    <span>Rooms</span>
                </a>
            </li>
        </ul>
        <!-- Account -->
        <div class="dropdown">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item d-flex align-items-center">
                    <a href="#" class="nav-link text-dark send-data-btn" data-id="<?php echo $user_id; ?>" data-rooms="<?php echo htmlentities($room_ids); ?>">
                        <i class="fas fa-share fa-lg me-2"></i>
                        <span>Send Data</span>
                    </a>
                    <span class="sensor-toggler fs-5"><input type="checkbox" class="apple-switch ms-4" <?php echo (isset($_COOKIE['sensor-disabled']) && $_COOKIE['sensor-disabled'] == '1') ? '' : 'checked'; ?>></span>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a href="#" class="nav-link d-flex align-items-center text-dark text-decoration-none">
                        <img src="https://avatars.githubusercontent.com/u/128895754?v=4" alt="" width="24"
                            height="24" class="rounded-circle me-2">
                        <strong><?php echo $_SESSION['name']; ?></strong>
                    </a>
                    <a href="?logout=true" class="text-dark ms-auto me-2">
                        <i class="fas fa-sign-out-alt fa-lg fa-flip-horizontal"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>