<?php
session_start();
include('DBConn.php');

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Check if the book_id parameter is present in the URL
if (!isset($_GET['book_id'])) {
    header("Location: home.php");
    exit;
}

// Retrieve the book ID and user ID
$bookId = $_GET['book_id'];
$userId = $_SESSION["user_id"];

// Insert the book into tblWishlist
$insertQuery = "INSERT INTO tblWishlist (ID, book_id) VALUES ('$userId', '$bookId')";
mysqli_query($conn, $insertQuery);

// Redirect back to the wishlist page
header("Location: wishlist.php");
exit;
?>
