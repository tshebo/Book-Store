<?php
session_start();

include('DBConn.php'); // Connection to the database

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve book details from the form
    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];
    $genre = $_POST["genre"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    // Check if a cover image was uploaded
    if (isset($_FILES["cover"]) && $_FILES["cover"]["error"] === UPLOAD_ERR_OK) {
        $cover = $_FILES["cover"]["name"];

        // Move the uploaded file to a specific location
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover);
        move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file);

        // Prepare the SQL statement
        $query = "INSERT INTO verifybook (title, author, ISBN, genre, bookCondition, price, cover, description)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $title, $author, $isbn, $genre, $condition, $price, $cover, $description);

        // Execute the statement
        $result = mysqli_stmt_execute($stmt);

        // Check if the execution was successful
        if ($result) {
            // Book request inserted successfully
            header("Location: sellbook.php");
            exit;
        } else {
            $error = "Failed to insert the book request into the database.";
        }
    } else {
        $error = "Please upload a cover image.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<link href="styles/background.css" rel="stylesheet">
    <link href="styles/homestyle.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>User - Sell Book</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <a class="navbar-brand" href="home.php" data-abc="true">ReuseBooks</a>
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

    <div class="container mt-5">
        <h2>Sell Book</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" required>
            </div>
            <div class="form-group">
                <label for="condition">Condition</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition" id="new" value="New" required>
                    <label class="form-check-label" for="new">
                        New
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition" id="fine" value="Fine" required>
                    <label class="form-check-label" for="fine">
                        Fine
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition" id="good" value="Good" required>
                    <label class="form-check-label" for="good">
                        Good
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition" id="fair" value="Fair" required>
                    <label class="form-check-label" for="fair">
                        Fair
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="condition" id="poor" value="Poor" required>
                    <label class="form-check-label" for="poor">
                        Poor
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="cover">Cover</label>
                <input type="file" class="form-control-file" id="cover" name="cover" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Sell Book</button>
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger mt-3">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>

