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

// Retrieve the book details from tblBooks
$bookId = $_GET['book_id'];
$query = "SELECT * FROM tblBooks WHERE book_id = $bookId";
$result = mysqli_query($conn, $query);

// Check if the book exists
if (mysqli_num_rows($result) == 0) {
    header("Location: home.php");
    exit;
}

// Fetch the book details
$book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <link href="styles/homestyle.css" rel="stylesheet">
    <link href="styles/background.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Reuse Books - Book Details</title>
    <style>
        /* Additional CSS styles for book details */
        .book-details {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <a class="navbar-brand" href="#" data-abc="true">ReuseBooks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php" data-abc="true">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="sellbook.php">Sell Books</a>
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
                <input class="form-control me-2" type="text" name="search" placeholder="Search for books">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <!-- Profile link -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Container -->
    <div class="container">
        <div style="text-align: center; color:black; font-weight: bold;">
            <!-- Copy the same user session code from home.php -->

            <!-- Book Details -->
            <div class="book-details">
                <h2><?php echo $book['title']; ?></h2>
                <img src="images/<?php echo $book['cover']; ?>" class="book-cover" alt="<?php echo $book['title']; ?>">
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <?php if (isset($book['isbn'])) : ?>
                    <p><strong>ISBN:</strong> <?php echo $book['isbn']; ?></p>
                <?php endif; ?>
                <p><strong>Price:</strong> <?php echo $book['price']; ?></p>
                <p><strong>Description:</strong></p>
                <p><?php echo $book['description']; ?></p>
                <!-- Add to Cart and Wishlist buttons -->
                <br>
                <a class="btn btn-success" href="home.php?book_id=<?php echo $book['book_id']; ?>">Add to Cart</a>
                <a class="btn btn-primary" href="add_to_wishlist.php?book_id=<?php echo $book['book_id']; ?>">Add to Wishlist</a>
            </div>
            <br><br>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>

</body>

</html>