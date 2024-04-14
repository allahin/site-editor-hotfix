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
    <title>ePanel | Recommended</title>
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
        $file = '../../start.php';
        
        if (file_exists($file)) {
            echo '<div class="flex items-center bg-yellow-200 text-yellow-900 text-sm p-4 mb-4">';
            echo '<svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-8h2.5l-3-3-3 3H9v2h2v-2z"/></svg>';
            echo 'Unnecessary File exists. Please click the button to delete.';
            echo '</div>';
            echo '<form action="" method="post">';
            echo '<button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">';
            echo 'Clean';
            echo '</button>';
            echo '</form>';
        } else {
            echo '<div class="flex items-center bg-green-200 text-green-900 text-sm p-4 mb-4">';
            echo '<svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>';
            echo 'The system is correct. You dont need to take action.';
            echo '</div>';
        }
        ?>
    </div>
    <?php
    if (isset($_POST['delete'])) {
        if (file_exists($file)) {
            unlink($file);
            echo '<script>alert("Cleaned.");</script>';
        }
    }
    ?>
    </div>
</div>
</body>
</html>