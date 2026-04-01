<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'developer'])) {
    die("Доступ заборонено.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['api_game_id'])) {
    $appid = (int)$_POST['api_game_id'];

    if ($_SESSION['user_role'] === 'admin') {
        $publisher_id = null; 
        $is_approved = 1;     
        $redirect_url = 'admin_panel.php';
        $success_msg = "Гру успішно імпортовано в каталог!";
    } else {
        $publisher_id = $_SESSION['user_id']; 
        $is_approved = 0;                     
        $redirect_url = 'developer_panel.php';
        $success_msg = "Вашу гру зі Steam успішно відправлено на модерацію! Вона з'явиться в каталозі після перевірки.";
    }

    $url = "https://store.steampowered.com/api/appdetails?appids={$appid}&l=ukrainian";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);

        if (isset($data[$appid]['success']) && $data[$appid]['success']) {
            $gameData = $data[$appid]['data'];

            $title = $gameData['name'] ?? 'Без назви';

            $stmt_check = $pdo->prepare("SELECT id FROM games WHERE title = ?");
            $stmt_check->execute([$title]);
            if ($stmt_check->fetch()) {
                echo "<script>alert('Гра «{$title}» вже є в базі!'); window.location.href='{$redirect_url}';</script>";
                exit;
            }

            $description = strip_tags($gameData['short_description'] ?? 'Опис відсутній');
            $cover_url = $gameData['header_image'] ?? 'img/default_cover.jpg';

            $tags_arr = [];
            if (!empty($gameData['genres'])) {
                foreach ($gameData['genres'] as $genre) {
                    $tags_arr[] = $genre['description'];
                }
            }
            $tags = implode(', ', $tags_arr);

            $features_arr = [];
            if (!empty($gameData['categories'])) {
                foreach ($gameData['categories'] as $category) {
                    $features_arr[] = $category['description'];
                }
            }
            $features = implode(', ', $features_arr);

            $languages = '';
            if (!empty($gameData['supported_languages'])) {
                $langs_parts = explode('<br>', $gameData['supported_languages']);
                $langs_clean = strip_tags($langs_parts[0]); 
                $languages = trim(str_replace('*', '', $langs_clean)); 
            }

            $sys_min = '';
            $sys_rec = '';
            if (isset($gameData['pc_requirements']['minimum'])) {
                $sys_min = strip_tags(str_replace('<br>', "\n", $gameData['pc_requirements']['minimum']));
                $sys_min = preg_replace('/^Мінімальні:\s*/i', '', $sys_min);
            }
            if (isset($gameData['pc_requirements']['recommended'])) {
                $sys_rec = strip_tags(str_replace('<br>', "\n", $gameData['pc_requirements']['recommended']));
                $sys_rec = preg_replace('/^Рекомендовані:\s*/i', '', $sys_rec);
            }

            $developer = !empty($gameData['developers']) ? implode(', ', $gameData['developers']) : 'Невідомо';
            $publisher = !empty($gameData['publishers']) ? implode(', ', $gameData['publishers']) : 'Невідомо';

            $release_date = date('Y-m-d'); 
            if (!empty($gameData['release_date']['date'])) {
                $parsed_date = strtotime($gameData['release_date']['date']);
                if ($parsed_date) {
                    $release_date = date('Y-m-d', $parsed_date);
                }
            }

            $screenshots = ['', '', '', '', ''];
            if (!empty($gameData['screenshots'])) {
                for ($i = 0; $i < min(5, count($gameData['screenshots'])); $i++) {
                    $screenshots[$i] = $gameData['screenshots'][$i]['path_thumbnail'];
                }
            }

            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    INSERT INTO games (title, description, cover_url, release_date, tags, publisher_id, features, languages, developer, publisher, sys_min, sys_rec, screenshot1, screenshot2, screenshot3, screenshot4, screenshot5, is_approved) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $title, $description, $cover_url, $release_date, $tags, $publisher_id, 
                    $features, $languages, $developer, $publisher, $sys_min, $sys_rec,
                    $screenshots[0], $screenshots[1], $screenshots[2], $screenshots[3], $screenshots[4],
                    $is_approved
                ]);

                $new_game_id = $pdo->lastInsertId();

                if (!empty($gameData['achievements']['highlighted'])) {
                    $stmt_ach = $pdo->prepare("INSERT INTO achievements (game_id, title, description, icon_url) VALUES (?, ?, ?, ?)");
                    foreach ($gameData['achievements']['highlighted'] as $ach) {
                        $ach_title = $ach['name'];
                        $ach_desc = 'Офіційне досягнення Steam'; 
                        $ach_icon = $ach['path'];
                        $stmt_ach->execute([$new_game_id, $ach_title, $ach_desc, $ach_icon]);
                    }
                }

                $pdo->commit();

                echo "<script>alert('{$success_msg}'); window.location.href='{$redirect_url}';</script>";
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                die("Помилка БД: " . $e->getMessage());
            }
        } else {
            die("Гра не знайдена в Steam API.");
        }
    } else {
        die("Помилка отримання даних від Steam API.");
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>