<?php
session_start();

require_once '../../connect.php';

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
    header("Location: ../../../admin");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../assets/css/tailwind.css" rel="stylesheet">
    <link rel="icon" href="<?= file_exists('../../../favicon.txt') ? file_get_contents('../../../favicon.txt') : "Fatal error, please reinstall." ?>">
    <title>ePanel</title>
    <style>
    /* Tema Renkleri */
    :root {
        --bg-color: #1a1a1a; /* Arka plan rengi */
        --text-color: #f2f2f2; /* Metin rengi */
        --accent-color: #FFA500; /* Vurgu rengi */
    }

    /* Genel Stiller */
    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        font-family: Arial, sans-serif;
    }

    #taskbar {
      background-color: #333;
      padding: 10px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 9999;
    }

    #logo {
      color: #f2f2f2;
      font-weight: bold;
      text-decoration: none;
    }

    .table-container {
      float: right;
      width: 400px;
    }

    .allah {
      width: 350px;
      margin-left: -145px;
    }

    /* Karanlık Tema Özelleştirmeleri */
    .bg-orange-500 {
        background-color: #ff4d00;
    }

    .shadow-lg {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    a, a:hover, a:visited {
        color: var(--text-color);
    }

    .container {
        margin-bottom: 200px;
    }

    .allahyok {
        width: 400px;
    }

    .allahsiz {
      width: 400px;
      margin-left: -145px;
    }
  </style>
</head>
<body class="flex justify-center items-center h-screen">
    <div class="w-3/4">
    <div id="taskbar">
    <a id="logo" href=""><img src="../../../assets/img/home.jpg" width="32" height="32" alt=Home title="Home"></a>
  </div>
    <div class="flex items-center px-4 py-2">
        <div class="flex items-center mr-5">
            <a href=""><img src="../../../assets/img/epanel.jpg" alt="logo" width="200" height="50"></a><br><br><br>emirPanel<br> Blog manager, use the file manager if you want to be deprived of the adjustments here.
        </div>
    </div>
    <div class="container mx-auto p-6">
        <div class="mt-6">
            <div class="text-2xl font-bold">General</div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg allahyok">
                <a href="filemanager"><img src="../../assets/img/file.jpg" class="w-12 m-4"></a>
                <a href="filemanager"><span>Homepage</span></a>
                </div>
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg allah">
                <a href="manager"><img src="../../assets/img/manager.png" class="w-12 m-4"></a>
                <a href="manager"><span>Pages</span></a>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <div class="text-2xl font-bold">Blog</div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg allahyok">
                <a href="mysql"><img src="../../assets/img/mysql.jpg" class="w-12 m-4"></a>
                <a href="mysql"><span>Articles</span></a>
                </div>
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg allahsiz">
                <a href="phpmyadmin"><img src="../../assets/img/phpmyadmin.jpg" class="w-12 m-4"></a>
                <a href="phpmyadmin"><span>Comments</span></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>