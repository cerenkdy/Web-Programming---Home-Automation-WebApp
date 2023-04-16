<?php 

    $host="localhost";
    $username="root";
    $password="";
    $database="Users";
    
    $connection = mysqli_connect($host, $username , $password, $database);
    mysqli_set_charset($connection, "UTF8");

    

?>