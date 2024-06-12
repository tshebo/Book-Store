<?php
session_start();
include('DBConn.php');

// Retrieve the messages from the tblMessages table
$sql = "SELECT * FROM tblMessages";
$result = $conn->query($sql);
$messages = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Communications</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <a class="navbar-brand" href="admin.php" data-abc="true">Admin Panel</a>
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
    <div class="container">
        <h1>Communications</h1>
        <?php if (count($messages) > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) { ?>
                        <tr>
                            <td><?php echo $message["name"]; ?></td>
                            <td><?php echo $message["email"]; ?></td>
                            <td><?php echo $message["subject"]; ?></td>
                            <td><?php echo $message["message"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No messages found.</p>
        <?php } ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua6jl9pzwpX8c+R5PA7PxzMS4x0qX/kX7RtoZYWhse4j6vD5vNcLl1Kj" crossorigin="anonymous"></script>
</body>

</html>
