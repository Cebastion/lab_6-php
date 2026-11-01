<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'users_db';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Помилка підключення до бази даних.');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function make_hash(string $password): string {
    return password_hash(md5($password), PASSWORD_DEFAULT);
}

function check_password(string $password, string $hash): bool {
    return password_verify(md5($password), $hash);
}
