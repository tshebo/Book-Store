<?php
session_start();
include('DBConn.php');

if (!isset($_SESSION["admin_id"])) {
    // If an admin is not logged in, redirect to the admin login page
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Get the user ID from the query string
    $userID = $_GET["id"];

    // Delete the user from the database
    $sql = "DELETE FROM tbluser WHERE ID = ?";
    $statement = $conn->prepare($sql);
    $statement->bind_param("i", $userID);

    if ($statement->execute()) {
        // Redirect back to the admin page if deletion is successful
        header("Location: admin.php");
        exit;
    } else {
        // Handle the error if deletion fails
        echo "Error deleting user.";
    }
} else {
    // If the user ID is not provided, redirect back to the admin page
    header("Location: admin.php");
    exit;
}
?>
