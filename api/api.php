<?php
session_start();
require_once '../includes/db_connect.php';

if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    if ($_GET['action'] == 'live_search' && isset($_GET['q'])) {
        $q = trim($_GET['q']);
        if (mb_strlen($q) < 2) { echo json_encode([]); exit; }

        $stmt = $pdo->prepare("SELECT id, title, cover_url FROM games WHERE title LIKE ? AND is_approved = 1 LIMIT 5");
        $stmt->execute(["%" . $q . "%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($_GET['action'] == 'get_notifications' && isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY id DESC LIMIT 10");
        $stmt->execute([$_SESSION['user_id']]);
        $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $unread = count($notifs);

        echo json_encode(['unread' => $unread, 'items' => $notifs]);
        exit;
    }

    if ($_GET['action'] == 'mark_read' && isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>