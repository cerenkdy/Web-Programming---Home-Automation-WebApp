<?php
// initialize db connection and session
require_once 'dbconfig.php';

// if user is not logged in, redirect to login.php?type=producers
if (!isset($_SESSION['producer_login'])) {
    header("Location: login.php?type=producers");
    exit;
}

// add consumer
if(isset($_POST['add_consumer']) && isset($_POST['username']) && isset($_POST['email']) &&  isset($_POST['password'])) {

    // get username, email and password
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // check if username or email already exists
    $stmt = $db->prepare("SELECT * FROM consumers WHERE username = ? OR email = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$username, $email]);

    if (empty($username) || empty($email) || empty($password) || empty($name)) {
        $error = 'Please fill in all fields.';
    }

    // if username or email already exists, show error message
    if ($stmt->rowCount() > 0) {
        $error = 'Username or email already exists.';
    } else if (!isset($error)) {
        // prepare, bind and execute INSERT statement
        $stmt = $db->prepare("INSERT INTO consumers (username, password, email, name, settings) VALUES (?, ?, ?, ?, '{\"theme\": \"light\", \"language\": \"en\", \"notifications\": true}')");
        if ($stmt->execute([
            $username,
            $password,
            $email,
            $name,
        ])) {
            // if register is successful, auth and redirect to myhome.php
            $user_id = $db->lastInsertId();
            // insert doors for user
            $stmt = $db->prepare("INSERT INTO home_configs (user_id, type, data) VALUES (?, ?, ?)");
            $stmt->execute([
                $user_id,
                'outdoor_lock',
                json_encode([
                    'status' => '1',
                ])
            ]);
            $stmt->execute([
                $user_id,
                'door',
                json_encode([
                    'name' => 'Main Door',
                    'status' => '1',
                ])
            ]);
            $stmt->execute([
                $user_id,
                'door',
                json_encode([
                    'name' => 'Exit Door',
                    'status' => '1',
                ])
            ]);
            $stmt->execute([
                $user_id,
                'door',
                json_encode([
                    'name' => 'Balcony Door',
                    'status' => '1',
                ])
            ]);
            $stmt->execute([
                $user_id,
                'door',
                json_encode([
                    'name' => 'Garage Door',
                    'status' => '1',
                ])
            ]);
            $success = 'Consumer added successfully.';
        } else {
            // if register is not successful, show error message
            $error = 'Something went wrong. Please try again later.';
        }
    }
}

// edit consumer
if(isset($_POST['edit_consumer']) && isset($_POST['username']) && isset($_POST['email']) &&  isset($_POST['password'])) {

    // get username, email and password
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // check if username or email already exists
    $stmt = $db->prepare("SELECT * FROM consumers WHERE (username = ? OR email = ?) AND id != ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$username, $email, $_POST['edit_consumer']]);

    if (empty($username) || empty($email) || empty($name)) {
        $error = 'Please fill in all fields.';
    }

    // if username or email already exists, show error message
    if ($stmt->rowCount() > 0) {
        $error = 'Username or email already exists.';
    } else if (!isset($error)) {
        // prepare, bind and execute INSERT statement
        $stmt = $db->prepare("UPDATE consumers SET username = ?, email = ?, name = ? WHERE id = ? LIMIT 1");
        if ($stmt->execute([
            $username,
            $email,
            $name,
            $_POST['edit_consumer'],
        ])) {
            if (!empty($password)) {
                $stmt = $db->prepare("UPDATE consumers SET password = ? WHERE id = ? LIMIT 1");
                $stmt->execute([
                    $password,
                    $_POST['edit_consumer'],
                ]);
            }
            $success = 'Consumer edited successfully.';
        } else {
            $error = 'Something went wrong. Please try again later.';
        }
    }
}

// delete consumer
if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $_GET['delete'] > 0) {
    // check consumer
    $stmt = $db->prepare("SELECT * FROM consumers WHERE id = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$_GET['delete']]);
    $getConsumer = $stmt->fetch();
    if($stmt->rowCount() > 0 && $getConsumer['deleted_at'] == NULL) {
        // delete consumer
        $stmt = $db->prepare("UPDATE consumers SET deleted_at = NOW() WHERE id = ? LIMIT 1");
        $stmt->execute([$_GET['delete']]);
        $success = 'Consumer deleted successfully.';
        $success .= '<script>window.history.replaceState({}, document.title,  "' . $_SERVER['PHP_SELF'] . '");</script>';
    } else if ($stmt->rowCount() > 0 && $getConsumer['deleted_at'] != NULL) {
        // delete consumer permanently
        $stmt = $db->prepare("DELETE FROM sensor_data WHERE user_id = ?");
        $stmt->execute([$_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM logs WHERE user_id = ?");
        $stmt->execute([$_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM devices WHERE user_id = ?");
        $stmt->execute([$_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM rooms WHERE user_id = ?");
        $stmt->execute([$_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM home_configs WHERE user_id = ?");
        $stmt->execute([$_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM consumers WHERE id = ? LIMIT 1");
        $stmt->execute([$_GET['delete']]);
        $success = 'Consumer deleted successfully.';
        $success .= '<script>window.history.replaceState({}, document.title,  "' . $_SERVER['PHP_SELF'] . '");</script>';
    }
}

// restore consumer
if(isset($_GET['restore']) && is_numeric($_GET['restore']) && $_GET['restore'] > 0) {
    // check consumer
    $stmt = $db->prepare("SELECT * FROM consumers WHERE id = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$_GET['restore']]);
    if($stmt->rowCount() > 0) {
        // restore consumer
        $stmt = $db->prepare("UPDATE consumers SET deleted_at = NULL WHERE id = ? LIMIT 1");
        $stmt->execute([$_GET['restore']]);
        $success = 'Consumer restored successfully.';
        $success .= '<script>window.history.replaceState({}, document.title,  "' . $_SERVER['PHP_SELF'] . '");</script>';
    }
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

// page
$page = 'consumers';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consumers | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
<body <?php if(isset($_COOKIE['sidebar-toggle']) && $_COOKIE['sidebar-toggle'] == '1') { echo ' class="sidebar-toggle"'; } ?>>
    <main class="d-flex flex-nowrap">
        <?php include 'components/producerSidebar.php';?>
        <!-- Consumers -->
        <div class="d-flex flex-fill p-3 px-md-4 px-sm-2 w-100">
            <div class="dashboard w-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-white">Consumers</h4>
                    <button type="button" class="text-white btn" data-bs-toggle="modal" data-bs-target="#addConsumerModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <?php if(isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php } else if(isset($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Username</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Age</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="d-none d-sm-table-cell">Rooms</th>
                                <th scope="col">Last Update</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // get consumers
                            $stmt = $db->prepare("SELECT * FROM consumers");
                            $stmt->execute();
                            $consumers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach($consumers as $consumer) {
                                // get last update
                                $stmt = $db->prepare("SELECT * FROM logs WHERE user_id = ? ORDER BY id DESC LIMIT 1");
                                $stmt->execute([$consumer['id']]);
                                $update = $stmt->fetch(PDO::FETCH_ASSOC);

                                // get rooms
                                $stmt = $db->prepare("SELECT * FROM rooms WHERE user_id = ?");
                                $stmt->execute([$consumer['id']]);
                                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <tr>
                                    <td><?php echo $consumer['id']; ?></td>
                                    <td><?php echo $consumer['username']; ?></td>
                                    <td><?php echo $consumer['name']; ?></td>
                                    <td><?php echo $consumer['email']; ?></td>
                                    <td>
                                        <?php
                                        if ($consumer['birth_date'] != null) {
                                            try {
                                                $birth_date = new DateTime($consumer['birth_date']);
                                            } catch (Exception $e) {
                                                $birth_date = null;
                                            }
                                            $now = new DateTime();
                                            if ($birth_date != null && $now > $birth_date) {
                                                $interval = $now->diff($birth_date);
                                                echo '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' . date('d/m/Y', strtotime($consumer['birth_date'])) . '">' . $interval->format('%y') . '</span>';
                                            } else {
                                                echo '-';
                                            }
                                        } else {
                                            echo '-';
                                        }

                                        ?>
                                    </td>
                                    <td>
                                        <?php if($consumer['deleted_at'] != null) { ?>
                                        <span class="badge bg-danger">Deleted</span>
                                        <?php } else if($consumer['disabled_at'] != null) { ?>
                                        <span class="badge bg-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo htmlentities(strip_tags($consumer['reason'])); ?>">Disabled</span>
                                        <?php } else { ?>
                                        <span class="badge bg-success">Active</span>
                                        <?php } ?>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <?php foreach($rooms as $room) { ?>
                                        <a href="producer_devices.php?user=<?php echo $consumer['id']; ?>&room=<?php echo $room['id']; ?>" class="badge bg-secondary me-1 text-decoration-none"><?php echo $room['name']; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo ($update) ? diffForHumans($update['created_at']) : '-'; ?></td>
                                    <td style="min-width: 160px;">
                                        <?php if($consumer['deleted_at'] != null) { ?>
                                            <a href="?restore=<?php echo $consumer['id']; ?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore Consumer">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                        <?php } ?>
                                        <a href="producer_devices.php?user=<?php echo $consumer['id']; ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Devices">
                                            <i class="fas fa-microchip"></i>
                                        </a>
                                        <a href="producer_rooms.php?user=<?php echo $consumer['id']; ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Rooms">
                                            <i class="fas fa-door-open"></i>
                                        </a>
                                        <a href="#editConsumerModal" class="btn btn-sm btn-outline-secondary edit-consumer" data-id="<?php echo $consumer['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $consumer['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete<?php if($consumer['deleted_at'] != null) { ?> Permanently<?php } ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'components/sendDataModal.php';
    include 'components/addConsumerModal.php';
    include 'components/editConsumerModal.php';
    ?>
    
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
    <script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    </script>
</body>

</html>