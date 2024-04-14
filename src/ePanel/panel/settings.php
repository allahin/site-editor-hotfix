<?php
session_start();

require_once '../connect.php';

// Kullanıcı girişi kontrolü
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

// Kullanıcı girişi yapılmamışsa geri yönlendir
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
    /* Görev çubuğu stilleri */
    #taskbar {
      background-color: #f2f2f2;
      padding: 10px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 9999;
    }
    
    /* Logo stilleri */
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
        .allah {
	display: inline-block;
	text-decoration: none;
	font-size: 13px;
	line-height: 2.15384615; /* 28px */
	min-height: 30px;
	margin: 0;
	padding: 0 10px;
	cursor: pointer;
	border-width: 1px;
	border-style: solid;
	-webkit-appearance: none;
	border-radius: 3px;
	white-space: nowrap;
	box-sizing: border-box;
    background: #2271b1;
	border-color: #2271b1;
	color: #fff;
	text-decoration: none;
	text-shadow: none;
}

/* Input Alanı Stili */
input[type="text"],
input[type="email"],
input[type="password"],
textarea,
select {
  padding: 2px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box; /* İçeriği border içine alır */
  width: 150px; /* Genişlik ayarla */
  margin-bottom: 10px; /* Alt boşluk ekle */
  font-size: 16px; /* Yazı tipi boyutu */
}

/* Odaklandığında Stil */
input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus,
textarea:focus,
select:focus {
  border-color: #007bff; /* Odaklandığında kenarlık rengi */
  outline: none; /* Varsayılan odaklanma rengini kaldır */
}

/* Buton Stili */
button {
  padding: 10px 20px;
  background-color: #007bff; /* Arkaplan rengi */
  border: none; /* Kenarlık yok */
  border-radius: 5px;
  color: #fff; /* Yazı rengi */
  cursor: pointer; /* İmleci el olarak göster */
  font-size: 16px; /* Yazı tipi boyutu */
}

button:hover {
  background-color: #0056b3; /* Üzerine gelindiğinde arkaplan rengi */
}

  </style>
</head>

<body class="flex justify-center items-center h-screen">
<div class="w-3/4">
    <div id="taskbar">
    <a id="logo" href="index.php"><img src="https://i.hizliresim.com/3fvkof0.png" width="32" height="32" alt=Home title="Home"></a>
  </div>
    <div class="flex items-center px-4 py-2">
        <div class="flex items-center mr-5 logo-container">
            <a href="index.php"><img src="https://i.hizliresim.com/5skdb9q.png" alt="logo" width="200" height="50"></a>
        </div>
    </div>
        <div class="container mx-auto p-6">
        <div class="mt-6 selam">
        <?php
// site.txt ve favicon.txt dosyalarının yolları
$siteDosya = "../../site.txt";
$faviconDosya = "../../favicon.txt";

// site adını ve favicon url'sini saklamak için değişkenler
$siteAdi = "";
$faviconUrl = "";

// site.txt dosyasından site adını al
if (file_exists($siteDosya)) {
    $siteAdi = file_get_contents($siteDosya);
}

// favicon.txt dosyasından favicon url'sini al
if (file_exists($faviconDosya)) {
    $faviconUrl = file_get_contents($faviconDosya);
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Site adı ve favicon url'sini güncelle
    $siteAdi = $_POST["siteAdi"];
    $faviconUrl = $_POST["faviconUrl"];

    // site.txt dosyasını güncelle
    file_put_contents($siteDosya, $siteAdi);

    // favicon.txt dosyasını güncelle
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
