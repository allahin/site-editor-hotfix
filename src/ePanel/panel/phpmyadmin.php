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
    <title>ePanel | phpMyAdmin</title>
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

input[type="text"] {
  width: 200px;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
  outline: none;
}


input[type="text"]::placeholder {
  color: #999;
}

button {
  padding: 6px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  outline: none;
}

button:hover {
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
            <h1 class="font-bold"><a href="../../phpmyadmin">phpMyAdmin</a> page (if have)</h1>
            <script>
function aramaYap() {
    var arananMetin = document.getElementById("arananMetin").value;
    
    if (arananMetin.trim() === "") {
        alert("Do not enter blank text. Son of a bitch");
        return;
    }

    var sayfaMetni = document.documentElement.innerHTML;

    if (sayfaMetni.indexOf(arananMetin) > -1) {
        var yeniMetin = sayfaMetni.replace(new RegExp(arananMetin, 'g'), "<span style='background-color: #88B04B'>" + arananMetin + "</span>");
        document.documentElement.innerHTML = yeniMetin;

        var hedef = document.querySelector("span[style='background-color: #88B04B']");
        hedef.scrollIntoView();
    } else {
        alert("The searched text was not found.");
    }
}
</script>
    <input type="text" id="arananMetin" placeholder="name" required>
    <button onclick="aramaYap()">Search</button>
            <?php
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $databaseName = $row["Database"];

        echo '<h2 class="text-xl mt-6"><a href="../../phpmyadmin/index.php?route=/database/structure&db=' . $databaseName . '">' . $databaseName . '</a></h2>';

        $tableSql = "SHOW TABLES FROM $databaseName";
        $tableResult = $conn->query($tableSql);

        if ($tableResult->num_rows > 0) {
            echo '<ul class="list-disc list-inside ml-4">';
            while ($tableRow = $tableResult->fetch_assoc()) {
                $tableName = $tableRow["Tables_in_$databaseName"];

                echo '<li><a href="../../phpmyadmin/index.php?route=/sql&pos=0&db=' . $databaseName . '&table=' . $tableName . '">' . $tableName . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p class="text-gray-600 ml-4">There are no tables in this database.</p>';
        }
    }
} else {
    echo '<p class="text-gray-600">No database was found.</p>';
}
?>
<br><br>
</div>
</div>
</body>
</html>