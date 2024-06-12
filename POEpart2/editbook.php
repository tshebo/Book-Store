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

    // Retrieve the book details from the tblBooks table
    $query = "SELECT * FROM tblBooks WHERE book_id = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("i", $bookID);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows == 1) {
        $book = $result->fetch_assoc();
    } else {
        // Book not found, redirect back to the verifybook.php page
        header("Location: verifybook.php");
        exit;
    }
} else {
    // Redirect back to the verifybook.php page if book ID is not provided
    header("Location: verifybook.php");
    exit;
}

// Handle form submission for updating book details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $ISBN = $_POST["ISBN"];
    $genre = $_POST["genre"];
    $bookCondition = $_POST["bookCondition"];
    $price = $_POST["price"];
    $cover = $_POST["cover"];
    $description = $_POST["description"];

    // Update the book details in the tblBooks table
    $query = "UPDATE tblBooks SET title = ?, author = ?, ISBN = ?, genre = ?, bookCondition = ?, price = ?, cover = ?, description = ? WHERE book_id = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("sssssdssi", $title, $author, $ISBN, $genre, $bookCondition, $price, $cover, $description, $bookID);
    $statement->execute();

    // Redirect back to the verifybook.php page to see the updated table
    header("Location: verifybook.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles/adminstyle.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <a class="navbar-brand" href="#" data-abc="true">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="addBooks.php">Add Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="communication.php">Communication</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="verifybook.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3">
        <h2>Edit Book</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $book["title"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $book["author"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="ISBN">ISBN:</label>
                <input type="text" class="form-control" id="ISBN" name="ISBN" value="<?php echo $book["ISBN"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="genre">Genre:</label>
                <input type="text" class="form-control" id="genre" name="genre" value="<?php echo $book["genre"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="bookCondition">Condition:</label>
                <input type="text" class="form-control" id="bookCondition" name="bookCondition" value="<?php echo $book["bookCondition"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $book["price"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="cover">Cover:</label>
                <input type="text" class="form-control" id="cover" name="cover" value="<?php echo $book["cover"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $book["description"]; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="verifybook.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
