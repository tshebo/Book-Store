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

// Check if the book already exists in the cart for the user
$checkQuery = "SELECT * FROM tblCart WHERE ID = '$userId' AND book_id = '$bookId'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Book already exists in the cart, update the quantity
    $row = mysqli_fetch_assoc($checkResult);
    $quantity = $row['quantity'] + 1;
    $updateQuery = "UPDATE tblCart SET quantity = '$quantity' WHERE ID = '$userId' AND book_id = '$bookId'";
    mysqli_query($conn, $updateQuery);
} else {
    // Book doesn't exist in the cart, insert a new record with quantity 1
    $insertQuery = "INSERT INTO tblCart (ID, book_id, quantity) VALUES ('$userId', '$bookId', 1)";
    if (mysqli_query($conn, $insertQuery)) {
        echo "Book added to cart successfully!";
    } else {
        echo "Error inserting book into cart: " . mysqli_error($conn);
    }}

// Redirect back to the cart page
header("Location: cart.php");
exit;
?>
