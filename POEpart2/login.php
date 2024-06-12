<?php
session_start();
include('DBConn.php');
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $studentNumber = $_POST['StudentNo'];
  $password = $_POST['password'];
  $hash = password_hash($password, PASSWORD_DEFAULT);

  $conn = new mysqli("localhost", "root", "", "bookstore");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = sprintf(
    "SELECT * FROM tbluser WHERE student_number = '%s'",
    $conn->real_escape_string($_POST["StudentNo"])
  );

  $result = $conn->query($sql);

  // return record as associative array
  $user_data = $result->fetch_assoc();

  if ($user_data) {
    if (password_verify($password, $user_data["password"])) {

      $_SESSION["user_id"] = $user_data["id"];
      $_SESSION["user_firstname"] = $user_data["username"];
      $_SESSION["user_lastname"] = $user_data["userlastname"];

      header("Location: home.php");
      exit;
    }
  }
  $is_invalid = true;

  $conn->close(); // Close the database connection
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

  <link rel="stylesheet" href="styles/loginstyle.css" />
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
            <a class="nav-link" href="home.php">Home </a>
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
  <h1>User Login</h1>

  <div style="text-align: center; color: red;">
    <!--Displays error message-->
    <?php if ($is_invalid) : ?>
      <em>Invalid Credentials</em>
    <?php endif; ?>
  </div>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="studentNo">Student Number:</label>
    <input type="text" name="StudentNo" id="studentNumber" value="<?= htmlspecialchars($_POST["StudentNo"] ?? "") ?>"> <br /><br />
    <label>Password:</label>
    <input type="password" name="password" id="password" /><br /><br />
    <input type="submit" value="Login" name="login" />
  </form>
  <a href="admin.php">Log In as Admin</a>
  <!-- sticky form-->
  <script language="javascript">
    let studentNumber = document.getElementById("studentNumber");
    let password = document.getElementById("password");
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>