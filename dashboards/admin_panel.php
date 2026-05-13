<?php
session_start();

$feature_icons = [
    "Однокористувацька гра" => "fas fa-user",
    "Багатокористувацька гра" => "fas fa-users",
    "Гравець проти гравця" => "fas fa-crosshairs",
    "Гравець проти гравця в мережі" => "fas fa-globe",
    "Гравець проти гравця в локальній мережі" => "fas fa-ethernet",
    "Кооперативна гра" => "fas fa-hands-helping",
    "Мережева кооперативна гра" => "fas fa-user-friends",
    "Локальна кооперативна гра" => "fas fa-users-cog",
    "Спільний/розділений екран" => "fas fa-columns",
    "Міжплатформна багатокористувацька гра" => "fas fa-random",
    "Додаткове високоякісне аудіо" => "fas fa-headphones-alt",
    "Підтримка відстежуваних контролерів" => "fas fa-vr-cardboard",
    "З субтитрами" => "fas fa-closed-captioning",
    "Голосовий чат" => "fas fa-microphone",
    "Регульована складність" => "fas fa-sliders-h",
    "Збереження будь-коли" => "fas fa-save",
    "Об’ємний звук" => "fas fa-broadcast-tower",
    "З підтримкою HDR" => "fas fa-tv",
    "Повна підтримка контролерів" => "fas fa-gamepad",
    "Підтримка контролерів Xbox" => "fab fa-xbox",
    "Підтримка контролерів DualSense" => "fab fa-playstation",
    "Стереозвук" => "fas fa-headphones",
    "У власному темпі" => "fas fa-walking"
];
$language_icons = [
    "Українська" => "🇺🇦",
    "Англійська" => "🇬🇧",
    "Французька" => "🇫🇷",
    "Німецька" => "🇩🇪",
    "Іспанська" => "🇪🇸"
];
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("<h1>Доступ заборонено</h1><p>Ви не маєте прав адміністратора.</p>");
}
$admin_user_id = $_SESSION['user_id'];
$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_game'])) {
    $game_id = (int)$_POST['game_id'];
    try {
        $pdo->prepare("DELETE FROM user_library WHERE game_id = ?")->execute([$game_id]);
        $pdo->prepare("DELETE FROM achievements WHERE game_id = ?")->execute([$game_id]);
        $pdo->prepare("DELETE FROM game_reviews WHERE game_id = ?")->execute([$game_id]);
        $pdo->prepare("DELETE FROM games WHERE id = ?")->execute([$game_id]);

        $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-trash me-2"></i> Гру та всі пов\'язані дані успішно видалено назавжди!</div>';
    } catch (PDOException $e) { 
        $message = '<div class="alert alert-danger shadow-sm">Помилка видалення: ' . $e->getMessage() . '</div>'; 
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['moderate_game'])) {
    $game_id = (int)$_POST['game_id']; $action = $_POST['action'];
    if ($action === 'approve') {
        try {
            $stmt = $pdo->prepare("UPDATE games SET is_approved = 1 WHERE id = ?");
            $stmt->execute([$game_id]);
            $message = '<div class="alert alert-success shadow-sm">Гру успішно схвалено та опубліковано!</div>';
        } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка: ' . $e->getMessage() . '</div>'; }
    } elseif ($action === 'reject') {
        try {
            $pdo->prepare("DELETE FROM achievements WHERE game_id = ?")->execute([$game_id]);
            $pdo->prepare("DELETE FROM games WHERE id = ?")->execute([$game_id]);
            $message = '<div class="alert alert-warning shadow-sm">Гру відхилено та видалено.</div>';
        } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка: ' . $e->getMessage() . '</div>'; }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_game_full'])) {
    $edit_game_id = (int)($_POST['edit_game_id'] ?? 0);
    $title = trim($_POST['edit_title'] ?? ''); 
    $description = trim($_POST['edit_description'] ?? '');
    $release_date = !empty(trim($_POST['edit_release_date'] ?? '')) ? trim($_POST['edit_release_date']) : '2000-01-01'; 
    $tags_str = isset($_POST['edit_tags']) ? implode(', ', $_POST['edit_tags']) : '';
    $cover_url = trim($_POST['edit_cover_url'] ?? ''); 
    $screenshot1 = trim($_POST['edit_screenshot1'] ?? '');
    $screenshot2 = trim($_POST['edit_screenshot2'] ?? ''); 
    $screenshot3 = trim($_POST['edit_screenshot3'] ?? '');
    $screenshot4 = trim($_POST['edit_screenshot4'] ?? ''); 
    $developer = trim($_POST['edit_developer'] ?? 'Невідомо');
    $publisher = trim($_POST['edit_publisher'] ?? 'Невідомо');

    $features_str = isset($_POST['edit_features']) ? implode(',', $_POST['edit_features']) : '';
    $languages_str = isset($_POST['edit_languages']) ? implode(',', $_POST['edit_languages']) : '';
    $platforms_str = isset($_POST['edit_platforms']) ? implode(',', $_POST['edit_platforms']) : '';
    $sys_min = trim($_POST['edit_sys_min'] ?? ''); 
    $sys_rec = trim($_POST['edit_sys_rec'] ?? '');

    if (!empty($title) && !empty($cover_url) && $edit_game_id > 0) {
        try {
            $sql = "UPDATE games SET title=?, description=?, cover_url=?, tags=?, features=?, languages=?, platforms=?, developer=?, publisher=?, release_date=?, sys_min=?, sys_rec=?, screenshot1=?, screenshot2=?, screenshot3=?, screenshot4=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$title, $description, $cover_url, $tags_str, $features_str, $languages_str, $platforms_str, $developer, $publisher, $release_date, $sys_min, $sys_rec, $screenshot1, $screenshot2, $screenshot3, $screenshot4, $edit_game_id])) {
                $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> Дані гри "'.htmlspecialchars($title).'" успішно оновлено!</div>';
            }
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Помилка оновлення: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning shadow-sm"><i class="fas fa-exclamation-triangle me-2"></i> Будь ласка, заповніть обов\'язкові поля (Назва та Обкладинка).</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_news_article'])) {
    $news_title = trim($_POST['news_title'] ?? '');
    $news_content = trim($_POST['news_content'] ?? '');
    $author_id = $admin_user_id;

    $news_image_url = 'img/default_cover.jpg';
    if (isset($_FILES['news_image']) && $_FILES['news_image']['error'] == 0) {
        $target_dir = "img/news/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $ext = pathinfo($_FILES['news_image']['name'], PATHINFO_EXTENSION);
        $target_file = $target_dir . "news_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['news_image']['tmp_name'], $target_file)) {
            $news_image_url = $target_file;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO news_articles (title, content, image_url, author_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$news_title, $news_content, $news_image_url, $author_id]);
        $message = '<div class="alert alert-success shadow-sm">Новину "'.htmlspecialchars($news_title).'" успішно опубліковано!</div>';
    } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка БД: '. $e->getMessage() .'</div>'; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_news'])) {
    $news_id = (int)$_POST['news_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM news_articles WHERE id = ?");
        $stmt->execute([$news_id]);
        $message = '<div class="alert alert-success shadow-sm">Новину успішно видалено.</div>';
    } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка: '. $e->getMessage() .'</div>'; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_achievement'])) {
    $ach_game_id = (int)($_POST['ach_game_id'] ?? 0);
    $stmt_title = $pdo->prepare("SELECT title FROM games WHERE id = ?");
    $stmt_title->execute([$ach_game_id]);
    $game_title = $stmt_title->fetchColumn();

    if ($game_title) {
        $safe_folder_name = preg_replace('/[^A-Za-z0-9\- ]/', '', $game_title);
        $safe_folder_name = trim($safe_folder_name);
        $target_dir = "img/achievements/" . $safe_folder_name . "/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $titles = $_POST['ach_title'] ?? []; 
        $descs = $_POST['ach_desc'] ?? [];
        $success_count = 0;

        for ($i = 0; $i < count($titles); $i++) {
            $title = trim($titles[$i]);
            $desc = trim($descs[$i]);
            if (empty($title)) continue;

            $ach_icon_path = '';
            if (isset($_FILES['ach_icon']['name'][$i]) && $_FILES['ach_icon']['error'][$i] == 0) {
                $ext = pathinfo($_FILES['ach_icon']['name'][$i], PATHINFO_EXTENSION);
                $safe_ach_name = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
                $target_file = $target_dir . $safe_ach_name . "_" . time() . "_" . $i . "." . $ext;
                if (move_uploaded_file($_FILES['ach_icon']['tmp_name'][$i], $target_file)) {
                    $ach_icon_path = $target_file;
                }
            }
            if ($ach_icon_path) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO achievements (game_id, title, description, icon_url) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$ach_game_id, $title, $desc, $ach_icon_path]);
                    $success_count++;
                } catch (PDOException $e) { }
            }
        }
        if ($success_count > 0) $message = '<div class="alert alert-success shadow-sm">Успішно додано досягнень: ' . $success_count . '</div>';
        else $message = '<div class="alert alert-warning shadow-sm">Не вдалося додати досягнення.</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_game'])) {
    $title = trim($_POST['title'] ?? ''); $description = trim($_POST['description'] ?? ''); $release_date = !empty(trim($_POST['release_date'] ?? '')) ? trim($_POST['release_date']) : '2000-01-01';
    $tags_str = isset($_POST['tags']) ? implode(', ', $_POST['tags']) : '';
    $cover_url = trim($_POST['cover_url'] ?? '');
    $screenshot1 = trim($_POST['screenshot1'] ?? '');
    $screenshot2 = trim($_POST['screenshot2'] ?? '');
    $screenshot3 = trim($_POST['screenshot3'] ?? '');
    $screenshot4 = trim($_POST['screenshot4'] ?? '');
    $screenshot5 = ''; 

    $developer = trim($_POST['developer'] ?? 'Невідомо');
    $publisher = trim($_POST['publisher'] ?? 'Невідомо');

    $features_str = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $languages_str = isset($_POST['languages']) ? implode(',', $_POST['languages']) : '';
    $platforms_str = isset($_POST['platforms']) ? implode(',', $_POST['platforms']) : '';
    $sys_min = trim($_POST['sys_min'] ?? '');
    $sys_rec = trim($_POST['sys_rec'] ?? '');

    $publisher_id = $admin_user_id;

    if (!empty($title) && !empty($cover_url)) {
        try {
            $sql = "INSERT INTO games (title, description, cover_url, tags, features, languages, platforms, developer, publisher, publisher_id, release_date, sys_min, sys_rec, screenshot1, screenshot2, screenshot3, screenshot4, screenshot5, is_approved) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$title, $description, $cover_url, $tags_str, $features_str, $languages_str, $platforms_str, $developer, $publisher, $publisher_id, $release_date, $sys_min, $sys_rec, $screenshot1, $screenshot2, $screenshot3, $screenshot4, $screenshot5])) {

                $new_game_id = $pdo->lastInsertId();
                $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> Гру "'.htmlspecialchars($title).'" успішно додано до каталогу!</div>';

                if (isset($_POST['rawg_ach_title']) && is_array($_POST['rawg_ach_title'])) {
                    $ach_count = 0;
                    $stmt_ach = $pdo->prepare("INSERT INTO achievements (game_id, title, description, icon_url) VALUES (?, ?, ?, ?)");
                    for ($i = 0; $i < count($_POST['rawg_ach_title']); $i++) {
                        $ach_title = trim($_POST['rawg_ach_title'][$i] ?? '');
                        $ach_desc = trim($_POST['rawg_ach_desc'][$i] ?? '');
                        $ach_image = trim($_POST['rawg_ach_image'][$i] ?? '');
                        if (!empty($ach_title)) {
                            $stmt_ach->execute([$new_game_id, $ach_title, $ach_desc, $ach_image]);
                            $ach_count++;
                        }
                    }
                    if ($ach_count > 0) $message .= '<div class="alert alert-info shadow-sm mt-2 fw-bold"><i class="fas fa-trophy me-2"></i> Автоматично збережено ' . $ach_count . ' досягнень.</div>';
                }
            }
        } catch (PDOException $e) { $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Помилка: ' . $e->getMessage() . '</div>'; }
    } else { $message = '<div class="alert alert-warning shadow-sm"><i class="fas fa-exclamation-triangle me-2"></i> Заповніть обов\'язкові поля.</div>'; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_hero_slider'])) {
    $hero_game_ids = $_POST['hero_games'] ?? [];
    try {
        $pdo->query("UPDATE games SET is_in_hero_slider = 0");
        if (!empty($hero_game_ids)) {
            $placeholders = implode(',', array_fill(0, count($hero_game_ids), '?'));
            $stmt_set = $pdo->prepare("UPDATE games SET is_in_hero_slider = 1 WHERE id IN ($placeholders)");
            $stmt_set->execute($hero_game_ids);
        }
        $message = '<div class="alert alert-success shadow-sm">Слайдер оновлено!</div>';
    } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка: '. $e->getMessage() .'</div>'; }
}

$stmt_all_games = $pdo->query("SELECT id, title, cover_url, release_date, developer, is_in_hero_slider FROM games WHERE is_approved = 1 ORDER BY id DESC");
$all_games = $stmt_all_games->fetchAll(PDO::FETCH_ASSOC);

$stmt_all_games_full = $pdo->query("SELECT * FROM games ORDER BY id DESC");
$all_games_full = $stmt_all_games_full->fetchAll(PDO::FETCH_ASSOC);

$stmt_unapproved = $pdo->query("SELECT g.*, u.username AS uploader_name FROM games g LEFT JOIN users u ON g.publisher_id = u.id WHERE g.is_approved = 0 ORDER BY g.id DESC");
$unapproved_games = $stmt_unapproved->fetchAll(PDO::FETCH_ASSOC);
$unapproved_count = count($unapproved_games);

$stmt_all_news = $pdo->query("SELECT * FROM news_articles ORDER BY created_at DESC");
$all_news = $stmt_all_news->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Дашборд Адміністратора';
$base_path = '../';
$page_css = $base_path . 'css/admin_panel.css';
$page_js = $base_path . 'js/admin_panel.js';
require_once '../includes/header.php';
?>

<div class="container content-section mt-4">
    <h2 class="mb-4 text-white"><i class="fas fa-cogs text-accent me-2"></i> Дашборд Адміністратора</h2>
    <?php echo $message; ?>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="bg-dark p-3 rounded border border-secondary shadow-sm admin-sidebar sticky-top" style="top: 100px;">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start d-flex justify-content-between align-items-center" data-bs-toggle="pill" data-bs-target="#v-pills-moderation">
                        <span><i class="fas fa-shield-alt me-2"></i> Модерація</span>
                        <?php if($unapproved_count > 0) echo "<span class='badge bg-danger rounded-pill'>$unapproved_count</span>"; ?>
                    </button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-games"><i class="fas fa-list me-2"></i> Каталог ігор</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-news"><i class="fas fa-newspaper me-2"></i> Новини</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-add-game"><i class="fas fa-plus-circle me-2"></i> Додати гру (RAWG)</button>
                    <button class="nav-link text-start text-warning" data-bs-toggle="pill" data-bs-target="#v-pills-edit-game"><i class="fas fa-edit me-2"></i> Редагувати гру</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-hero"><i class="fas fa-images me-2"></i> Hero-слайдер</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-achievements"><i class="fas fa-trophy me-2"></i> Досягнення</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-steam"><i class="fab fa-steam me-2"></i> Імпорт (Steam)</button>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content bg-dark border border-secondary rounded p-4 shadow-sm" id="v-pills-tabContent" style="min-height: 600px;">

                <div class="tab-pane fade show active" id="v-pills-moderation">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-shield-alt text-accent me-2"></i> Заявки на публікацію</h4>
                    <?php if (empty($unapproved_games)): ?>
                        <div class="text-center py-5"><i class="fas fa-check-circle text-success mb-3" style="font-size: 4rem;"></i><h5 class="text-white-50">Усі ігри перевірено. Немає нових заявок.</h5></div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($unapproved_games as $mod_game): ?>
                                <div class="col-md-6">
                                    <div class="card bg-dark-green border-secondary h-100 shadow-sm">
                                        <div class="row g-0 h-100">
                                            <div class="col-4"><img src="<?php echo htmlspecialchars(resolve_url($mod_game['cover_url'])); ?>" class="img-fluid rounded-start h-100 w-100" style="object-fit: cover;"></div>
                                            <div class="col-8">
                                                <div class="card-body d-flex flex-column p-3 h-100">
                                                    <h5 class="card-title text-white fs-6 mb-2"><?php echo htmlspecialchars($mod_game['title']); ?></h5>
                                                    <p class="text-white-50 small mb-1"><strong>Завантажив:</strong> <?php echo htmlspecialchars($mod_game['uploader_name'] ?? 'Невідомо'); ?></p>
                                                    <p class="text-white-50 small mb-3"><strong>Розробник:</strong> <?php echo htmlspecialchars($mod_game['developer']); ?></p>
                                                    <div class="mt-auto d-flex gap-2">
                                                        <form method="POST" action="admin_panel.php" class="flex-grow-1 m-0"><input type="hidden" name="moderate_game" value="1"><input type="hidden" name="game_id" value="<?php echo $mod_game['id']; ?>"><input type="hidden" name="action" value="approve"><button type="submit" class="btn btn-success btn-sm w-100 fw-bold"><i class="fas fa-check"></i></button></form>
                                                        <form method="POST" action="admin_panel.php" class="flex-grow-1 m-0"><input type="hidden" name="moderate_game" value="1"><input type="hidden" name="game_id" value="<?php echo $mod_game['id']; ?>"><input type="hidden" name="action" value="reject"><button type="submit" class="btn btn-danger btn-sm w-100 fw-bold" onclick="return confirm('Ви впевнені?');"><i class="fas fa-times"></i></button></form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="v-pills-games">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-list text-accent me-2"></i> Управління іграми</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle border-secondary">
                            <thead><tr><th scope="col" style="width: 70px;">Обкладинка</th><th scope="col">Назва</th><th scope="col">Розробник</th><th scope="col">Дата виходу</th><th scope="col" class="text-end">Дії</th></tr></thead>
                            <tbody>
                                <?php foreach ($all_games as $game): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover;"></td>
                                        <td class="fw-bold"><a href="../games/game_details.php?id=<?php echo $game['id']; ?>" class="text-white text-decoration-none" target="_blank"><?php echo htmlspecialchars($game['title']); ?></a></td>
                                        <td class="text-white-50 small"><?php echo htmlspecialchars($game['developer'] ?? '-'); ?></td>
                                        <td class="text-white-50 small"><?php echo htmlspecialchars($game['release_date']); ?></td>
                                        <td class="text-end">
                                            <form action="admin_panel.php" method="POST" class="m-0" onsubmit="return confirm('Ви абсолютно впевнені? Ця гра буде видалена з бази назавжди!');">
                                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>"><button type="submit" name="delete_game" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-news">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-newspaper text-accent me-2"></i> Опублікувати новину</h4>
                    <form action="admin_panel.php" method="POST" enctype="multipart/form-data" class="bg-dark-green p-4 rounded border border-secondary mb-5">
                        <input type="hidden" name="add_news_article" value="1">
                        <div class="mb-3"><label class="form-label text-white">Заголовок новини</label><input type="text" name="news_title" class="form-control bg-dark border-secondary text-white" required></div>
                        <div class="mb-3"><label class="form-label text-white">Текст новини</label><textarea name="news_content" class="form-control bg-dark border-secondary text-white" rows="5" required></textarea></div>
                        <div class="mb-3"><label class="form-label text-white">Обкладинка новини</label><input type="file" name="news_image" class="form-control bg-dark border-secondary text-white" accept="image/*" required></div>
                        <button type="submit" class="btn btn-success w-100 fw-bold"><i class="fas fa-paper-plane me-2"></i> Опублікувати</button>
                    </form>

                    <h5 class="text-white mb-3">Історія новин</h5>
                    <?php if (empty($all_news)): ?><p class="text-white-50">Немає жодної новини.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($all_news as $news): ?>
                                <div class="list-group-item bg-dark-green border-secondary d-flex justify-content-between align-items-center mb-2 rounded p-3">
                                    <div class="d-flex align-items-center"><img src="<?php echo htmlspecialchars(resolve_url($news['image_url'])); ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 15px;"><div><h6 class="mb-1 text-white"><?php echo htmlspecialchars($news['title']); ?></h6><small class="text-white-50"><?php echo date('d.m.Y H:i', strtotime($news['created_at'])); ?></small></div></div>
                                    <form method="POST" action="admin_panel.php" class="m-0" onsubmit="return confirm('Ви впевнені?');"><input type="hidden" name="delete_news" value="1"><input type="hidden" name="news_id" value="<?php echo $news['id']; ?>"><button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="v-pills-add-game">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="bg-dark p-4 rounded border border-secondary shadow-sm">
                                <h4 class="mb-3"><i class="text-accent fas fa-magic me-2"></i> Автозаповнення RAWG</h4>
                                <div class="input-group mb-3 shadow-sm"><input type="text" id="rawg-search-input" class="form-control bg-dark-green text-white border-secondary" placeholder="Наприклад: Witcher 3..."><button class="btn btn-success fw-bold" type="button" id="rawg-search-btn"><i class="fas fa-search"></i></button></div>
                                <div id="rawg-loader" class="text-center d-none my-3"><div class="spinner-border text-accent" role="status"></div></div>
                                <div id="rawg-results" class="list-group"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <form action="admin_panel.php" method="POST" id="game-form" class="bg-dark-green p-4 rounded border border-secondary shadow-sm">
                                <h4 class="mb-4"><i class="text-accent fas fa-plus-circle"></i> Додавання гри</h4>
                                <div class="mb-3"><label class="text-white fw-bold">Назва гри *</label><input type="text" name="title" id="form-title" class="form-control" required></div>
                                <div class="mb-3"><label class="text-white fw-bold">Опис гри</label><textarea name="description" id="form-desc" class="form-control" rows="6"></textarea></div>

                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-accent fw-bold">Особливості гри:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                            <?php
                                            $f_idx = 0;
                                            foreach($feature_icons as $feat => $icon): ?>
                                                <div class="col-md-3 col-sm-4 col-6 form-check">
                                                    <input class="form-check-input feat-cb" type="checkbox" name="features[]" value="<?php echo htmlspecialchars($feat); ?>" id="af<?php echo $f_idx; ?>">
                                                    <label class="form-check-label text-white-50" for="af<?php echo $f_idx; ?>"><i class="<?php echo $icon; ?> me-1"></i> <?php echo htmlspecialchars($feat); ?></label>
                                                </div>
                                            <?php $f_idx++; endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-accent fw-bold">Підтримувані мови:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                                <?php
                                                $l_idx = 1;
                                                foreach($language_icons as $lang => $icon): ?>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check">
                                                        <input class="form-check-input lang-cb" type="checkbox" name="languages[]" value="<?php echo $lang; ?>" id="al<?php echo $l_idx; ?>">
                                                        <label class="form-check-label text-white-50" for="al<?php echo $l_idx; ?>"><span class="me-1"><?php echo $icon; ?></span> <?php echo $lang; ?></label>
                                                    </div>
                                                <?php $l_idx++; endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-accent fw-bold">Платформи:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="PC (Windows)" id="pl1"><label class="form-check-label text-white-50" for="pl1"><i class="fab fa-windows"></i> PC</label></div>
                                                <div class="form-check col-md-3 col-sm-4 col-6"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="PlayStation" id="pl2"><label class="form-check-label text-white-50" for="pl2"><i class="fab fa-playstation"></i> PlayStation</label></div>
                                                <div class="form-check col-md-3 col-sm-4 col-6"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Xbox" id="pl3"><label class="form-check-label text-white-50" for="pl3"><i class="fab fa-xbox"></i> Xbox</label></div>
                                                <div class="form-check col-md-3 col-sm-4 col-6"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Nintendo Switch" id="pl4"><label class="form-check-label text-white-50" for="pl4"><i class="fas fa-gamepad"></i> Nintendo</label></div>
                                                <div class="form-check col-md-3 col-sm-4 col-6"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Mac" id="pl5"><label class="form-check-label text-white-50" for="pl5"><i class="fab fa-apple"></i> Mac</label></div>
                                                <div class="form-check col-md-3 col-sm-4 col-6"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Linux" id="pl6"><label class="form-check-label text-white-50" for="pl6"><i class="fab fa-linux"></i> Linux</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="text-white fw-bold">Жанри/Теги</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                            <?php
                                            $add_tags = ["Екшен", "Рольові ігри", "РПГ", "Шутер", "Стратегія", "Пригоди", "Гонки", "Симулятор", "Спорт", "Головоломка", "Хоррор", "Жахи", "Платформер", "Файтинг", "Виживання", "Відкритий світ", "Пісочниця", "Інді"];
                                            foreach($add_tags as $index => $tag): ?>
                                                <div class="col-md-3 col-sm-4 col-6 form-check">
                                                    <input class="form-check-input tag-cb" type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>" id="atag<?php echo $index; ?>">
                                                    <label class="form-check-label text-white-50" for="atag<?php echo $index; ?>"><?php echo htmlspecialchars($tag); ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"><label class="text-white fw-bold">Розробник</label><input type="text" name="developer" id="form-developer" class="form-control"></div>
                                    <div class="col-md-6"><label class="text-white fw-bold">Видавець</label><input type="text" name="publisher" id="form-publisher" class="form-control"></div>
                                </div>
                                <div class="mb-4"><label class="text-white fw-bold">Дата виходу</label><input type="date" name="release_date" id="form-release" class="form-control"></div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3"><label class="text-white">Мін. вимоги</label><textarea name="sys_min" id="form-sys-min" class="form-control" rows="4"></textarea></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Рек. вимоги</label><textarea name="sys_rec" id="form-sys-rec" class="form-control" rows="4"></textarea></div>
                                </div>
                                <div class="mb-3"><label class="text-white fw-bold">Головна обкладинка (URL) *</label><input type="url" name="cover_url" id="form-cover" class="form-control" required></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 1</label><input type="url" name="screenshot1" id="form-screen1" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 2</label><input type="url" name="screenshot2" id="form-screen2" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 3</label><input type="url" name="screenshot3" id="form-screen3" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 4</label><input type="url" name="screenshot4" id="form-screen4" class="form-control"></div>
                                </div>
                                <div id="rawg-achievements-container"></div>
                                <button type="submit" name="add_game" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">Опублікувати гру</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-edit-game">
                    <div class="bg-dark p-4 rounded border border-secondary shadow-sm">
                        <h4 class="text-warning mb-4"><i class="fas fa-edit me-2"></i> Редагування будь-якої гри</h4>
                        <?php if (empty($all_games_full)): ?><div class="alert alert-warning">У базі ще немає ігор.</div><?php else: ?>
                            <div class="mb-4 border-bottom border-secondary pb-4">
                                <label class="form-label text-white fw-bold fs-5">Крок 1. Знайдіть гру для редагування:</label>
                                <input type="text" id="admin-edit-search" class="form-control bg-dark-green text-white border-secondary mb-2" placeholder="Почніть вводити назву гри...">
                                <div id="admin-edit-search-results" class="list-group" style="max-height: 250px; overflow-y: auto;"></div>
                            </div>
                            <div id="admin-edit-form-container" class="d-none">
                                <form action="admin_panel.php" method="POST">
                                    <input type="hidden" name="edit_game_full" value="1"><input type="hidden" name="edit_game_id" id="edit-game-id">
                                    <h5 class="text-warning mb-3">Крок 2. Змініть дані гри:</h5>
                                    <div class="mb-3"><label class="text-white fw-bold">Назва гри *</label><input type="text" name="edit_title" id="edit-form-title" class="form-control bg-dark-green text-white border-secondary" required></div>
                                    <div class="mb-3"><label class="text-white fw-bold">Опис гри</label><textarea name="edit_description" id="edit-form-desc" class="form-control bg-dark-green text-white border-secondary" rows="6"></textarea></div>

                                    <div class="row mb-3">
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-warning fw-bold">Особливості гри:</label>
                                            <div class="card bg-dark-green border-secondary p-3">
                                                <div class="row">
                                                <?php
                                                $e_f_idx = 0;
                                                foreach($feature_icons as $feat => $icon): ?>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check">
                                                        <input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="<?php echo htmlspecialchars($feat); ?>" id="eaf<?php echo $e_f_idx; ?>">
                                                        <label class="form-check-label text-white-50" for="eaf<?php echo $e_f_idx; ?>"><i class="<?php echo $icon; ?> me-1"></i> <?php echo htmlspecialchars($feat); ?></label>
                                                    </div>
                                                <?php $e_f_idx++; endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-warning fw-bold">Підтримувані мови:</label>
                                            <div class="card bg-dark-green border-secondary p-3">
                                                <div class="row">
                                                    <?php
                                                    $e_l_idx = 1;
                                                    foreach($language_icons as $lang => $icon): ?>
                                                        <div class="col-md-3 col-sm-4 col-6 form-check">
                                                            <input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="<?php echo $lang; ?>" id="edit_al<?php echo $e_l_idx; ?>">
                                                            <label class="form-check-label text-white-50" for="edit_al<?php echo $e_l_idx; ?>"><span class="me-1"><?php echo $icon; ?></span> <?php echo $lang; ?></label>
                                                        </div>
                                                    <?php $e_l_idx++; endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-warning fw-bold">Платформи:</label>
                                            <div class="card bg-dark-green border-secondary p-3">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="PC (Windows)" id="epl1"><label class="form-check-label text-white-50" for="epl1"><i class="fab fa-windows"></i> PC</label></div>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="PlayStation" id="epl2"><label class="form-check-label text-white-50" for="epl2"><i class="fab fa-playstation"></i> PlayStation</label></div>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Xbox" id="epl3"><label class="form-check-label text-white-50" for="epl3"><i class="fab fa-xbox"></i> Xbox</label></div>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Nintendo Switch" id="epl4"><label class="form-check-label text-white-50" for="epl4"><i class="fas fa-gamepad"></i> Nintendo</label></div>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Mac" id="epl5"><label class="form-check-label text-white-50" for="epl5"><i class="fab fa-apple"></i> Mac</label></div>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Linux" id="epl6"><label class="form-check-label text-white-50" for="epl6"><i class="fab fa-linux"></i> Linux</label></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12 mb-3">
                                            <label class="text-white fw-bold">Жанри/Теги</label>
                                            <div class="card bg-dark-green border-secondary p-3">
                                                <div class="row">
                                                <?php
                                                foreach($add_tags as $index => $tag): ?>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check">
                                                        <input class="form-check-input edit-tag-cb" type="checkbox" name="edit_tags[]" value="<?php echo htmlspecialchars($tag); ?>" id="etag<?php echo $index; ?>">
                                                        <label class="form-check-label text-white-50" for="etag<?php echo $index; ?>"><?php echo htmlspecialchars($tag); ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"><label class="text-white fw-bold">Розробник</label><input type="text" name="edit_developer" id="edit-form-developer" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6"><label class="text-white fw-bold">Видавець</label><input type="text" name="edit_publisher" id="edit-form-publisher" class="form-control bg-dark-green text-white border-secondary"></div>
                                    </div>
                                    <div class="mb-4"><label class="text-white fw-bold">Дата виходу</label><input type="date" name="edit_release_date" id="edit-form-release" class="form-control bg-dark-green text-white border-secondary"></div>

                                    <h5 class="text-warning mb-3"><i class="fas fa-desktop me-2"></i> Системні вимоги</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3"><label class="text-white">Мін. вимоги</label><textarea name="edit_sys_min" id="edit-form-sys-min" class="form-control bg-dark-green text-white border-secondary" rows="4"></textarea></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Рек. вимоги</label><textarea name="edit_sys_rec" id="edit-form-sys-rec" class="form-control bg-dark-green text-white border-secondary" rows="4"></textarea></div>
                                    </div>

                                    <h5 class="text-warning mb-3"><i class="fas fa-image me-2"></i> Медіа-матеріали</h5>
                                    <div class="mb-3"><label class="text-white fw-bold">Обкладинка (URL) *</label><input type="url" name="edit_cover_url" id="edit-form-cover" class="form-control bg-dark-green text-white border-secondary" required></div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3"><label class="text-white">Скрін 1</label><input type="url" name="edit_screenshot1" id="edit-form-screen1" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скрін 2</label><input type="url" name="edit_screenshot2" id="edit-form-screen2" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скрін 3</label><input type="url" name="edit_screenshot3" id="edit-form-screen3" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скрін 4</label><input type="url" name="edit_screenshot4" id="edit-form-screen4" class="form-control bg-dark-green text-white border-secondary"></div>
                                    </div>
                                    <button type="submit" class="btn btn-warning fw-bold w-100 fs-5 mt-3 shadow-sm"><i class="fas fa-save me-2"></i> Зберегти всі зміни</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-hero">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-images text-accent me-2"></i> Керування Hero-слайдером</h4>
                    <div class="mb-4"><div class="input-group shadow-sm"><span class="input-group-text bg-dark-green border-secondary text-white"><i class="fas fa-search"></i></span><input type="text" id="hero-search" class="form-control bg-dark-green border-secondary text-white" placeholder="Пошук гри за назвою..."></div></div>
                    <form action="admin_panel.php" method="POST">
                        <div class="list-group list-group-flush mb-4 rounded border border-secondary" id="hero-games-list">
                            <?php foreach ($all_games as $game): ?>
                                <label class="list-group-item game-list-item-horizontal hero-game-item d-flex align-items-center p-3" data-title="<?php echo mb_strtolower(htmlspecialchars($game['title']), 'UTF-8'); ?>">
                                    <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">
                                    <div class="flex-grow-1"><h6 class="mb-0 text-white"><?php echo htmlspecialchars($game['title']); ?></h6></div>
                                    <div class="form-check form-switch fs-4 m-0"><input class="form-check-input" type="checkbox" name="hero_games[]" value="<?php echo $game['id']; ?>" <?php echo ($game['is_in_hero_slider']) ? 'checked' : ''; ?>></div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <nav aria-label="Hero slider pagination"><ul class="pagination justify-content-center flex-wrap" id="hero-pagination"></ul></nav>
                        <button type="submit" name="update_hero_slider" class="btn btn-success btn-lg w-100 mt-3 fw-bold">Зберегти слайдер</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="v-pills-achievements">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-trophy text-accent me-2"></i> Додати досягнення</h4>
                    <form action="admin_panel.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add_achievement" value="1">
                        <div class="mb-4">
                            <label class="form-label text-white">Оберіть гру:</label>
                            <select name="ach_game_id" class="form-select bg-dark border-secondary text-white" required>
                                <?php foreach ($all_games as $g): ?><option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['title']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div id="achievements-container">
                            <div class="achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark-green shadow-sm">
                                <h5 class="text-white mb-3">Досягнення #1</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control bg-dark border-secondary text-white" placeholder="Назва" required></div>
                                    <div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control bg-dark border-secondary text-white" placeholder="Опис" required></div>
                                    <div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control bg-dark border-secondary text-white" accept="image/*" required></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-light" id="add-more-btn"><i class="fas fa-plus me-2"></i> Додати ще</button>
                            <button type="submit" class="btn btn-success px-5 fw-bold">Зберегти досягнення</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="v-pills-steam">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="bg-dark p-4 rounded border border-secondary shadow-sm">
                                <h4 class="mb-3 text-white"><i class="fab fa-steam text-accent me-2"></i> Автозаповнення Steam</h4>
                                <div class="input-group mb-3 shadow-sm"><input type="text" id="steam-search-input" class="form-control bg-dark-green text-white border-secondary" placeholder="Введіть назву англійською..."><button class="btn btn-success fw-bold" type="button" id="steam-search-btn"><i class="fas fa-search"></i></button></div>
                                <div id="steam-loader" class="text-center d-none my-3"><div class="spinner-border text-white" role="status"></div></div>
                                <div id="steam-results" class="list-group"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <form action="admin_panel.php" method="POST" id="steam-game-form" class="bg-dark-green p-4 rounded border border-secondary shadow-sm">
                                <h4 class="mb-4 text-white"><i class="fab fa-steam text-success"></i> Додавання гри (через Steam)</h4>
                                <div class="mb-3"><label class="text-white fw-bold">Назва гри *</label><input type="text" name="title" id="steam-form-title" class="form-control" required></div>
                                <div class="mb-3"><label class="text-white fw-bold">Опис гри</label><textarea name="description" id="steam-form-desc" class="form-control" rows="6"></textarea></div>

                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white fw-bold">Особливості гри:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                            <?php
                                            $s_f_idx = 0;
                                            foreach($feature_icons as $feat => $icon): ?>
                                                <div class="col-md-3 col-sm-4 col-6 form-check">
                                                    <input class="form-check-input steam-feat-cb" type="checkbox" name="features[]" value="<?php echo htmlspecialchars($feat); ?>" id="saf<?php echo $s_f_idx; ?>">
                                                    <label class="form-check-label text-white-50" for="saf<?php echo $s_f_idx; ?>"><i class="<?php echo $icon; ?> me-1"></i> <?php echo htmlspecialchars($feat); ?></label>
                                                </div>
                                            <?php $s_f_idx++; endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white fw-bold">Підтримувані мови:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                                <?php
                                                $s_l_idx = 1;
                                                foreach($language_icons as $lang => $icon): ?>
                                                    <div class="col-md-3 col-sm-4 col-6 form-check">
                                                        <input class="form-check-input steam-lang-cb" type="checkbox" name="languages[]" value="<?php echo $lang; ?>" id="sal<?php echo $s_l_idx; ?>">
                                                        <label class="form-check-label text-white-50" for="sal<?php echo $s_l_idx; ?>"><span class="me-1"><?php echo $icon; ?></span> <?php echo $lang; ?></label>
                                                    </div>
                                                <?php $s_l_idx++; endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white fw-bold">Платформи:</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="PC (Windows)" id="spl1"><label class="form-check-label text-white-50" for="spl1"><i class="fab fa-windows"></i> PC</label></div>
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="PlayStation" id="spl2"><label class="form-check-label text-white-50" for="spl2"><i class="fab fa-playstation"></i> PlayStation</label></div>
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="Xbox" id="spl3"><label class="form-check-label text-white-50" for="spl3"><i class="fab fa-xbox"></i> Xbox</label></div>
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="Nintendo Switch" id="spl4"><label class="form-check-label text-white-50" for="spl4"><i class="fas fa-gamepad"></i> Nintendo</label></div>
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="Mac" id="spl5"><label class="form-check-label text-white-50" for="spl5"><i class="fab fa-apple"></i> Mac</label></div>
                                                <div class="col-md-3 col-sm-4 col-6 form-check"><input class="form-check-input steam-plat-cb" type="checkbox" name="platforms[]" value="Linux" id="spl6"><label class="form-check-label text-white-50" for="spl6"><i class="fab fa-linux"></i> Linux</label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="text-white fw-bold">Жанри/Теги</label>
                                        <div class="card bg-dark border-secondary p-3">
                                            <div class="row">
                                            <?php foreach($add_tags as $index => $tag): ?>
                                                <div class="col-md-3 col-sm-4 col-6 form-check">
                                                    <input class="form-check-input steam-tag-cb" type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>" id="satag<?php echo $index; ?>">
                                                    <label class="form-check-label text-white-50" for="satag<?php echo $index; ?>"><?php echo htmlspecialchars($tag); ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"><label class="text-white fw-bold">Розробник</label><input type="text" name="developer" id="steam-form-developer" class="form-control"></div>
                                    <div class="col-md-6"><label class="text-white fw-bold">Видавець</label><input type="text" name="publisher" id="steam-form-publisher" class="form-control"></div>
                                </div>
                                <div class="mb-4"><label class="text-white fw-bold">Дата виходу</label><input type="date" name="release_date" id="steam-form-release" class="form-control"></div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3"><label class="text-white">Мін. вимоги</label><textarea name="sys_min" id="steam-form-sys-min" class="form-control" rows="4"></textarea></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Рек. вимоги</label><textarea name="sys_rec" id="steam-form-sys-rec" class="form-control" rows="4"></textarea></div>
                                </div>
                                <div class="mb-3"><label class="text-white fw-bold">Головна обкладинка (URL) *</label><input type="url" name="cover_url" id="steam-form-cover" class="form-control" required></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 1</label><input type="url" name="screenshot1" id="steam-form-screen1" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 2</label><input type="url" name="screenshot2" id="steam-form-screen2" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 3</label><input type="url" name="screenshot3" id="steam-form-screen3" class="form-control"></div>
                                    <div class="col-md-6 mb-3"><label class="text-white">Скріншот 4</label><input type="url" name="screenshot4" id="steam-form-screen4" class="form-control"></div>
                                </div>
                                <div id="steam-achievements-container"></div>
                                <button type="submit" name="add_game" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">Опублікувати гру</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    window.AdminPanelData = {
        allGames: <?php echo json_encode($all_games_full); ?>
    };
</script>

<?php require_once '../includes/footer.php'; ?>