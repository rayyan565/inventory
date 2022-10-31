<?php

$host = "alecsiteserver.mysql.database.azure.com";
$dbname = "bubble_viz";
$username = "alecadmin";
$password = "RFIDlab123!";

$mysqli = new mysqli(hostname: $host,
    username: $username,
    password: $password,
    database: $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli; //hello