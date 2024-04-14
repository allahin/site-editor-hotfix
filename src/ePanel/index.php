<?php
require_once 'connect.php';

session_start();

$message = '';

if (isset($_SESSION['username'])) {
    header("Location: panel");
    exit();
}

if (isset($_COOKIE['panel'])) {
    $username = $_COOKIE['panel'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    $stmt->close();

    if ($count === 1) {
        $_SESSION['username'] = $username;

        header("Location: panel");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        setcookie("panel", $username, time() + (7 * 24 * 60 * 60)); // 7 günlük süre

        $_SESSION['username'] = $username;

        header("Location: ../../epanel/panel");
        exit();
    } else {
        $message = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="icon" type="image/x-icon" href="<?= file_exists('../../favicon.txt') ? file_get_contents('../../favicon.txt') : "Fatal error, please reinstall." ?>">
  <style>
    body {
      background-color: #f8f9fa;
    }
  
    .card {
      border: none;
      border-radius: 0;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  
    .card-title {
      font-size: 24px;
    }
  
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
  
    .btn-primary:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }
  
    .text-decoration-none {
      text-decoration: none;
    }
  </style>
  <title>ePanel | Login</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Admin Panel</h2>
            <form method="POST">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
              </div>
              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>