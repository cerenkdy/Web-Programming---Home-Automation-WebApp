<?php
// database connection and session 
require_once 'dbconfig.php';

// if user is logged in, redirect to myhome.php

if (isset($_SESSION['user'])) {
    hearder("Location: myhome.php");
    exit;
}

// if login form has been submitted, process it
if (isset($_POST['username']) && isset($_POST['password'])) {

    // getting username and password
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // database select statement
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute([$username, $password]);

    // if username and password are correct, set session variables
    
    if ($stmt->rowCount() >0) {
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = $row['id'];
        $_SESSION['type'] = $row['type'];

        // redirect to myhome.php
        header("Location: myhome.php");
        exit;
    } else (
        // if username or password are not correct, show error message
        $error = 'Username or password is incorrect' ;
    )
}   

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Smart Home</title>
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
            <h1 class="text-center mb-4">Sign In</h1>
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
            <p class="text-center">Don't have an account? <a href="register.html">Sign Up</a></p>
        </form>
    </main>
</body>

</html>