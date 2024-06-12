<?php
session_start();
include('DBConn.php');

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Get the search query from the URL parameter
$searchQuery = $_GET['search'];

// Prepare the SQL query to search for books
$query = "SELECT * FROM tblBooks WHERE title LIKE '%$searchQuery%' OR author LIKE '%$searchQuery%' OR isbn LIKE '%$searchQuery%'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>

<head>
<link href="styles/background.css" rel="stylesheet">
    <link href="styles/homestyle.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Reuse Books - Search Results</title>
    <style>
        /* Additional CSS styles for search results */
        .search-results {
            margin-top: 20px;
        }
    </style>
</head>

<body>
 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-success"> <a class="navbar-brand" href="#" data-abc="true">ReuseBooks</a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"> <a class="nav-link" href="home.php" data-abc="true">Home <span class="sr-only">(current)</span></a> </li>
                <li class="nav-item"><a class="nav-link active" href="sellbook.php">Sell Books</a> </li>
                <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>

            </ul>
            <!-- Search form -->
            <form action="search.php" method="GET" class="form-inline my-2 my-lg-0">
                <input class="form-control me-2" type="text" name="search" placeholder="Search for books">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <!-- Container -->
    <div class="container">
        <div style="text-align: center; color:black; font-weight: bold;">
            <!-- Copy the same user session code from home.php -->

            <!-- Search Results -->
            <div class="search-results">
                <h2>Search Results:</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Add to Cart</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr onclick=\"window.location='details.php?book_id={$row['book_id']}'\">";
                            echo "<td><img src='images/" . $row['cover'] . "' class='book-cover' alt='{$row['title']}'></td>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['author']}</td>";
                            echo "<td><a class='btn btn-success' href='cart.php?book_id={$row['book_id']}'>Add to Cart</a></td>";
                            echo "<td>{$row['price']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>