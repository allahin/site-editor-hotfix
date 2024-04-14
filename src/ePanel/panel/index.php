<?php $start = microtime(true); ?>
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
    <link href="../../assets/css/tailwind.css" rel="stylesheet">
    <link rel="icon" href="<?= file_exists('../../favicon.txt') ? file_get_contents('../../favicon.txt') : "Fatal error, please reinstall." ?>">
    <title>ePanel</title>
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
    .table-container {
      float: right;
      width: 400px;
    }

    .allah {
      width: 340px;
    }
  </style>
</head>
<body class="flex justify-center items-center h-screen">
    <div class="w-3/4">
    <div id="taskbar">
    <a id="logo" href=""><img src="../../assets/img/home.jpg" width="32" height="32" alt=Home title="Home"></a>
  </div>
    <div class="flex items-center px-4 py-2">
        <div class="flex items-center mr-5">
            <a href=""><img src="../../assets/img/epanel.jpg" alt="logo" width="200" height="50"></a>
        </div>
    </div>
    <div class="table-container">
    <table class="w-full bg-white shadow-lg">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 text-left">Analyzer</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="py-2 px-4 border-b"><?php
$ip = $_SERVER['REMOTE_ADDR'];
echo "IP: " . $ip;
?>
</td>
        </tr>
        <tr>
          <td class="py-2 px-4 border-b"><?php
$ip = gethostbyname($_SERVER['SERVER_NAME']);
echo "Sites IP: " . $ip;
?>
</td>
</tr>
<tr>
<td class="py-2 px-4 border-b"><?php
$ip = php_uname();
echo "Your device: " . $ip;
?>
</td>
        </tr>
      </tbody>
    </table>
</div>
    <div class="container mx-auto p-6">
        <div class="mt-6">
            <div class="text-2xl font-bold">General</div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg">
                <a href="filemanager"><img src="../../assets/img/file.jpg" class="w-12 m-4"></a>
                <a href="filemanager"><span>File Manager</span></a>
                </div>
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg allah">
                <a href="manager"><img src="" class="w-12 m-4"></a>
                <a href="manager"><span>Manager</span></a>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <div class="text-2xl font-bold">Databases</div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg">
                <a href="mysql.php"><img src="../../assets/img/mysql.jpg" class="w-12 m-4"></a>
                <a href="mysql.php"><span>MySQL</span></a>
                </div>
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg">
                <a href="phpmyadmin.php"><img src="../../assets/img/phpmyadmin.jpg" class="w-12 m-4"></a>
                <a href="phpmyadmin.php"><span>phpMyAdmin</span></a>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <div class="text-2xl font-bold">Advanced</div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg">
                <a href="settings.php"><img src="../../assets/img/settings.jpg" class="w-12 m-4"></a>
                <a href="settings.php"><span>Settings</span></a>
                </div>
                <div class="flex items-center bg-orange-500 rounded overflow-hidden shadow-lg">
                <a href="recommended.php"><img src="../../assets/img/tik.jpg" class="w-12 m-4"></a>
                <a href="recommended.php"><span>Requirements after setup</span></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$end = microtime(true);
$time = $end - $start;
$timeFormatted = number_format($time, 2);

echo "Page opening time: " . $timeFormatted . " second";
?>