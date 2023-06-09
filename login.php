<?php
// database connection and session 
require_once 'dbconfig.php';

// if user is logged in, redirect to myhome.php

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['type']) && $_SESSION['type'] == 'producers') {
        header("Location: producer.php");
    } else {
        header("Location: myhome.php");
    }
}

// if login form has been submitted, process it
if (isset($_POST['username']) && isset($_POST['password'])) {

    // login type
    $type = (isset($_GET['type']) && $_GET['type'] == 'producers') ? 'producers' : 'consumers';

    // getting username and password
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // database select statement
    $stmt = $db->prepare("SELECT * FROM " . $type . " WHERE username = ? AND password = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$username, $password]);

    // if username and password are correct, set session variables
    
    if ($stmt->rowCount() >0) {
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = $row['id'];
        $_SESSION['type'] = $type;
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['settings'] = json_decode($row['settings'], true);

        if ($_SESSION['type'] == 'producers') {
            // if user is producer, redirect to producer.php
            header("Location: producer.php");
        } else {
            // if user is consumer, redirect to myhome.php
            header("Location: myhome.php");
        }
        exit;
    } else {
        // if username and password are not correct, show error message
        $error = 'Username or password are incorrect.';
    }
}   

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | <?php echo $name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
    <body>
    <main class="d-flex justify-content-center align-items-center lrform">
        <?php
        if (!isset($_GET['type']) || ($_GET['type'] != 'consumers' && $_GET['type'] != 'producers')) {
        ?>
        <main class="d-flex justify-content-center align-items-center ps-0 mw-400">
            <div class="p-5 rounded shadow bg-white mw-400">
                <div class="d-flex justify-content-center align-items-center">
                    <h4>Sign in as</h4>
                </div>  
                <div class="mb-4 d-grid mt-5">
                    <a href="login.php?type=producers" class="btn p-2 btn-sh btn-lg">Producer</a>
                </div>
                <div class="d-grid mb-5">
                    <a href="login.php?type=consumers" class="btn p-2 btn-sh btn-lg">Consumer</a>
                </div>
            </div>
        </main>
        <?php
        } else {
        ?>
        <form class="p-5 rounded shadow bg-white mw-400" action="" method="post">
            <?php
            if (isset($_GET['type']) && $_GET['type'] == 'producers') {
                echo '<h1 class="text-center mb-4 h3">Producer Sign In</h1>';
            } else {
                echo '<h1 class="text-center mb-4 h3">Consumer Sign In</h1>';
            }
            ?>
            <!-- ERROR MESSAGE -->
            <?php if (isset($error)) {?>
                <div class="alert alert-danger mb-3" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php }?>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" type="text" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" name="password" id="password" required>
            </div>
            <div class="mb-4 d-grid">
                <button class="btn p-2 btn-sh" type="submit">Sign In</button>
            </div>
            <!-- back to sign in type -->
            <p class="text-center mb-0">
                <?php
                if (isset($_GET['type']) && $_GET['type'] == 'producers') {
                    echo 'Not a producer? <a href="login.php?type=consumers">Sign in as consumer</a>';
                } else {
                    echo 'Not a consumer? <a href="login.php?type=producers">Sign in as producer</a>';
                }
                ?>
            </p>
            <!-- back to home -->
            <p class="text-center mt-3">Back to <a href="./">Home</a></p>
        </form>
        <?php
        }
        ?>
    </main>
</body>
</html>