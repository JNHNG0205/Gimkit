<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gimkit";

// Create connection
$con = new mysqli("localhost","root","","gimkit");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
