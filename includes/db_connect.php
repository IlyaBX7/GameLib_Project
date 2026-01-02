<?php
$host = 'localhost';      // Або 127.0.0.1
$db   = 'gamelib_db';  // Назва твоєї бази даних
$user = 'root';           // Ваш логін до БД (зазвичай 'root')
$pass = '';              // Ваш пароль до БД (зазвичай порожній)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази даних: " . $e->getMessage());
}
?>