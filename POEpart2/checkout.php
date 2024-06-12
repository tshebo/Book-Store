<?php
session_start();
include('DBConn.php'); // Connection

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Retrieve user information from tblUser
$user_id = $_SESSION["user_id"];
$userQuery = "SELECT username, student_number FROM tblUser WHERE id = '$user_id'";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Error: " . mysqli_error($conn));
}

$userData = mysqli_fetch_assoc($userResult);
$username = $userData['username'];
$studentNumber = $userData['student_number'];

// Retrieve cart information from tblCart and associated book information from tblBooks
$cartQuery = "SELECT c.cart_id, b.title, b.price
              FROM tblCart c
              INNER JOIN tblBooks b ON c.book_id = b.book_id
              WHERE c.ID = '$user_id'";
$cartResult = mysqli_query($conn, $cartQuery);

if (!$cartResult) {
    die("Error: " . mysqli_error($conn));
}

// Calculate the total price
$total = 0;
if (mysqli_num_rows($cartResult) > 0) {
    while ($row = mysqli_fetch_assoc($cartResult)) {
        $total += $row['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<!-- ...head section code... -->
<style>
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
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
        <h1>Checkout</h1>

        <h3>User Information:</h3>
        <p>Username: <?php echo $username; ?></p>
        <p>Student Number: <?php echo $studentNumber; ?></p>

        <h3>Cart Information:</h3>
        <?php if (mysqli_num_rows($cartResult) > 0) { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($cartResult, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($cartResult)) {
                        echo "<tr>";
                        echo "<td>{$row['title']}</td>";
                        echo "<td>R{$row['price']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else {
            echo "<p>No items in the cart.</p>";
        } ?>

        <h3>Total Price: R<?php echo $total; ?></h3>

        <h3>Order ID: <?php echo $user_id; ?></h3>

        <h3>Payment Details:</h3>
        <form id="paymentForm">
            <div class="form-group">
                <label for="cardNumber">Card Number</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
            </div>
            <div class="form-group">
                <label for="expiryDate">Expiry Date</label>
                <input type="text" class="form-control" id="expiryDate" name="expiryDate" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>
            
        </form>

        <h3>Address:</h3>
        <form id="addressForm">
            <div class="form-group">
                <label for="street">Street</label>
                <input type="text" class="form-control" id="street" name="street" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="state">Province</label>
                <input type="text" class="form-control" id="province" name="province" required>
            </div>
            <div class="form-group">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" required>
            </div>
             
        </form>

        
        

         
        <div class="button-container">
            <form action="login.php">
                <button type="submit" class="btn btn-primary">Confirm Checkout</button>
            </form>
        </div>
    </div>
        
    </div>

    <!-- ...script and footer code... -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle payment form submission
            $('#paymentForm').submit(function(event) {
                event.preventDefault();
                //payment processing logic here

                //   show an alert with the payment success message
                alert('Payment successful!');
            });

            // Handle address form submission
            $('#addressForm').submit(function(event) {
                event.preventDefault();
              

                //  alert with the address submission success message
                alert('Address submitted successfully!');
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
