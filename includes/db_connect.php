<?php
$host = 'database';
$database = 'game_library';

define('BASE_URL', '/'); 

if (!function_exists('resolve_url')) {
    function resolve_url($url) {
        if (empty($url)) return '';
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            return $url;
        }
        return BASE_URL . ltrim($url, '/');
    }
}
$user = 'root';
$pass = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази даних: " . $e->getMessage());
}
?>