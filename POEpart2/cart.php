<?php
session_start();
include('DBConn.php'); // Connection

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Check if the book_id parameter is provided for individual book deletion
if (isset($_GET["book_id"])) {
    // Get the book_id from the parameter
    $book_id = $_GET["book_id"];

    // Get the user_id from the session
    $user_id = $_SESSION["user_id"];

    // Delete the book from tblCart for the logged-in user
    $deleteQuery = "DELETE FROM tblCart WHERE id = '$user_id' AND book_id = '$book_id'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    // Check if the deletion was successful
    if ($deleteResult) {
        // Redirect back to the cart page
        header("Location: cart.php");
        exit;
    } else {
        // Show an error message
        echo "Error deleting book from cart: " . mysqli_error($conn);
        exit;
    }
}

// Check if the "Empty Cart" button is clicked
if (isset($_POST["empty_cart"])) {
    // Get the user_id from the session
    $user_id = $_SESSION["user_id"];

    // Delete all books from tblCart for the logged-in user
    $emptyCartQuery = "DELETE FROM tblCart WHERE id = '$user_id'";
    $emptyCartResult = mysqli_query($conn, $emptyCartQuery);

    // Check if the deletion was successful
    if ($emptyCartResult) {
        // Redirect back to the cart page
        header("Location: cart.php");
        exit;
    } else {
        // Show an error message
        echo "Error emptying cart: " . mysqli_error($conn);
        exit;
    }
}

// Retrieve books from tblCart for the logged-in user
$user_id = $_SESSION["user_id"];
$query = "SELECT tblBooks.book_id, tblBooks.cover, tblBooks.title, tblBooks.price FROM tblBooks INNER JOIN tblCart ON tblBooks.book_id = tblCart.book_id WHERE tblCart.id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Calculate the total price
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total += $row['price'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="styles/background.css" rel="stylesheet">
    <link href="styles/homestyle.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        .book-cover-small {
            max-width: 100px;
            /* Adjust the width as needed */
            max-height: 150px;
            /* Adjust the height as needed */
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


    <div class="container">
        <h1>Cart</h1>

        <?php
        // Check if there are no items in the cart
        if (mysqli_num_rows($result) === 0) {
            echo '<p>No items in the cart. Please add items to your cart to proceed.</p>';
        } else {
        ?>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result, 0); // Reset the result pointer
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td><img src='images/{$row['cover']}' class='book-cover-small' alt='Book Cover'></td>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['price']}</td>";
                            echo "<td><a href='cart.php?book_id={$row['book_id']}' class='btn btn-danger'>Delete</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td><?php echo $total; ?></td>
                            <td><a href="home.php" class="btn btn-primary">Continue Shopping</a></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center">
                                <a href="checkout.php" class="btn btn-success">Checkout</a>
                                <button type="submit" name="empty_cart" class="btn btn-danger">Empty Cart</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        <?php
        }
        ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>