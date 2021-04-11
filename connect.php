<?php


$user = 'root';
$password = 'root';
$db = 'test_films';
$host = 'localhost';
$port = 3306;
$link = mysqli_init();
$connected_base = mysqli_real_connect(
    $link, 
    $host, 
    $user, 
    $password, 
    $db,
    $port);
?>