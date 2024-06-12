<?php
session_start();
include('DBConn.php');

if (isset($_SESSION["admin_id"])) {
    // If an admin is already logged in, redirect to the admin page
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO tblAdmin (username, password) VALUES (?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $username, $hashed_password);

    if ($statement->execute()) {
        // Admin registration successful, redirect to the admin login page
        header("Location: admin_login.php");
        exit;
    } else {
        // Error occurred during registration
        $error_message = "Error: " . $statement->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
    <link rel="stylesheet" href="styles/adminstyle.css" />
</head>
<body>
    <header>
        <div class="banner">
            <h1>Reuse Books</h1>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </header>
    <h1>Admin Signup</h1>
    <?php if (isset($error_message)) { ?>
        <p style="text-align: center;color:red"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="adminsignup.php">
        <label>Username:</label>
        <input type="text" name="username" required /><br /><br />
        <label>Password:</label>
        <input type="password" name="password" required /><br /><br />
        <input type="submit" value="Signup" />
    </form>
    <a href="admin_login.php">Go to Admin Login</a>
</body>
</html>
