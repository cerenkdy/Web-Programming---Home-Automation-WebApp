<?php

$user="admin";
$password="1234";

if(isset($_POST["login"])) 
{

// Taking username and password from HTML form    
    $username = $_POST["username"];  
    $pwd=$_POST["password"];  

    if(isset($username) && isset($pwd)) 
    {
        
            //Password check
            if($pwd == $password && $username == $user)
            {
                
                echo '<div class="alert alert-success" role="alert">
            giriş yapıldı!
            </div>';

            //When producer page been made that needs to be updated
            header('Location: https://www.youtube.com/watch?v=Tm6BlRMEny0');
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