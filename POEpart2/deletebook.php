<?php
session_start();
include('DBConn.php');

if (!isset($_SESSION["admin_id"])) {
    // If an admin is not logged in, redirect to the admin login page
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["book_id"])) {
    $bookID = $_GET["book_id"];

    // Check if the book exists in the tblBooks table
    $tblBooks_query = "SELECT * FROM tblBooks WHERE book_id = ?";
    $tblBooks_statement = $conn->prepare($tblBooks_query);
    $tblBooks_statement->bind_param("i", $bookID);
    $tblBooks_statement->execute();
    $tblBooks_result = $tblBooks_statement->get_result();

    if ($tblBooks_result->num_rows > 0) {
        // Delete the book from the tblBooks table
        $tblBooks_delete_query = "DELETE FROM tblBooks WHERE book_id = ?";
        $tblBooks_delete_statement = $conn->prepare($tblBooks_delete_query);
        $tblBooks_delete_statement->bind_param("i", $bookID);
        $tblBooks_delete_statement->execute();
    }

    // Check if the book exists in the verifyBook table
    $verifyBook_query = "SELECT * FROM verifyBook WHERE book_id = ?";
    $verifyBook_statement = $conn->prepare($verifyBook_query);
    $verifyBook_statement->bind_param("i", $bookID);
    $verifyBook_statement->execute();
    $verifyBook_result = $verifyBook_statement->get_result();

    if ($verifyBook_result->num_rows > 0) {
        // Delete the book from the verifyBook table
        $verifyBook_delete_query = "DELETE FROM verifyBook WHERE book_id = ?";
        $verifyBook_delete_statement = $conn->prepare($verifyBook_delete_query);
        $verifyBook_delete_statement->bind_param("i", $bookID);
        $verifyBook_delete_statement->execute();
    }
}

// Redirect back to the verifybook.php page to see the updated table
header("Location: verifybook.php");
exit;
?>
