<?php
session_start();
include('DBConn.php');


// Check if the tblUser table exists
$tableExistsQuery = "SHOW TABLES LIKE 'tblUser'";
$tableExistsResult = $conn->query($tableExistsQuery);

if ($tableExistsResult->num_rows > 0) {
    // If the tblUser table exists, delete it
    $deleteTableQuery = "DROP TABLE tblUser";
    $conn->query($deleteTableQuery);
}

// Create the tblUser table
$createTableQuery = "
    CREATE TABLE tblUser (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(20) NOT NULL,
        userlastname VARCHAR(20) NOT NULL,
        student_number VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL
        IsVerified TINYINT(1) DEFAULT 0

    )
";
$conn->query($createTableQuery);

// Load data from userData.txt into tblUser table
$dataFile = 'userData.txt';
if (file_exists($dataFile)) {
    $userData = file($dataFile);

    foreach ($userData as $line) {
        $line = trim($line);
        $data = explode(' ', $line);

        $firstName = $data[0];
        $lastName = $data[1];
        $studentNumber = $data[2];
        $password = $data[3];

        $insertQuery = "INSERT INTO tblUser (username, userlastname, student_number, password) VALUES ('$firstName', '$lastName', '$studentNumber', '$password')";
        $conn->query($insertQuery);
    }
}

// Close the database connection
$conn->close();
