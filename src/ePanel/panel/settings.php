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
    <title>ePanel | Settings</title>
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
    
label {
     display: block;
    margin-bottom: 5px;
}
    
input[type="text"] {
    width: calc(100% - 12px);
    padding: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
    
.allah {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    width: 150px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}
    
.allah:hover {
    background-color: #0056b3;
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
$siteDosya = "../../site.txt";
$faviconDosya = "../../favicon.txt";

$siteAdi = "";
$faviconUrl = "";

if (file_exists($siteDosya)) {
    $siteAdi = file_get_contents($siteDosya);
}

if (file_exists($faviconDosya)) {
    $faviconUrl = file_get_contents($faviconDosya);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siteAdi = $_POST["siteAdi"];
    $faviconUrl = $_POST["faviconUrl"];

    file_put_contents($siteDosya, $siteAdi);

    file_put_contents($faviconDosya, $faviconUrl);
}
?>
    <div>
        <h2>Sitename: <?php echo $siteAdi; ?></h2>
        <h2>Favicon URL: <?php echo $faviconUrl; ?></h2><br>
    </div>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="siteAdi">New sitename:</label>
        <input type="text" id="siteAdi" name="siteAdi" value="" required>
        <label for="faviconUrl">New favicon URL:</label>
        <input type="text" id="faviconUrl" name="faviconUrl" value="" required>
        <br>
        <input class="allah" type="submit" value="Update">
    </form>
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