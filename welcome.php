<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h2>Ласкаво просимо, <?= e($_SESSION['username']) ?>!</h2>
    <p>Ви успішно авторизувалися. Це захищена сторінка.</p>
    <p>ID користувача: <?= (int)$_SESSION['user_id'] ?></p>
    <a class="btn" href="logout.php">Вийти</a>
</div>
</body>
</html>
