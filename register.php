<?php 
// database connection and session
require_once 'dbconfig.php';

// if user is logged in, redirect to myhome.php
if (isset($_SESSION['consumer_login']) || isset($_SESSION['producer_login'])) {
    if (isset($_SESSION['type']) && $_SESSION['type'] == 'producers') {
        header("Location: producer.php");
    } else {
        header("Location: myhome.php");
    }
}

// if register form has been submitted
if (isset($_POST['username']) && isset($_POST['email']) &&  isset($_POST['password'])) {

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
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
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

            $_SESSION['user'] = $user_id;
            $_SESSION['type'] = 'consumer';
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['settings'] = json_decode('{\"theme\": \"light\", \"language\": \"en\", \"notifications\": true}', true);
            header("Location: myhome.php");
            exit;
        } else {
            // if register is not successful, show error message
            $error = 'Something went wrong. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <main class="d-flex justify-content-center align-items-center lrform">
        <form class="p-5 rounded shadow bg-white mw-400" method="post" action="register.php">
            <h1 class="text-center mb-4">Sign Up</h1>
            <!-- error message -->
            <?php if (isset($error)) {?>
                <div class="alert alert-danger mb-3" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php }?>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input class="form-control" type="text" name="name" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input class="form-control" type="email" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" type="text" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" name="password" id="password" required>
            </div>
            <div class="mb-4 d-grid">
                <button class="btn p-2 btn-sh" type="submit">Sign Up</button>
            </div>
            <div class="d-flex">
                <hr class="flex-grow-1">
                <span class="mx-2">OR</span>
                <hr class="flex-grow-1">
            </div>
            <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-dark">Sign In</a></p>
        </form>
    </main>
</body>
</html>