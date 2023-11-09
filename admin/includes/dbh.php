<?php
// diffine the connection of database blogs
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "blogs";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// echo "sucsess connection to database";
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
