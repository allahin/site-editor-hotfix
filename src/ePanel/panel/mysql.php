<?php
session_start();

require_once '../connect.php';

if (isset($_COOKIE['panel'])) {
    $username = $_COOKIE['panel'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    $stmt->close();

    if ($count === 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
    }
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../admin");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= file_exists('../../favicon.txt') ? file_get_contents('../../favicon.txt') : "Fatal error, please reinstall." ?>">
    <title>ePanel | MySQL</title>
    <style>
    #taskbar {
      background-color: #f2f2f2;
      padding: 10px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 9999;
    }
    
    #logo {
      color: #333;
      font-weight: bold;
      text-decoration: none;
    }
    .logo-container {
            position: absolute;
            top: 80px;
        }
        .selam {
            position: absolute;
            top: 150px;
        }
  </style>
</head>
<body class="flex justify-center items-center h-screen">
<div class="w-3/4">
    <div id="taskbar">
    <a id="logo" href="index.php"><img src="../../assets/img/home.jpg" width="32" height="32" alt=Home title="Home"></a>
  </div>
    <div class="flex items-center px-4 py-2">
        <div class="flex items-center mr-5 logo-container">
            <a href="index.php"><img src="../../assets/img/epanel.jpg" alt="logo" width="200" height="50"></a>
        </div>
    </div>
        <div class="container mx-auto p-6">
        <div class="mt-6 selam">
  <?php
  if (isset($_POST['create_db'])) {
    $db_name = $_POST['db_name'];

    if (empty($db_name)) {
      echo '<div class="bg-red-200 p-2 mb-4">Database name cannot be left blank.</div>';
    } else {
      $create_db_query = "CREATE DATABASE $db_name";
      if ($conn->query($create_db_query) === TRUE) {
        echo '<div class="bg-green-200 p-2 mb-4">The database was created successfully.</div>';
      } else {
        echo '<div class="bg-red-200 p-2 mb-4">An error occurred while creating the database: ' . $conn->$error . '</div>';
      }
    }
  }

  if (isset($_POST['delete_db'])) {
    $db_name = $_POST['db_name'];

    if (empty($db_name)) {
      echo '<div class="bg-red-200 p-2 mb-4">Database name cannot be left blank.</div>';
    } else {
      $check_db_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'";
      $check_result = $conn->query($check_db_query);

      if ($check_result->num_rows > 0) {
        $delete_db_query = "DROP DATABASE $db_name";
        if ($conn->query($delete_db_query) === TRUE) {
          echo '<div class="bg-green-200 p-2 mb-4">The database has been successfully deleted.</div>';
        } else {
          echo '<div class="bg-red-200 p-2 mb-4">An error occurred while deleting the database: ' . $conn->$error . '</div>';
        }
      } else {
        echo '<div class="bg-red-200 p-2 mb-4">Database not found.</div>';
      }
    }
  }

  $show_databases_query = "SHOW DATABASES";
  $databases = $conn->query($show_databases_query);
  ?>

  <h2 class="text-lg font-bold mt-4">Databases</h2>
  <ul class="list-disc pl-8 mt-2">
    <?php while ($row = $databases->fetch_assoc()) : ?>
      <li><?php echo $row['Database']; ?></li>
    <?php endwhile; ?>
  </ul>

  <h2 class="text-lg font-bold mt-4">Database Create</h2>
  <form method="post" class="mb-4">
    <input type="text" name="db_name" placeholder="Database name" required class="px-2 py-1 border rounded">
    <button type="submit" name="create_db" class="ml-2 px-4 py-2 bg-blue-500 text-white font-semibold rounded">Create</button>
  </form>

  <h2 class="text-lg font-bold mt-4">Database Delete</h2>
  <form method="post" class="mb-4">
    <input type="text" name="db_name" placeholder="Database name" required class="px-2 py-1 border rounded">
    <button type="submit" name="delete_db" class="ml-2 px-4 py-2 bg-red-500 text-white font-semibold rounded">Delete</button>
  </form>
    </div>
</div>
</body>
</html>