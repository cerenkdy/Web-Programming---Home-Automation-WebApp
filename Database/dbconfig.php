<?php
// application config
$name = 'Smart Home';


// database connection config
$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = 'password';
$dbname = 'smarthome';

// database connection
try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass, $dbname);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed? " . $e->getMessage();
}

// session starting
session_start();
