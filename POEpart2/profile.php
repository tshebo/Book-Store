<?php
session_start();
include('DBConn.php'); // Connection

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Fetch user details
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM tblUser WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if user exists
if (mysqli_num_rows($result) === 0) {
    echo "User not found.";
    exit;
}

// Fetch user data
$user_data = mysqli_fetch_assoc($result);

// Fetch books sold by the user from the verifybook table
$query_sold = "SELECT * FROM verifybook WHERE book_id = '$user_id'";
$result_sold = mysqli_query($conn, $query_sold);

// Check if the query executed successfully
if (!$result_sold) {
    echo "Error retrieving sold books: " . mysqli_error($conn);
    exit;
}

// Fetch books bought by the user
$query_bought = "SELECT b.* FROM tblBooks AS b INNER JOIN tblCart AS c ON b.book_id = c.book_id WHERE c.id = '$user_id'";
$result_bought = mysqli_query($conn, $query_bought);

// Check if the query executed successfully
if (!$result_bought) {
    echo "Error retrieving bought books: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        
    </style>
    <link href="styles/profilestyle.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Reuse Books - Profile</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <a class="navbar-brand" href="#">ReuseBooks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sellbook.php">Sell Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="wishlist.php">Wishlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
            <!-- Search form -->
            <form action="search.php" method="GET" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search for books">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <!-- Profile link -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="profile">
            <h1>User Profile</h1>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Welcome, <?= $user_data['username'] ?>!</h3>
                    <p class="card-text"><strong>First Name:</strong> <?= $user_data['username'] ?></p>
                    <p class="card-text"><strong>Last Name:</strong> <?= $user_data['userlastname'] ?></p>
                    <p class="card-text"><strong>Student Number:</strong> <?= $user_data['student_number'] ?></p>
                </div>
            </div>

            <div class="books-sold">
                <h3>Books Sold</h3>
                <?php
                if (mysqli_num_rows($result_sold) > 0) {
                    echo "<ul>";
                    while ($row_sold = mysqli_fetch_assoc($result_sold)) {
                        echo "<li>{$row_sold['title']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No books sold yet.</p>";
                }
                ?>
            </div>

            <div class="books-bought">
                <h3>Books Bought</h3>
                <?php
                if (mysqli_num_rows($result_bought) > 0) {
                    echo "<ul>";
                    while ($row_bought = mysqli_fetch_assoc($result_bought)) {
                        echo "<li>{$row_bought['title']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No books bought yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
