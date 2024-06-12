<?php
session_start();
include('DBConn.php');

if (!isset($_SESSION["admin_id"])) {
    // If an admin is not logged in, redirect to the admin login page
    header("Location: admin_login.php");
    exit;
}

// Process user signup validation
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["validate_signup"])) {
        $signupID = $_GET["validate_signup"];

        // Fetch the signup details from the signupvalidation table
        $sql = "SELECT * FROM signupvalidation WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $signupID);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows == 1) {
            // Signup found, retrieve the data
            $row = $result->fetch_assoc();

            // Insert the validated signup into the tblUser table
            $sql = "INSERT INTO tblUser (username, userlastname, student_number, password) VALUES (?, ?, ?, ?)";
            $statement = $conn->prepare($sql);
            $statement->bind_param("ssss", $row["username"], $row["userlastname"], $row["student_number"], $row["password"]);
            $statement->execute();

            // Delete the validated signup from the signupvalidation table
            $sql = "DELETE FROM signupvalidation WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("i", $signupID);
            $statement->execute();
        }
    } elseif (isset($_GET["delete_signup"])) {
        $signupID = $_GET["delete_signup"];

        // Delete the signup from the signupvalidation table
        $sql = "DELETE FROM signupvalidation WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $signupID);
        $statement->execute();

        // After deletion, redirect back to the admin page to see the updated table
        header("Location: admin.php");
        exit;
    } elseif (isset($_GET["delete_user"])) {
        $userID = $_GET["delete_user"];

        // Delete the user from the tblUser table
        $sql = "DELETE FROM tblUser WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $userID);
        $statement->execute();

        // After deletion, redirect back to the admin page to see the updated table
        header("Location: admin.php");
        exit;
    }
}

// Fetch validated users from the tblUser table
$sql = "SELECT id, username, userlastname, student_number FROM tblUser";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);

// Fetch unverified signups from the signupvalidation table
$sql = "SELECT id, username, userlastname, student_number FROM signupvalidation";
$result = $conn->query($sql);
$signups = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles/adminstyle.css" />
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
    <h1>Welcome, Admin</h1>
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <h2>Validated Users</h2>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Lastname</th>
                    <th>Student Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user["id"]; ?></td>
                        <td><?php echo $user["username"]; ?></td>
                        <td><?php echo $user["userlastname"]; ?></td>
                        <td><?php echo $user["student_number"]; ?></td>
                        <td>
                            <!-- Add a button to delete the user -->
                            <a href="admin.php?delete_user=<?php echo $user["id"]; ?>" class="btn btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Pending Signups</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Lastname</th>
                    <th>Student Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($signups as $signup) { ?>
                    <tr>
                        <td><?php echo $signup["id"]; ?></td>
                        <td><?php echo $signup["username"]; ?></td>
                        <td><?php echo $signup["userlastname"]; ?></td>
                        <td><?php echo $signup["student_number"]; ?></td>
                        <td>
                            <!-- Add buttons to validate or delete the signup -->
                            <a href="admin.php?validate_signup=<?php echo $signup["id"]; ?>" class="btn btn-outline-primary">Validate</a>
                            <a href="admin.php?delete_signup=<?php echo $signup["id"]; ?>" class="btn btn-outline-danger">Delete</a>
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