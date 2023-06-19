<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php
if (!isset($_SESSION['consumer_login'])) {
    header("Location: login.php");
    exit;
}

// page
$page = 'settings';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings | <?php echo $name; ?></title>
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
        <!-- Settings -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-white">Settings</h4>
                </div>
                <div class="row flex-row flex-wrap">
                    <!-- Account -->
                    <div class="col-md-6">
                        <div class="card rounded-4 shadow-sm mb-3 bg-white-50">
                            <form action="" class="card-body d-flex flex-column" method="post">
                                <h2 class="h5 mb-3">Account</h2>
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" placeholder="Full Name">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email">
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" placeholder="Username">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" placeholder="Password">
                                </div>
                                <div class="mb-4">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmPassword"
                                        placeholder="Confirm Password">
                                </div>
                                <button type="submit" class="btn btn-sh w-100 py-2 mt-auto">Save</button>
                            </form>
                        </div>
                    </div>
                    <!-- System -->
                    <div class="col-md-6">
                        <div class="card rounded-4 shadow-sm mb-3 bg-white-50">
                            <form action="" class="card-body d-flex flex-column" method="post">
                                <h2 class="h5 mb-3">System</h2>
                                <div class="mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language">
                                        <option value="en">English</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="theme" class="form-label">Theme</label>
                                    <select class="form-select" id="theme">
                                        <option value="light">Light</option>
                                        <option value="dark" disabled>Dark (Soon)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="emailNotifications" class="form-label">Email Notifications</label>
                                    <select class="form-select" id="emailNotifications">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="smsNotifications" class="form-label">SMS Notifications</label>
                                    <select class="form-select" id="smsNotifications">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pushNotifications" class="form-label">
                                        Push Notifications
                                        <span class="badge bg-secondary">Coming Soon</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-sh w-100 py-2 mt-md-5">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>