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
$user = intval($_SESSION['user']);
$settings = $_SESSION['settings'];
$type = ($_SESSION['type'] == 'producers') ? 'producers' : 'consumers';


// delete and disable account
if (isset($_POST['account_action']) && ($_POST['account_action'] == 'disable' || $_POST['account_action'] == 'delete')) {
    // check password
    $stmt = $db->prepare("SELECT * FROM " . $type . " WHERE id = ? AND password = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$user, $_POST['password']]);
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_POST['account_action'] == 'disable') {
            $stmt = $db->prepare("UPDATE " . $type . " SET disabled_at = NOW(), reason = ? WHERE id = ?");
            $stmt->execute([$_POST['reason'], $user]);
        } else {
            $stmt = $db->prepare("UPDATE " . $type . " SET deleted_at = NOW() WHERE id = ?");
            $stmt->execute([$user]);
        }
        // logout
        unset($_SESSION['user']);
        unset($_SESSION['type']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['username']);
        unset($_SESSION['settings']);
        unset($_SESSION['consumer_login']);
        unset($_SESSION['producer_login']);
        header("Location: login.php");
        exit;
    } else {
        $error = 'Incorrect password!';
    }
}

// save password
if (isset($_POST['password']) && isset($_POST['password2']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
    $type = ($_SESSION['type'] == 'producers') ? 'producers' : 'consumers';
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    if ($password == $password2) {
        $stmt = $db->prepare("UPDATE " . $type . " SET password = ? WHERE id = ?");
        $stmt->execute([$password, $user]);
    } else {
        $error = 'Passwords do not match!';
    }
}

// save birth_date
if (isset($_POST['birth_date']) && !empty($_POST['birth_date'])) {
    $birth_date = $_POST['birth_date'];
    $stmt = $db->prepare("UPDATE " . $type . " SET birth_date = ? WHERE id = ?");
    $stmt->execute([$birth_date, $user]);
    $_SESSION['birth_date'] = $birth_date;
}


// save user data
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['name']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $type = ($_SESSION['type'] == 'producers') ? 'producers' : 'consumers';
    $stmt = $db->prepare("UPDATE " . $type . " SET name = ?, email = ?, username = ? WHERE id = ?");
    $stmt->execute([$name, $email, $username, $user]);
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    header("Location: settings.php");
    exit;
}

// fetch updated data
$stmt = $db->prepare("SELECT * FROM " . $type . " WHERE id = ? LIMIT 1");
$stmt->execute([$user]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['name'] = $row['name'];
$_SESSION['email'] = $row['email'];
$_SESSION['username'] = $row['username'];
$_SESSION['birth_date'] = $row['birth_date'];
$_SESSION['settings'] = json_decode($row['settings'], true);
$settings = $_SESSION['settings'];
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
                    <div class="col-md-6 d-flex">
                        <div class="card rounded-4 shadow-sm mb-3 bg-white-50 flex-fill">
                            <form action="settings.php" class="card-body d-flex flex-column" method="post">
                                <h2 class="h5 mb-3">Account</h2>
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" placeholder="Full Name" name="name" value="<?php echo htmlentities($_SESSION['name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo htmlentities($_SESSION['email']); ?>" required>
                                </div>
                                <?php if (isset($_SESSION['birth_date'])) { ?>
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Birth Date</label>
                                    <input type="date" class="form-control" id="birth_date" placeholder="Birth Date" name="birth_date" value="<?php echo htmlentities($_SESSION['birth_date']); ?>">
                                </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo htmlentities($_SESSION['username']); ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="password2">
                                        </div>
                                    </div>
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
                                    <select class="form-select" id="language" name="language">
                                        <option value="en"<?php if($settings['language'] == 'en') { echo ' selected'; } ?>>English</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="theme" class="form-label">Theme</label>
                                    <select class="form-select" id="theme">
                                        <option value="light"<?php if($settings['theme'] == 'light') { echo ' selected'; } ?>>Light</option>
                                        <option value="dark"<?php if($settings['theme'] == 'dark') { echo ' selected'; } ?> disabled>Dark (Soon)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="emailNotifications" class="form-label">Email Notifications</label>
                                    <select class="form-select" id="emailNotifications" name="notifications">
                                        <option value="1"<?php if($settings['notifications'] == 1) { echo ' selected'; } ?>>Enabled</option>
                                        <option value="0"<?php if($settings['notifications'] == 0) { echo ' selected'; } ?> disabled>Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="smsNotifications" class="form-label">SMS Notifications</label>
                                    <select class="form-select" id="smsNotifications">
                                        <option value="1" disabled>Enabled (Soon)</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pushNotifications" class="form-label">
                                        <div>Push Notifications</div>
                                        <span class="badge bg-secondary">Coming Soon</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-sh w-100 py-2 mt-md-4">Save</button>
                            </form>
                        </div>
                    </div>
                    <!-- Disable Account Button for modal -->
                    <div class="col-md-6">
                        <div class="card rounded-4 shadow-sm mb-3 bg-white-50">
                            <div class="card-body d-flex flex-column">
                                <button type="button" class="btn btn-outline-danger w-100 py-2 mt-auto" data-bs-toggle="modal" data-bs-target="#disableAccountModal">Disable Account</button>
                            </div>
                        </div>
                    </div>
                    <!-- Delete Account Button for modal -->
                    <div class="col-md-6">
                        <div class="card rounded-4 shadow-sm mb-3 bg-white-50">
                            <div class="card-body d-flex flex-column">
                                <button type="button" class="btn btn-danger w-100 py-2 mt-auto" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/disableAccountModal.php';
    include 'components/deleteAccountModal.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <script src="js/app.js"></script>
</body>

</html>