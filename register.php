<?php
require 'config.php';

$errors = [];
$success = false;
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || mb_strlen($username) > 50) {
        $errors[] = 'Ім\'я користувача обов\'язкове (до 50 символів).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
        $errors[] = 'Введіть коректну електронну пошту.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Пароль має містити щонайменше 6 символів.';
    }

    if (!$errors) {
        $hash = make_hash($password);
        try {
            $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $email, $hash);
            $stmt->execute();
            $stmt->close();
            $success = true;
        } catch (mysqli_sql_exception $ex) {
            if ($ex->getCode() === 1062) {
                $errors[] = 'Користувач з таким іменем або поштою вже існує.';
            } else {
                $errors[] = 'Помилка під час реєстрації.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h2>Реєстрація</h2>
    <?php foreach ($errors as $err): ?>
        <p class="error"><?= e($err) ?></p>
    <?php endforeach; ?>
    <?php if ($success): ?>
        <p class="ok">Реєстрація успішна! <a href="login.php">Увійти</a></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <label>Ім'я користувача</label>
        <input type="text" name="username" value="<?= e($username) ?>" required maxlength="50">
        <label>Електронна пошта</label>
        <input type="email" name="email" value="<?= e($email) ?>" required maxlength="100">
        <label>Пароль</label>
        <input type="password" name="password" required minlength="6">
        <button type="submit">Зареєструватися</button>
    </form>
    <p>Вже є акаунт? <a href="login.php">Вхід</a></p>
</div>
</body>
</html>
