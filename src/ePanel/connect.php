<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "allah";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("fatal error" . $conn->connect_error);
}

?>