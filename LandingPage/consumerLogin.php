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
                
            header('Location: ../Consumer/livingroom.html ');
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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main">
        <div class="form-box">
            <h2>Consumer Log in </h2>
            <form class="input-group" action="consumerLogin.php" method="POST">
                <?php if (isset($error_msg)): ?> 
                    <p class="error"   color="red"><?php echo $error_msg; ?></p>
                <?php endif ?>
                <input type="text" name="username" class="input-field" placeholder="Enter Username" required>
                <input type="password" name="password" class="input-field" placeholder="Enter Password" required>
                <button type="submit" name="login" class="submit-btn" href='producerLogin.php'> Login </button>
            </form>
            <div class="buttons">
                <button class="button-link" onclick="window.location.href='landingPage.html';"> Back to Landing Page</button>
            </div>
        </div>
    </div>
</body>
</html>