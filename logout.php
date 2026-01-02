<?php
session_start(); // Запускаємо сесію

// Видаляємо всі змінні сесії
$_SESSION = [];

// Руйнуємо сесію
session_destroy();

// Перенаправляємо на головну сторінку
header("Location: index.php");
exit;
?>