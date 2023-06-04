<?php 
    // database connection and session
    require_once 'dbconfig.php';

    // if user is logged in, redirect to myhome.php
    if (isset($_SESSION['user'])) {
        header("Location: myhome.php");
        exit;
    }

    // if register form has been submitted
    if (isset($_POST['username']) && isset($_POST['email']) &&  isset($_POST['password'])) {

        // get username, email and password
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';


        // check if username or email already exists
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$username, $email]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | Smart Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <main class="d-flex justify-content-center align-items-center lrform">
        <form class="p-5 rounded shadow bg-white mw-400">
            <h1 class="text-center mb-4">Sign Up</h1>
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
            <p class="text-center">Already have an account? <a href="login.html">Sign In</a></p>
        </form>
    </main>
</body>
</html>