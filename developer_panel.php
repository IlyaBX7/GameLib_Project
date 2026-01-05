<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'developer') {
    die("<h1>Доступ заборонено</h1><p>Ви не маєте прав для доступу до цієї сторінки.</p>");
}
$developer_id = $_SESSION['user_id'];

$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_achievement'])) {
    $ach_game_id = (int)$_POST['ach_game_id'];
    
    $stmt_check = $pdo->prepare("SELECT id, title FROM games WHERE id = ? AND publisher_id = ?");
    $stmt_check->execute([$ach_game_id, $developer_id]);
    $game_data = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if ($game_data) {
        $game_title = $game_data['title'];
        
        $safe_folder_name = preg_replace('/[^A-Za-z0-9\- ]/', '', $game_title);
        $safe_folder_name = trim($safe_folder_name);
        $target_dir = "img/achievements/" . $safe_folder_name . "/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

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

        if ($success_count > 0) {
            $message = '<div class="alert alert-success">Успішно додано досягнень: ' . $success_count . ' до папки "' . htmlspecialchars($safe_folder_name) . '"</div>';
        } else {
            $message = '<div class="alert alert-warning">Не вдалося додати досягнення.</div>';
        }

    } else {
        $message = '<div class="alert alert-danger">Помилка: ви можете додавати досягнення тільки до своїх ігор.</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_game'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tags = trim($_POST['tags']);
    $developer = trim($_POST['developer']);
    $publisher = trim($_POST['publisher']);
    $release_date = trim($_POST['release_date']);
    $sys_min = trim($_POST['sys_min']);
    $sys_rec = trim($_POST['sys_rec']);
    $publisher_id = $_SESSION['user_id']; 
    
    $features_str = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $languages_str = isset($_POST['languages']) ? implode(',', $_POST['languages']) : '';

    function uploadFile($file_input, $game_title, $filename) {
        if (!isset($file_input) || $file_input['error'] != 0) return [false, "Помилка завантаження: $filename"];
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
    for($i=1; $i<=5; $i++) {
        list($s, $p) = uploadFile($_FILES["screenshot$i"], $title, "sc$i.jpg");
        if($s) $paths["sc$i"] = $p; else $errors[] = $p;
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO games (title, description, cover_url, tags, features, languages, developer, publisher, publisher_id, release_date, sys_min, sys_rec, screenshot1, screenshot2, screenshot3, screenshot4, screenshot5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title, $description, $paths['cover'], $tags, 
                $features_str, $languages_str,
                $developer, $publisher, $publisher_id, $release_date, 
                $sys_min, $sys_rec,
                $paths['sc1'], $paths['sc2'], $paths['sc3'], $paths['sc4'], $paths['sc5']
            ]);
            $message = '<div class="alert alert-success">Гру "'.htmlspecialchars($title).'" успішно додано!</div>';
        } catch (PDOException $e) { $message = '<div class="alert alert-danger">Помилка БД: '. $e->getMessage() .'</div>'; }
    } else { $message = '<div class="alert alert-danger">'. implode('<br>', $errors) .'</div>'; }
}

$stmt_my_games = $pdo->prepare("SELECT id, title FROM games WHERE publisher_id = ? ORDER BY title ASC");
$stmt_my_games->execute([$developer_id]);
$my_games = $stmt_my_games->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Панель розробника';
require_once 'includes/header.php';
?>

<div class="container content-section">
    <h2 class="mb-4">Панель розробника</h2>
    <?php echo $message; ?>

    <ul class="nav nav-tabs profile-tabs mb-4" id="devTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="add-game-tab" data-bs-toggle="tab" data-bs-target="#add-game-panel" type="button" role="tab">Додати гру</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="achievements-tab" data-bs-toggle="tab" data-bs-target="#achievements-panel" type="button" role="tab">Додати досягнення</button>
        </li>
    </ul>

    <div class="tab-content">
        
        <div class="tab-pane fade show active" id="add-game-panel" role="tabpanel">
            <form action="developer_panel.php" method="POST" enctype="multipart/form-data" class="bg-dark-green p-4 rounded">
                
                <div class="mb-3"><label>Назва гри</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3"><label>Опис гри</label><textarea name="description" class="form-control" rows="5" required></textarea></div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-accent">Особливості гри:</label>
                        <div class="card bg-dark-green border-secondary p-2">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Одиночна гра" id="f1"><label class="form-check-label text-white" for="f1">Одиночна гра</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Багатокористувацька" id="f2"><label class="form-check-label text-white" for="f2">Багатокористувацька</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Кооператив" id="f3"><label class="form-check-label text-white" for="f3">Кооператив</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Досягнення" id="f4"><label class="form-check-label text-white" for="f4">Досягнення</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Підтримка контролерів" id="f5"><label class="form-check-label text-white" for="f5">Підтримка контролерів</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="Хмарні збереження" id="f6"><label class="form-check-label text-white" for="f6">Хмарні збереження</label></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-accent">Підтримувані мови:</label>
                        <div class="card bg-dark-green border-secondary p-2">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Українська" id="l1"><label class="form-check-label text-white" for="l1">Українська</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Англійська" id="l2"><label class="form-check-label text-white" for="l2">Англійська</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Французька" id="l3"><label class="form-check-label text-white" for="l3">Французька</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Німецька" id="l4"><label class="form-check-label text-white" for="l4">Німецька</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="languages[]" value="Іспанська" id="l5"><label class="form-check-label text-white" for="l5">Іспанська</label></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3"><label>Теги</label><input type="text" name="tags" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Розробник</label><input type="text" name="developer" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Видавець</label><input type="text" name="publisher" class="form-control" required></div>
                </div>
                <div class="mb-3"><label>Дата виходу</label><input type="date" name="release_date" class="form-control" required></div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Мін. вимоги</label><textarea name="sys_min" class="form-control" rows="3"></textarea></div>
                    <div class="col-md-6 mb-3"><label>Рек. вимоги</label><textarea name="sys_rec" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="mb-3"><label>Обкладинка</label><input type="file" name="cover_url" class="form-control" required></div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Скрін 1</label><input type="file" name="screenshot1" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Скрін 2</label><input type="file" name="screenshot2" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Скрін 3</label><input type="file" name="screenshot3" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>Скрін 4</label><input type="file" name="screenshot4" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>Скрін 5</label><input type="file" name="screenshot5" class="form-control" required></div>
                </div>
                <button type="submit" name="add_game" class="btn btn-success btn-lg w-100 mt-3">Додати гру до каталогу</button>
            </form>
        </div>

        <div class="tab-pane fade" id="achievements-panel" role="tabpanel">
            <div class="bg-dark-green p-4 rounded">
                <h4 class="text-accent mb-3">Додати досягнення</h4>
                <form action="developer_panel.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_achievement" value="1">
                    <div class="mb-4">
                        <label class="form-label">Оберіть вашу гру:</label>
                        <select name="ach_game_id" class="form-select form-select-lg" required>
                            <option value="" selected disabled>-- Виберіть зі списку --</option>
                            <?php foreach ($my_games as $g): ?>
                                <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(empty($my_games)): ?><div class="form-text text-danger">Ви ще не додали жодної гри.</div><?php endif; ?>
                    </div>
                    <div id="achievements-container">
                        <div class="achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark">
                            <h5 class="text-white mb-3">Досягнення #1</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3"><label class="form-label small text-muted">Назва</label><input type="text" name="ach_title[]" class="form-control" required></div>
                                <div class="col-md-4 mb-3"><label class="form-label small text-muted">Опис</label><input type="text" name="ach_desc[]" class="form-control" required></div>
                                <div class="col-md-4 mb-3"><label class="form-label small text-muted">Іконка</label><input type="file" name="ach_icon[]" class="form-control" accept="image/*" required></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-light" id="add-more-btn"><i class="fas fa-plus"></i> Додати ще одне</button>
                        <button type="submit" class="btn btn-success px-5" <?php echo empty($my_games) ? 'disabled' : ''; ?>>Зберегти всі досягнення</button>
                    </div>
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
    addBtn.addEventListener('click', function() {
        count++;
        const newGroup = document.createElement('div');
        newGroup.className = 'achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark';
        newGroup.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3"><h5 class="text-white mb-0">Досягнення #${count}</h5><button type="button" class="btn btn-sm btn-danger remove-btn" onclick="this.parentElement.parentElement.remove()">x</button></div>
            <div class="row">
                <div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control" placeholder="Назва" required></div>
                <div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control" placeholder="Опис" required></div>
                <div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control" accept="image/*" required></div>
            </div>`;
        container.appendChild(newGroup);
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>