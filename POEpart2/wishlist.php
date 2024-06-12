<?php
session_start();
include('DBConn.php');

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["book_id"])) {
    // Get the book_id from the parameter
    $book_id = $_GET["book_id"];

    // Get the user_id from the session
    $user_id = $_SESSION["user_id"];

    // Delete the book from tblwishlist for the logged-in user
    $deleteQuery = "DELETE FROM tblwishlist WHERE ID = '$user_id' AND book_id = '$book_id'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    // Check if the deletion was successful
    if ($deleteResult) {
        // Redirect back to the wishlist page
        header("Location: wishlist.php");
        exit;
    } else {
        // Show an error message
        echo "Error deleting book from wishlist: " . mysqli_error($conn);
        exit;
    }
}

// Retrieve the user ID
$userId = $_SESSION["user_id"];

// Retrieve the books from tblwishlist for the current user
$query = "SELECT tblwishlist.wishlist_id, tblBooks.book_id, tblBooks.title, tblBooks.author, tblBooks.price, tblBooks.description, tblBooks.cover
          FROM tblwishlist
          INNER JOIN tblBooks ON tblwishlist.book_id = tblBooks.book_id
          WHERE tblwishlist.ID = $userId";
$result = mysqli_query($conn, $query);

// Function to remove a book from tblwishlist
function removeBookFromWishlist($wishlistId) {
    global $conn, $userId;
    $deleteQuery = "DELETE FROM tblwishlist WHERE wishlist_id = $wishlistId AND ID = $userId";
    mysqli_query($conn, $deleteQuery);
}

?>

<!DOCTYPE html>
<html>

<head>
    <link href="styles/homestyle.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Reuse Books - Wishlist</title>
    <style>
        /* Additional CSS styles for wishlist page */
        .book-thumbnail {
            max-width: 150px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .book-details {
            display: none;
        }

        .btn-add-to-cart {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
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
        <h2>Wishlist</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $wishlistId = $row['wishlist_id'];
                $bookId = $row['book_id'];
        ?>
                <!-- Book Thumbnail -->
                <img src="images/<?php echo $row['cover']; ?>" class="book-thumbnail" alt="<?php echo $row['title']; ?>" onclick="showBookDetails(<?php echo $bookId; ?>)">

                <!-- Book Details -->
                <div id="bookDetails_<?php echo $bookId; ?>" class="book-details">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><strong>Author:</strong> <?php echo $row['author']; ?></p>
                    <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
                    <p><strong>Description:</strong></p>
                    <p><?php echo $row['description']; ?></p>
                    <!-- Add to Cart button -->
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                        <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                    </form>
                </div>

                <!-- Remove from Wishlist button -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                    <button type="submit" class="btn btn-danger btn-remove">Remove from Wishlist</button>
                </form>
        <?php
            }
        } else {
            echo "<p>Your wishlist is empty.</p>";
        }
        ?>
    </div>

    <!-- JavaScript to toggle book details -->
    <script>
        function showBookDetails(bookId) {
            var bookDetails = document.getElementById('bookDetails_' + bookId);
            if (bookDetails.style.display === "none") {
                bookDetails.style.display = "block";
            } else {
                bookDetails.style.display = "none";
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua9k9L3ch9O2l0m6Xwjq8eU2cwlLKB5P4fXJdQWJqjiRgF5eEL3e/4Wt" crossorigin="anonymous"></script>
</body>

</html>
