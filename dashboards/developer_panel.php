<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'developer') {
    die("<div class='container mt-5 text-center'><h2 class='text-white'>Доступ заборонено</h2><p class='text-white-50'>Ви не маєте прав розробника.</p><a href='index.php' class='btn btn-success mt-3'>На головну</a></div>");
}
$developer_id = $_SESSION['user_id'];
$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_game'])) {
    $game_id = (int)$_POST['game_id'];
    try { 
        $stmt_check = $pdo->prepare("SELECT id FROM games WHERE id = ? AND publisher_id = ?");
        $stmt_check->execute([$game_id, $developer_id]);

        if ($stmt_check->fetch()) {
            $pdo->prepare("DELETE FROM user_library WHERE game_id = ?")->execute([$game_id]);
            $pdo->prepare("DELETE FROM achievements WHERE game_id = ?")->execute([$game_id]);
            $pdo->prepare("DELETE FROM game_reviews WHERE game_id = ?")->execute([$game_id]); 
            $pdo->prepare("DELETE FROM games WHERE id = ?")->execute([$game_id]);
            $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-trash me-2"></i> Гру та всі її дані успішно видалено.</div>'; 
        } else {
            $message = '<div class="alert alert-danger shadow-sm">Помилка: У вас немає прав на видалення цієї гри.</div>';
        }
    } 
    catch (PDOException $e) { $message = '<div class="alert alert-danger shadow-sm">Помилка видалення: ' . $e->getMessage() . '</div>'; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_game_full'])) {
    $edit_game_id = (int)($_POST['edit_game_id'] ?? 0);
    $title = trim($_POST['edit_title'] ?? ''); 
    $description = trim($_POST['edit_description'] ?? '');
    $release_date = $_POST['edit_release_date'] ?? ''; 
    $tags = trim($_POST['edit_tags'] ?? '');
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
            $sql = "UPDATE games SET title=?, description=?, cover_url=?, tags=?, features=?, languages=?, platforms=?, developer=?, publisher=?, release_date=?, sys_min=?, sys_rec=?, screenshot1=?, screenshot2=?, screenshot3=?, screenshot4=? WHERE id=? AND publisher_id=?";
            $pdo->prepare($sql)->execute([$title, $description, $cover_url, $tags, $features_str, $languages_str, $platforms_str, $developer, $publisher, $release_date, $sys_min, $sys_rec, $screenshot1, $screenshot2, $screenshot3, $screenshot4, $edit_game_id, $developer_id]);
            $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> Дані оновлено!</div>';
        } catch (PDOException $e) { $message = '<div class="alert alert-danger shadow-sm">Помилка: ' . $e->getMessage() . '</div>'; }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_achievement'])) {
    $ach_game_id = (int)($_POST['ach_game_id'] ?? 0);
    $stmt_check = $pdo->prepare("SELECT title FROM games WHERE id = ? AND publisher_id = ?"); $stmt_check->execute([$ach_game_id, $developer_id]); $game_title = $stmt_check->fetchColumn();

    if ($game_title) {
        $target_dir = "img/achievements/" . trim(preg_replace('/[^A-Za-z0-9\- ]/', '', $game_title)) . "/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $titles = $_POST['ach_title'] ?? []; $descs = $_POST['ach_desc'] ?? []; $success_count = 0;
        for ($i = 0; $i < count($titles); $i++) {
            $title = trim($titles[$i]); $desc = trim($descs[$i]); if (empty($title)) continue;
            $ach_icon_path = '';
            if (isset($_FILES['ach_icon']['name'][$i]) && $_FILES['ach_icon']['error'][$i] == 0) {
                $target_file = $target_dir . preg_replace('/[^A-Za-z0-9\-]/', '', $title) . "_" . time() . "_" . $i . "." . pathinfo($_FILES['ach_icon']['name'][$i], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['ach_icon']['tmp_name'][$i], $target_file)) $ach_icon_path = $target_file;
            }
            if ($ach_icon_path) {
                $pdo->prepare("INSERT INTO achievements (game_id, title, description, icon_url) VALUES (?, ?, ?, ?)")->execute([$ach_game_id, $title, $desc, $ach_icon_path]); $success_count++;
            }
        }
        $message = '<div class="alert alert-success">Успішно додано досягнень: ' . $success_count . '</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_game'])) {
    $title = trim($_POST['title'] ?? ''); $description = trim($_POST['description'] ?? ''); $release_date = $_POST['release_date'] ?? '';
    $tags = trim($_POST['tags'] ?? ''); $cover_url = trim($_POST['cover_url'] ?? ''); 
    $screenshot1 = trim($_POST['screenshot1'] ?? ''); $screenshot2 = trim($_POST['screenshot2'] ?? ''); 
    $screenshot3 = trim($_POST['screenshot3'] ?? ''); $screenshot4 = trim($_POST['screenshot4'] ?? '');
    $developer = trim($_POST['developer'] ?? 'Невідомо'); $publisher = trim($_POST['publisher'] ?? 'Невідомо');

    $features_str = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $languages_str = isset($_POST['languages']) ? implode(',', $_POST['languages']) : '';
    $platforms_str = isset($_POST['platforms']) ? implode(',', $_POST['platforms']) : '';
    $sys_min = trim($_POST['sys_min'] ?? ''); $sys_rec = trim($_POST['sys_rec'] ?? '');

    if (!empty($title) && !empty($cover_url)) {
        try {
            $sql = "INSERT INTO games (title, description, cover_url, tags, features, languages, platforms, developer, publisher, publisher_id, release_date, sys_min, sys_rec, screenshot1, screenshot2, screenshot3, screenshot4, screenshot5, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
            if ($pdo->prepare($sql)->execute([$title, $description, $cover_url, $tags, $features_str, $languages_str, $platforms_str, $developer, $publisher, $developer_id, $release_date, $sys_min, $sys_rec, $screenshot1, $screenshot2, $screenshot3, $screenshot4, ''])) {
                $new_game_id = $pdo->lastInsertId();
                $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> Гру надіслано на модерацію!</div>';

                if (isset($_POST['rawg_ach_title']) && is_array($_POST['rawg_ach_title'])) {
                    $ach_count = 0; $stmt_ach = $pdo->prepare("INSERT INTO achievements (game_id, title, description, icon_url) VALUES (?, ?, ?, ?)");
                    for ($i = 0; $i < count($_POST['rawg_ach_title']); $i++) {
                        $ach_title = trim($_POST['rawg_ach_title'][$i] ?? '');
                        if (!empty($ach_title)) {
                            $stmt_ach->execute([$new_game_id, $ach_title, trim($_POST['rawg_ach_desc'][$i] ?? ''), trim($_POST['rawg_ach_image'][$i] ?? '')]); $ach_count++;
                        }
                    }
                    if ($ach_count > 0) $message .= '<div class="alert alert-info shadow-sm mt-2 fw-bold"><i class="fas fa-trophy me-2"></i> Автоматично збережено ' . $ach_count . ' досягнень.</div>';
                }
            }
        } catch (PDOException $e) { $message = '<div class="alert alert-danger shadow-sm">Помилка: ' . $e->getMessage() . '</div>'; }
    }
}

$my_games = $pdo->query("SELECT * FROM games WHERE publisher_id = $developer_id ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Панель Розробника';
$base_path = '../';
$page_css = $base_path . 'css/developer_panel.css';
$page_js = $base_path . 'js/developer_panel.js';
require_once '../includes/header.php';
?>

<div class="container content-section mt-4">
    <h2 class="mb-4 text-white"><i class="fas fa-code text-accent me-2"></i> Кабінет Розробника</h2>
    <?php echo $message; ?>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="bg-dark p-3 rounded border border-secondary shadow-sm admin-sidebar sticky-top" style="top: 100px;">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#v-pills-add-game"><i class="fas fa-plus-circle me-2"></i> Додати гру (RAWG)</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-steam"><i class="fab fa-steam me-2"></i> Імпорт (Steam)</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-games"><i class="fas fa-list me-2"></i> Мої ігри</button>
                    <button class="nav-link text-start text-warning" data-bs-toggle="pill" data-bs-target="#v-pills-edit-game"><i class="fas fa-edit me-2"></i> Редагувати гру</button>
                    <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-achievements"><i class="fas fa-trophy me-2"></i> Досягнення</button>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content bg-dark border border-secondary rounded p-4 shadow-sm" id="v-pills-tabContent" style="min-height: 600px;">

                <div class="tab-pane fade show active" id="v-pills-add-game">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="bg-dark p-4 rounded border border-secondary shadow-sm sticky-top" style="top: 100px;">
                                <h4 class="mb-3"><i class="text-accent fas fa-magic me-2"></i> Автозаповнення RAWG</h4>
                                <div class="input-group mb-3 shadow-sm"><input type="text" id="rawg-search-input" class="form-control bg-dark-green text-white border-secondary" placeholder="Введіть назву..."><button class="btn btn-success fw-bold" type="button" id="rawg-search-btn"><i class="fas fa-search"></i></button></div>
                                <div id="rawg-loader" class="text-center d-none my-3"><div class="spinner-border text-accent" role="status"></div></div>
                                <div id="rawg-results" class="list-group"></div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <form action="developer_panel.php" method="POST" id="game-form" class="bg-dark-green p-4 rounded border border-secondary shadow-sm">
                                <h4 class="mb-4"><i class="text-accent fas fa-plus-circle"></i> Додавання гри</h4>
                                <div class="mb-3"><label class="text-white fw-bold">Назва гри *</label><input type="text" name="title" id="form-title" class="form-control" required></div>
                                <div class="mb-3"><label class="text-white fw-bold">Опис гри</label><textarea name="description" id="form-desc" class="form-control" rows="6"></textarea></div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-accent fw-bold">Особливості:</label>
                                        <div class="card bg-dark border-secondary p-2">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Одиночна гра" id="af1"><label class="form-check-label text-white" for="af1">Одиночна гра</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Багатокористувацька" id="af2"><label class="form-check-label text-white" for="af2">Мультиплеєр</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Кооператив" id="af3"><label class="form-check-label text-white" for="af3">Кооператив</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Досягнення" id="af4"><label class="form-check-label text-white" for="af4">Досягнення</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Підтримка контролерів" id="af5"><label class="form-check-label text-white" for="af5">Контролери</label></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-accent fw-bold">Мови:</label>
                                        <div class="card bg-dark border-secondary p-2">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Українська" id="al1"><label class="form-check-label text-white" for="al1">Українська</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Англійська" id="al2"><label class="form-check-label text-white" for="al2">Англійська</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Французька" id="al3"><label class="form-check-label text-white" for="al3">Французька</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Німецька" id="al4"><label class="form-check-label text-white" for="al4">Німецька</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Іспанська" id="al5"><label class="form-check-label text-white" for="al5">Іспанська</label></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-accent fw-bold">Платформи:</label>
                                        <div class="card bg-dark border-secondary p-2">
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="PC (Windows)" id="pl1"><label class="form-check-label text-white" for="pl1"><i class="fab fa-windows"></i> PC</label></div>
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="PlayStation" id="pl2"><label class="form-check-label text-white" for="pl2"><i class="fab fa-playstation"></i> PlayStation</label></div>
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Xbox" id="pl3"><label class="form-check-label text-white" for="pl3"><i class="fab fa-xbox"></i> Xbox</label></div>
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Nintendo Switch" id="pl4"><label class="form-check-label text-white" for="pl4"><i class="fas fa-gamepad"></i> Nintendo</label></div>
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Mac" id="pl5"><label class="form-check-label text-white" for="pl5"><i class="fab fa-apple"></i> Mac</label></div>
                                            <div class="form-check"><input class="form-check-input plat-cb" type="checkbox" name="platforms[]" value="Linux" id="pl6"><label class="form-check-label text-white" for="pl6"><i class="fab fa-linux"></i> Linux</label></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4"><label class="text-white fw-bold">Жанри/Теги</label><input type="text" name="tags" id="form-tags" class="form-control"></div>
                                    <div class="col-md-4"><label class="text-white fw-bold">Розробник</label><input type="text" name="developer" id="form-developer" class="form-control"></div>
                                    <div class="col-md-4"><label class="text-white fw-bold">Видавець</label><input type="text" name="publisher" id="form-publisher" class="form-control"></div>
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
                                <button type="submit" name="add_game" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">Надіслати на модерацію</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-steam">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fab fa-steam text-accent me-2"></i> Імпорт ігор зі Steam API</h4>
                    <div class="bg-dark p-4 rounded border border-secondary shadow-sm">
                        <p class="text-white-50 small mb-4">Введіть назву гри англійською мовою. Система знайде її в магазині Steam та дозволить надіслати заявку на імпорт в БД в один клік.</p>
                        <form id="api-search-form" class="d-flex mb-4">
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-dark-green border-secondary text-white"><i class="fas fa-search"></i></span>
                                <input type="text" id="api-search-query" class="form-control bg-dark-green border-secondary text-white" placeholder="Наприклад: Portal 2..." required>
                                <button type="submit" class="btn btn-success fw-bold px-4">Знайти в Steam</button>
                            </div>
                        </form>
                        <div id="api-search-results" class="row g-3"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-games">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-list text-accent me-2"></i> Ваші опубліковані ігри</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle border-secondary">
                            <thead><tr><th scope="col" style="width: 80px;">Обкладинка</th><th scope="col">Назва</th><th scope="col">Статус</th><th scope="col">Дата виходу</th><th scope="col" class="text-end">Дії</th></tr></thead>
                            <tbody>
                                <?php foreach ($my_games as $game): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td class="fw-bold">
                                            <?php if ($game['is_approved']): ?><a href="../games/game_details.php?id=<?php echo $game['id']; ?>" class="text-white text-decoration-none"><?php echo htmlspecialchars($game['title']); ?></a>
                                            <?php else: ?><span class="text-white-50"><?php echo htmlspecialchars($game['title']); ?></span><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($game['is_approved']): ?><span class="badge bg-success"><i class="fas fa-check-circle"></i> Опубліковано</span>
                                            <?php else: ?><span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> На модерації</span><?php endif; ?>
                                        </td>
                                        <td class="text-white-50"><?php echo htmlspecialchars($game['release_date']); ?></td>
                                        <td class="text-end">
                                            <form action="developer_panel.php" method="POST" class="d-inline" onsubmit="return confirm('Ви впевнені, що хочете назавжди видалити цю гру з каталогу? Всі відгуки та досягнення будуть видалені!');">
                                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>"><button type="submit" name="delete_game" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-edit-game">
                    <div class="bg-dark p-4 rounded border border-secondary shadow-sm">
                        <h4 class="text-warning mb-4"><i class="fas fa-edit me-2"></i> Повне редагування гри</h4>
                        <?php if (empty($my_games)): ?><div class="alert alert-warning">У вас ще немає ігор.</div><?php else: ?>
                            <div class="mb-4 border-bottom border-secondary pb-4">
                                <label class="form-label text-white fw-bold fs-5">Крок 1. Оберіть гру для редагування:</label>
                                <select id="edit-game-select" class="form-select bg-dark-green text-white border-secondary fs-5" required>
                                    <option value="" disabled selected>-- Виберіть гру зі списку --</option>
                                    <?php foreach ($my_games as $g): ?><option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['title']); ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div id="edit-form-container" class="d-none">
                                <form action="developer_panel.php" method="POST">
                                    <input type="hidden" name="edit_game_full" value="1"><input type="hidden" name="edit_game_id" id="edit-game-id">
                                    <h5 class="text-warning mb-3">Крок 2. Змініть дані гри:</h5>
                                    <div class="mb-3"><label class="text-white fw-bold">Назва гри *</label><input type="text" name="edit_title" id="edit-form-title" class="form-control bg-dark-green text-white border-secondary" required></div>
                                    <div class="mb-3"><label class="text-white fw-bold">Опис гри</label><textarea name="edit_description" id="edit-form-desc" class="form-control bg-dark-green text-white border-secondary" rows="6"></textarea></div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-warning fw-bold">Особливості гри:</label>
                                            <div class="card bg-dark-green border-secondary p-2">
                                                <div class="form-check"><input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="Одиночна гра" id="edit_af1"><label class="form-check-label text-white" for="edit_af1">Одиночна гра</label></div>
                                                <div class="form-check"><input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="Багатокористувацька" id="edit_af2"><label class="form-check-label text-white" for="edit_af2">Мультиплеєр</label></div>
                                                <div class="form-check"><input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="Кооператив" id="edit_af3"><label class="form-check-label text-white" for="edit_af3">Кооператив</label></div>
                                                <div class="form-check"><input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="Досягнення" id="edit_af4"><label class="form-check-label text-white" for="edit_af4">Досягнення</label></div>
                                                <div class="form-check"><input class="form-check-input edit-feature-cb" type="checkbox" name="edit_features[]" value="Підтримка контролерів" id="edit_af5"><label class="form-check-label text-white" for="edit_af5">Контролери</label></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-warning fw-bold">Підтримувані мови:</label>
                                            <div class="card bg-dark-green border-secondary p-2">
                                                <div class="form-check"><input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="Українська" id="edit_al1"><label class="form-check-label text-white" for="edit_al1">Українська</label></div>
                                                <div class="form-check"><input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="Англійська" id="edit_al2"><label class="form-check-label text-white" for="edit_al2">Англійська</label></div>
                                                <div class="form-check"><input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="Французька" id="edit_al3"><label class="form-check-label text-white" for="edit_al3">Французька</label></div>
                                                <div class="form-check"><input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="Німецька" id="edit_al4"><label class="form-check-label text-white" for="edit_al4">Німецька</label></div>
                                                <div class="form-check"><input class="form-check-input edit-lang-cb" type="checkbox" name="edit_languages[]" value="Іспанська" id="edit_al5"><label class="form-check-label text-white" for="edit_al5">Іспанська</label></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-warning fw-bold">Платформи:</label>
                                            <div class="card bg-dark-green border-secondary p-2">
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="PC (Windows)" id="epl1"><label class="form-check-label text-white" for="epl1"><i class="fab fa-windows"></i> PC</label></div>
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="PlayStation" id="epl2"><label class="form-check-label text-white" for="epl2"><i class="fab fa-playstation"></i> PlayStation</label></div>
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Xbox" id="epl3"><label class="form-check-label text-white" for="epl3"><i class="fab fa-xbox"></i> Xbox</label></div>
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Nintendo Switch" id="epl4"><label class="form-check-label text-white" for="epl4"><i class="fas fa-gamepad"></i> Nintendo</label></div>
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Mac" id="epl5"><label class="form-check-label text-white" for="epl5"><i class="fab fa-apple"></i> Mac</label></div>
                                                <div class="form-check"><input class="form-check-input edit-plat-cb" type="checkbox" name="edit_platforms[]" value="Linux" id="epl6"><label class="form-check-label text-white" for="epl6"><i class="fab fa-linux"></i> Linux</label></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4"><label class="text-white fw-bold">Жанри/Теги</label><input type="text" name="edit_tags" id="edit-form-tags" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-4"><label class="text-white fw-bold">Розробник</label><input type="text" name="edit_developer" id="edit-form-developer" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-4"><label class="text-white fw-bold">Видавець</label><input type="text" name="edit_publisher" id="edit-form-publisher" class="form-control bg-dark-green text-white border-secondary"></div>
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
                                        <div class="col-md-6 mb-3"><label class="text-white">Скріншот 1</label><input type="url" name="edit_screenshot1" id="edit-form-screen1" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скріншот 2</label><input type="url" name="edit_screenshot2" id="edit-form-screen2" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скріншот 3</label><input type="url" name="edit_screenshot3" id="edit-form-screen3" class="form-control bg-dark-green text-white border-secondary"></div>
                                        <div class="col-md-6 mb-3"><label class="text-white">Скріншот 4</label><input type="url" name="edit_screenshot4" id="edit-form-screen4" class="form-control bg-dark-green text-white border-secondary"></div>
                                    </div>
                                    <button type="submit" class="btn btn-warning fw-bold w-100 fs-5 mt-3 shadow-sm"><i class="fas fa-save me-2"></i> Зберегти всі зміни</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-achievements">
                    <h4 class="text-white border-bottom border-secondary pb-3 mb-4"><i class="fas fa-trophy text-accent me-2"></i> Додати досягнення</h4>
                    <form action="developer_panel.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add_achievement" value="1">
                        <div class="mb-4">
                            <label class="text-white mb-2">Оберіть вашу гру:</label>
                            <select name="ach_game_id" class="form-select bg-dark border-secondary text-white" required>
                                <?php foreach ($my_games as $g): ?><option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['title']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div id="achievements-container">
                            <div class="achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark-green shadow-sm">
                                <h5 class="text-white mb-3">Досягнення #1</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control bg-dark text-white border-secondary" placeholder="Назва" required></div>
                                    <div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control bg-dark text-white border-secondary" placeholder="Опис" required></div>
                                    <div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control bg-dark text-white border-secondary" accept="image/*" required></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-light" id="add-more-btn"><i class="fas fa-plus me-2"></i> Додати ще</button>
                            <button type="submit" class="btn btn-success px-5 fw-bold">Зберегти досягнення</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.DevPanelData = {
        myGames: <?php echo json_encode($my_games); ?>
    };
</script>
<?php require_once '../includes/footer.php'; ?>