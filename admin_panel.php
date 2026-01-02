<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("<h1>Доступ заборонено</h1><p>Ви не маєте прав адміністратора.</p>");
}
$admin_user_id = $_SESSION['user_id'];
$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_achievement'])) {
    $ach_game_id = (int)$_POST['ach_game_id'];
    
    $stmt_title = $pdo->prepare("SELECT title FROM games WHERE id = ?");
    $stmt_title->execute([$ach_game_id]);
    $game_title = $stmt_title->fetchColumn();
    
    if ($game_title) {
        $safe_folder_name = preg_replace('/[^A-Za-z0-9\- ]/', '', $game_title);
        $safe_folder_name = trim($safe_folder_name);
        $target_dir = "img/achievements/" . $safe_folder_name . "/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $titles = $_POST['ach_title']; 
        $descs = $_POST['ach_desc'];
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
        if ($success_count > 0) $message = '<div class="alert alert-success">Успішно додано досягнень: ' . $success_count . '</div>';
        else $message = '<div class="alert alert-warning">Не вдалося додати досягнення.</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_game'])) {
    $title = trim($_POST['title']);
    $publisher_id = $admin_user_id;
    $features_str = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $languages_str = isset($_POST['languages']) ? implode(',', $_POST['languages']) : '';
    
    function uploadFile($file_input, $game_title, $filename) {
        if (!isset($file_input) || $file_input['error'] != 0) return [false, "Помилка файлу: $filename"];
        $safe_folder_name = preg_replace('/[^A-Za-z0-9\- ]/', '', $game_title);
        $safe_folder_name = str_replace(' ', '-', $safe_folder_name);
        $game_dir = "img/Game/" . $safe_folder_name . "/";
        if (!is_dir($game_dir)) mkdir($game_dir, 0777, true);
        if ($filename === 'cover') {
            $ext = pathinfo($file_input['name'], PATHINFO_EXTENSION);
            $target_path = "img/Game/" . $safe_folder_name . "." . $ext;
        } else {
            $target_path = $game_dir . $filename;
        }
        if (move_uploaded_file($file_input['tmp_name'], $target_path)) return [true, $target_path];
        else return [false, "Не вдалося перемістити: $filename"];
    }
    $paths = []; $errors = [];
    list($s, $p) = uploadFile($_FILES['cover_url'], $title, 'cover'); if($s) $paths['cover'] = $p; else $errors[] = $p;
    for($i=1; $i<=5; $i++) { list($s, $p) = uploadFile($_FILES["screenshot$i"], $title, "sc$i.jpg"); if($s) $paths["sc$i"] = $p; else $errors[] = $p; }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO games (title, description, cover_url, tags, features, languages, developer, publisher, publisher_id, release_date, sys_min, sys_rec, screenshot1, screenshot2, screenshot3, screenshot4, screenshot5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title, trim($_POST['description']), $paths['cover'], trim($_POST['tags']), $features_str, $languages_str,
                trim($_POST['developer']), trim($_POST['publisher']), $publisher_id, trim($_POST['release_date']), trim($_POST['sys_min']), trim($_POST['sys_rec']),
                $paths['sc1'], $paths['sc2'], $paths['sc3'], $paths['sc4'], $paths['sc5']
            ]);
            $message = '<div class="alert alert-success">Гру "'.htmlspecialchars($title).'" додано!</div>';
        } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка БД: '. $e->getMessage() .'</div>'; }
    } else { $message = '<div class="alert alert-danger">'. implode('<br>', $errors) .'</div>'; }
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
        $message = '<div class="alert alert-success">Слайдер оновлено!</div>';
    } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка: '. $e->getMessage() .'</div>'; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_news_article'])) {
}

$stmt_all_games = $pdo->query("SELECT id, title, cover_url, is_in_hero_slider FROM games ORDER BY id DESC");
$all_games = $stmt_all_games->fetchAll(PDO::FETCH_ASSOC);
$stmt_all_news = $pdo->query("SELECT * FROM news_articles ORDER BY created_at DESC");
$all_news = $stmt_all_news->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Панель адміністратора';
require_once 'includes/header.php';
?>
<div class="container content-section">
    <h2 class="mb-4">Панель адміністратора</h2>
    <?php echo $message; ?>
    <ul class="nav nav-tabs profile-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#hero-panel">Hero-слайдер</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#news-panel">Новини</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-game-panel">Додати гру</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#achievements-panel">Додати досягнення</button></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="hero-panel">
            <div class="profile-content">
                <form action="admin_panel.php" method="POST">
                     <div class="list-group list-group-flush">
                        <?php foreach ($all_games as $game): ?>
                            <label class="list-group-item game-list-item-horizontal admin-panel-item">
                                <img class="game-list-img" src="<?php echo htmlspecialchars($game['cover_url']); ?>">
                                <div class="game-list-info"><h5 class="game-list-title"><?php echo htmlspecialchars($game['title']); ?></h5></div>
                                <div class="form-check form-switch fs-4"><input class="form-check-input" type="checkbox" name="hero_games[]" value="<?php echo $game['id']; ?>" <?php echo ($game['is_in_hero_slider']) ? 'checked' : ''; ?>></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" name="update_hero_slider" class="btn btn-success w-100 mt-4">Зберегти</button>
                </form>
            </div>
        </div>
        <div class="tab-pane fade" id="news-panel"><p class="p-3">Тут новини</p></div>
        <div class="tab-pane fade" id="add-game-panel">
             <p class="p-3 text-white">Форма додавання гри (ідентична developer_panel)</p>
        </div>
        <div class="tab-pane fade" id="achievements-panel">
            <div class="profile-content">
                 <form action="admin_panel.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_achievement" value="1">
                    <div class="mb-4"><select name="ach_game_id" class="form-select" required>
                        <?php foreach ($all_games as $g): ?><option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['title']); ?></option><?php endforeach; ?>
                    </select></div>
                    <div id="achievements-container"><div class="achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark"><h5 class="text-white mb-3">Досягнення #1</h5><div class="row"><div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control" placeholder="Назва" required></div><div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control" placeholder="Опис" required></div><div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control" accept="image/*" required></div></div></div></div>
                    <div class="d-flex justify-content-between"><button type="button" class="btn btn-outline-light" id="add-more-btn">+ Ще</button><button type="submit" class="btn btn-success px-5">Зберегти</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('achievements-container');
    const addBtn = document.getElementById('add-more-btn');
    let count = 1;
    if(addBtn) addBtn.addEventListener('click', function() {
        count++;
        const newGroup = document.createElement('div');
        newGroup.className = 'achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark';
        newGroup.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3"><h5 class="text-white mb-0">Досягнення #${count}</h5><button type="button" class="btn btn-sm btn-danger remove-btn" onclick="this.parentElement.parentElement.remove()">x</button></div><div class="row"><div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control" placeholder="Назва" required></div><div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control" placeholder="Опис" required></div><div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control" accept="image/*" required></div></div>`;
        container.appendChild(newGroup);
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>