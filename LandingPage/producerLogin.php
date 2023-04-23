<?php



//We will not considering security at the moment

if(isset($_POST["login"])) 
{

    header('Location: ../Consumer/livingroom.html ');
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
                 <?php if (isset($error_msg)): ?> 
                    <p class="error"   color="red"><?php echo $error_msg; ?></p>
                <?php endif ?>
                <input type="text" name="username" class="input-field" placeholder="Enter Username">
                <input type="password" name="password" class="input-field" placeholder="Enter Password">
                <button type="submit" name="login" class="submit-btn" href='producerLogin.php'> Login </button>
            </form>
            <div class="buttons">
                <button class="button-link" onclick="window.location.href='landingPage.html';"> Back to Landing Page</button>
            </div>
        </div>
    </div>
</body>
</html>