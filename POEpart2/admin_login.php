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
     
    $sql = "SELECT id, username, password FROM tbladmin WHERE username = ?";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $username);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, set session variables and redirect to admin page
            $_SESSION["admin_id"] = $row["id"];
            header("Location: admin.php");
            exit;
        } else {
            // Password is incorrect
            $error_message = "Invalid username or password.";
        }
    } else {
        // Username doesn't exist
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles/loginstyle.css" />
    <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">ReuseBooks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="signup.php">SignUp</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <br> <br> <br>
    <h1>Admin Login</h1>
    <?php if (isset($error_message)) { ?>
        <p style="text-align: center;color:red"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="admin_login.php">
        <label>Username:</label>
        <input type="text" name="username" required /><br /><br />
        <label>Password:</label>
        <input type="password" name="password" required /><br /><br />
        <input type="submit" value="Login" />
    </form>
    <a href="login.php">Log In as a user</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
