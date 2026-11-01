<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: welcome.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && check_password($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: welcome.php');
        exit;
    }
    $error = 'Невірне ім\'я користувача або пароль.';
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Авторизація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h2>Вхід</h2>
    <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="POST" action="login.php">
        <label>Ім'я користувача</label>
        <input type="text" name="username" value="<?= e($username) ?>" required>
        <label>Пароль</label>
        <input type="password" name="password" required>
        <button type="submit">Увійти</button>
    </form>
    <p>Немає акаунта? <a href="register.php">Реєстрація</a></p>
</div>
</body>
</html>
