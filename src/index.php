<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= file_exists('favicon.txt') ? file_get_contents('favicon.txt') : "Fatal error, please reinstall." ?>">
    <title>Homepage | <?= file_exists('site.txt') ? file_get_contents('site.txt') : "Fatal error, please reinstall." ?></title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        .centered-text {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 36px;
        }
        .bold-text {
            font-weight: bold;
        }
        .corner-link {
            position: absolute;
            top: 3px;
            right: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="centered-text">
        <p class="bold-text">Welcome!</p>
        <a class="corner-link" href="start" style="text-decoration: none;">Are you the founder?</a>
    </div>
<script src="./assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
