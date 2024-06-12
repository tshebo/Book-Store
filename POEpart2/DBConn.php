<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$servername = "localhost";
$user = "root";
$password = "";
$database = "bookstore";

// Create a connection
$conn = new mysqli($servername, $user, $password, $database);

// Check connection
if ($conn->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

 return $conn;