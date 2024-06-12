<?php
session_start();
include('DBConn.php');

if (!isset($_SESSION["admin_id"])) {
    // If an admin is not logged in, redirect to the admin login page
    header("Location: admin_login.php");
    exit;
}

// Fetch books from tblBooks
$tblBooks_query = "SELECT * FROM tblBooks";
$tblBooks_result = mysqli_query($conn, $tblBooks_query);

// Fetch books from verifyBook
$verifyBook_query = "SELECT * FROM verifyBook";
$verifyBook_result = mysqli_query($conn, $verifyBook_query);

// Verify book by uploading it to tblBooks
if (isset($_GET['verifybook'])) {
    $book_id = $_GET['verifybook'];

    // Retrieve book details from verifyBook
    $verifyBook_details_query = "SELECT * FROM verifyBook WHERE book_id = '$book_id'";
    $verifyBook_details_result = mysqli_query($conn, $verifyBook_details_query);
    $book_data = mysqli_fetch_assoc($verifyBook_details_result);

    // Insert the book into tblBooks
    $insert_query = "INSERT INTO tblBooks (title, author, ISBN, genre, bookCondition, price, cover, description) VALUES (
        '{$book_data['title']}', '{$book_data['author']}', '{$book_data['ISBN']}', '{$book_data['genre']}', '{$book_data['bookCondition']}', '{$book_data['price']}', '{$book_data['cover']}', '{$book_data['description']}'
    )";
    $insert_result = mysqli_query($conn, $insert_query);

    if ($insert_result) {
        // Delete the book from verifyBook
        $delete_query = "DELETE FROM verifyBook WHERE book_id = '$book_id'";
        $delete_result = mysqli_query($conn, $delete_query);

        if ($delete_result) {
            header("Location: verifybook.php");
            exit;
        } else {
            echo "Error deleting book from verifyBook: " . mysqli_error($conn);
            exit;
        }
    } else {
        echo "Error inserting book into tblBooks: " . mysqli_error($conn);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Verify Books</title>
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
                    <a class="nav-link active" href="addBooks.php">Add Books</a>
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
                    <a class="nav-link" href="verifybook.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3">
        <h1>Verify Books</h1>

        <h2>Books in tblBooks</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Genre</th>
                    <th>Book Condition</th>
                    <th>Price</th>
                    <th>Cover</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($tblBooks_result)) { ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['ISBN']; ?></td>
                        <td><?php echo $row['genre']; ?></td>
                        <td><?php echo $row['bookCondition']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['cover']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <a href="editbook.php?book_id=<?php echo $row['book_id']; ?>">Edit</a>
                            <a href="deletebook.php?book_id=<?php echo $row['book_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Books in verifyBook</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Genre</th>
                    <th>Book Condition</th>
                    <th>Price</th>
                    <th>Cover</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($verifyBook_result)) { ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['ISBN']; ?></td>
                        <td><?php echo $row['genre']; ?></td>
                        <td><?php echo $row['bookCondition']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['cover']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <a href="editbook.php?book_id=<?php echo $row['book_id']; ?>">Edit</a>
                            <a href="deletebook.php?book_id=<?php echo $row['book_id']; ?>">Delete</a>
                            <a href="verifybook.php?verifybook=<?php echo $row['book_id']; ?>">Verify</a> <!-- Add the verify link -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
