<?php
if (filesize('ePanel/connect.php') > 0) {
    header('Location: admin');
    exit;
}
?>
<?php
$host = "";
$username = "";
$password = "";
$database = "";

$cookieName = "kurucu";

$cookieExpiration = time() + (24 * 60 * 60);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userHost = $_POST["host"];
    $userUsername = $_POST["username"];
    $userPassword = $_POST["password"];
    $userDatabase = $_POST["database"];
    $userAccountUsername = $_POST["account_username"];
    $userAccountPassword = $_POST["account_password"];

    $conn = new mysqli($userHost, $userUsername, $userPassword, $userDatabase);

    if ($conn->connect_error) {
        $errorMessage = "fatal error" . $conn->connect_error;
        echo '<div class="error-message"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> ' . $errorMessage . '</span></div>';
    } else {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";

        if ($conn->query($createTableQuery) === TRUE) {
            $hashedPassword = password_hash($userAccountPassword, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users (username, password) VALUES ('$userAccountUsername', '$hashedPassword')";

            if ($conn->query($insertQuery) === TRUE) {
                setcookie($cookieName, true, $cookieExpiration);

                $connectCode = '<?php' . PHP_EOL .
                    '$servername = "' . $userHost . '";' . PHP_EOL .
                    '$username = "' . $userUsername . '";' . PHP_EOL .
                    '$password = "' . $userPassword . '";' . PHP_EOL .
                    '$dbname = "' . $userDatabase . '";' . PHP_EOL . PHP_EOL .
                    '$conn = new mysqli($servername, $username, $password, $dbname);' . PHP_EOL . PHP_EOL .
                    'if ($conn->connect_error) {' . PHP_EOL .
                    '    die("fatal error" . $conn->connect_error);' . PHP_EOL .
                    '}' . PHP_EOL . PHP_EOL .
                    '?>';

                if (file_put_contents('ePanel/connect.php', $connectCode) !== false) {
                    echo '<div class="success-message"><span class="text-success"><i class="fas fa-check-circle"></i>okey</span></div>';

                    $indexFilePath = 'index.php';
                    $indexFileContent = file_get_contents($indexFilePath);
                    $indexFileContent = str_replace('href="start"', 'href="ePanel"', $indexFileContent);
                    file_put_contents($indexFilePath, $indexFileContent);

                } else {
                    echo '<div class="error-message"><span class="text-danger"><i class="fas fa-exclamation-circle"></i>error</span></div>';
                }

                header("Location: epanel");
                exit;
            } else {
                $errorMessage = "error" . $conn->error;
                echo '<div class="error-message"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> ' . $errorMessage . '</span></div>';
            }
        } else {
            $errorMessage = "fatal error" . $conn->error;
            echo '<div class="error-message"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> ' . $errorMessage . '</span></div>';
        }

        $conn->close();
    }
}

if (isset($_COOKIE[$cookieName])) {
    header("Location: epanel");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's start | <?= file_exists('site.txt') ? file_get_contents('site.txt') : "Fatal error, please reinstall." ?></title>
    <link rel="icon" href="<?= file_exists('favicon.txt') ? file_get_contents('favicon.txt') : "Fatal error, please reinstall." ?>
">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        body {
            margin-top: 41px;
            margin-bottom: 41px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 110vh;
            background-color: #f8f9fa;
        }
        .error-message {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
            <h1><?php echo htmlspecialchars("Wewelcome!"); ?></h1>
                <p>Create a database and enter your information. Also, enter the admin account information you will create. <a href="admin">Admin Panel</a><br>Fatal error, warning errors mean that your database or host information is incorrect.</p>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="host">Host:</label>
                        <input type="text" class="form-control" id="host" name="host" autocomplete="off" value="localhost" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="username">Database Username:</label>
                        <input type="text" class="form-control" id="username" name="username" autocomplete="off" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="password">Database Password:</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="database">Database Name:</label>
                        <input type="text" class="form-control" id="database" name="database" autocomplete="off" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="account_username">Admin Name:</label>
                        <input type="text" class="form-control" id="account_username" name="account_username" autocomplete="off" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="account_password">Admin Password:</label>
                        <input type="password" class="form-control" id="account_password" name="account_password" autocomplete="off" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Start Setup</button>
                </form>
            </div>
        </div>
    </div>
    <div class="position-fixed" style="bottom: 10px; right: 10px;">
    <div class="float-right mr-3" style="clear:both;">
        <a href="#" class="text-dark" onclick="goBack()" style="display: flex; align-items: center; text-decoration: none;">
            <img src="./assets/img/ok.jpg" style="width: 20px; height: 20px; margin-right: 5px;"/>
            <span style="font-size: 12px;">Turn back</span>
        </a>
    </div>
</div>
<script>
  function goBack() {
    window.history.back();
  }
</script>
<script src="./assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
