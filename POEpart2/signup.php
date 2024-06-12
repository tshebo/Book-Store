<?php
session_start();
include('DBConn.php');

if (isset($_POST['SignUp'])) {
    $firstname = $_POST["firstName"];
    $lastname = $_POST["lastName"];
    $studentNo = $_POST["studentNumber"];
    $password = $_POST["password"];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if (empty($firstname)) {
        die("First name cannot be left empty");
    }
    if (strlen($password) < 3) {
        die("Password is too short");
    }
    if (!preg_match("/[a-z]/i", $password)) {
        die("Password must contain at least one letter");
    }

    // Prepare the INSERT statement
    $sql = "INSERT INTO signupvalidation (username, userlastname, student_number, password) VALUES (?, ?, ?, ?)";
    $statement = $conn->prepare($sql);

    if (!$statement) {
        die("Error: " . $conn->error);
    }

    // Bind the parameters and execute the statement
    $statement->bind_param("ssss", $firstname, $lastname, $studentNo, $hash);

    if ($statement->execute()) {
        header("Location: processing.html");
        exit;
    } else {
        if ($conn->errno === 1062) {
            die("The student number has already been registered");
        } else {
            die($conn->error . " " . $conn->errno);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reuse Books - Sign Up</title>
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
    <h1>Sign Up for Reuse Books</h1>
    <form id="signup" method="post" action="signup.php">
        <label>First Name:</label>
        <input type="text" name="firstName" required><br><br>
        <label>Last Name:</label>
        <input type="text" name="lastName" required><br><br>
        <label>Student Number:</label>
        <input type="text" name="studentNumber" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Sign Up" name="SignUp">

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>