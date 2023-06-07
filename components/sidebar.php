<?php
// If the user is not logged in, redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!-- Sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-light sidebar">
    <div class="d-flex align-items-center mb-3 mb-md-0 px-md-3">
        <button class="btn btn-sm p-0 m-0" id="sidebarToggle" type="button">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <a href="myhome.php" class="fs-5 text-dark text-decoration-none ms-2"><?php echo $name; ?></a>
    </div>
    <nav class="d-flex flex-column flex-fill">
        <br>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="myhome.php" class="nav-link text-dark<?php echo $page == 'myhome' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-home fa-lg me-2"></i>
                    <span>My Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="rooms.php" class="nav-link text-dark<?php echo $page == 'rooms' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-door-open fa-lg me-2"></i>
                    <span>Rooms</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="devices.php" class="nav-link text-dark<?php echo $page == 'devices' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-microchip fa-lg me-2"></i>
                    <span>Devices</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="analytics.php" class="nav-link text-dark<?php echo $page == 'analytics' ? ' fw-bold' : ''; ?>">
                    <i class="fas fa-chart-line fa-lg me-2"></i>
                    <span>Analytics</span>
                </a>
            </li>
        </ul>
        <!-- Account -->
        <div class="dropdown">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="settings.php" class="nav-link text-dark<?php echo $page == 'settings' ? ' fw-bold' : ''; ?>">
                        <i class="fas fa-cog fa-lg me-2"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a href="settings.php" class="nav-link d-flex align-items-center text-dark text-decoration-none">
                        <img src="https://avatars.githubusercontent.com/u/128895754?v=4" alt="" width="24"
                            height="24" class="rounded-circle me-2">
                        <strong><?php echo $_SESSION['name']; ?></strong>
                    </a>
                    <a href="myhome.php?logout=true" class="text-dark ms-auto me-2">
                        <i class="fas fa-sign-out-alt fa-lg fa-flip-horizontal"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>