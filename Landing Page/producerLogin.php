<?php

include("connection.php");



if(isset($_POST["login"])) 
{

// Taking username and password from HTML form    
    $username = $_POST["username"];  
    $pwd=$_POST["password"];  

    if(isset($username) && isset($pwd)) 
    {
        //Searching for username
        $selection = "SELECT * FROM  producer WHERE Username ='$username'";
        $start=mysqli_query($connection, $selection);

        //Usernames are unique so this works as found or not found
        $is_user_found = mysqli_num_rows($start); // 0 or 1

        if($is_user_found>0)
        {
            //All data related to that user
            $user_record = mysqli_fetch_assoc($start);
            
            $password = $user_record["Password"];
            //Password check
            if($pwd == $password)
            {
                session_start();
                $_SESSION["username"]=$user_record["username"];
                echo '<div class="alert alert-success" role="alert">
            giriş yapıldı!
            </div>';
            header('Location: ../Consumer/livingroom.html');
            }
            else
            {
                echo '<div class="alert alert-failed" role="alert">
            The password is incorrect!
            </div>';
            }
        }
        else 
        {
            echo '<div class="alert alert-failed" role="alert">
            The username is incorrect!
            </div>';
            
        }

        mysqli_close($connection);

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producer Log in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main">
        <div class="form-box">
            <h2>Producer Log in </h2>
            <form class="input-group" action="producerLogin.php" method="POST">
                <input type="text" name="username" class="input-field" placeholder="Enter Username" required>
                <input type="password" name="password" class="input-field" placeholder="Enter Password" required>
                <button type="submit" name="login" class="submit-btn"> Login </button>
            </form>
            <div style="position: absolute; bottom: 23px; right: 35% ;">
                <button class="button-link" onclick="window.location.href='landingPage.html';"> Back to Landing Page</button>

            </div>
            
        </div>
    </div>
</body>
</html>