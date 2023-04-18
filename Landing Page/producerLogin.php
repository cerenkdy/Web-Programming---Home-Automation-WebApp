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
                
                header('Location: ../Producer/livingroom-p.html ');
            }
            else
            {
            $error_msg="Invalid username or password";
            }
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
                <?php if (isset($error_msg)): ?> 
                    <p class="error"   color="red"><?php echo $error_msg; ?></p>
                <?php endif ?>
            </div>
            
        </div>
    </div>
</body>
</html>