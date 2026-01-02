<?php
session_start();
require_once 'includes/db_connect.php';

$message = ''; // Для сповіщень

// --- 1. ОБРОБКА ЗАВАНТАЖЕННЯ АВАТАРА ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['new_avatar'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $file = $_FILES['new_avatar'];

        if ($file['error'] === 0) {
            $target_dir = "img/avatars/";
            $check = getimagesize($file['tmp_name']);
            if ($check !== false) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = "user_" . $user_id . "_" . time() . "." . $ext;
                $target_path = $target_dir . $new_filename;

                $stmt_old = $pdo->prepare("SELECT avatar_url FROM users WHERE id = ?");
                $stmt_old->execute([$user_id]);
                $old_avatar_path = $stmt_old->fetchColumn();

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $stmt_update = $pdo->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
                    $stmt_update->execute([$target_path, $user_id]);
                    
                    if ($old_avatar_path != 'img/avatars/default.png' && file_exists($old_avatar_path)) {
                        @unlink($old_avatar_path);
                    }
                    $message = '<div class="alert alert-success mt-3">Аватар оновлено!</div>';
                } else {
                    $message = '<div class="alert alert-danger mt-3">Помилка завантаження файлу.</div>';
                }
            }
        }
    }
}

// --- 2. НОВЕ: ОБРОБКА ЗМІНИ СТАТУСУ ГРИ ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $game_id = (int)$_POST['game_id'];
        $new_status = $_POST['status'];
        
        // Дозволені статуси (захист)
        $allowed_statuses = ['playing', 'completed', 'planned', 'dropped', 'owned'];
        
        if (in_array($new_status, $allowed_statuses)) {
            $stmt = $pdo->prepare("UPDATE user_library SET status = ? WHERE user_id = ? AND game_id = ?");
            $stmt->execute([$new_status, $user_id, $game_id]);
            // Перезавантажуємо сторінку, щоб побачити зміни
            header("Location: profile.php"); 
            exit;
        }
    }
}

// --- 3. ОБРОБКА КОМЕНТАРЯ ДЛЯ РОЗРОБНИКА ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dev_review'])) {
    // ... (код той самий)
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
    $author_user_id = $_SESSION['user_id'];
    $developer_user_id = (int)$_GET['id']; // Беремо ID з URL
    $comment_text = trim($_POST['comment_text']);
    
    if (!empty($comment_text)) {
        $stmt_insert = $pdo->prepare("INSERT INTO developer_reviews (developer_user_id, author_user_id, comment_text) VALUES (?, ?, ?)");
        $stmt_insert->execute([$developer_user_id, $author_user_id, $comment_text]);
    }
}
// --- 4. ВИДАЛЕННЯ КОМЕНТАРЯ ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_dev_review'])) {
     if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
     $user_id = $_SESSION['user_id'];
     $comment_id = (int)$_POST['comment_id'];
     $current_profile_id = (int)$_GET['id'];
     
     $stmt = $pdo->prepare("DELETE FROM developer_reviews WHERE id = ? AND author_user_id = ?");
     $stmt->execute([$comment_id, $user_id]);
     header("Location: profile.php?id=" . $current_profile_id);
     exit;
}


// --- Визначаємо, чий профіль дивимось ---
$profile_user_id = 0;
$is_own_profile = false;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $profile_user_id = (int)$_GET['id'];
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_user_id) {
        $is_own_profile = true;
    }
} elseif (isset($_SESSION['user_id'])) {
    $profile_user_id = $_SESSION['user_id'];
    $is_own_profile = true;
} else {
    header("Location: login.php");
    exit;
}

// --- Отримуємо дані профілю ---
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$profile_user_id]);
$profile_user = $stmt_user->fetch();

if (!$profile_user) {
    die("Помилка: Користувача з таким ID не знайдено.");
}

// --- Логіка відображення ігор ---
$is_developer = ($profile_user['user_role'] === 'developer');
$games_list = [];
$slider_games = [];
$dev_reviews = [];

if ($is_developer) {
    // Для розробника - ігри, які він створив
    $stmt_games = $pdo->prepare("SELECT * FROM games WHERE publisher_id = ? ORDER BY id DESC");
    $stmt_games->execute([$profile_user_id]);
    $games_list = $stmt_games->fetchAll();
    $slider_games = array_slice($games_list, 0, 4);
    
    // Коментарі стіни
    $stmt_dev_reviews = $pdo->prepare("SELECT r.*, u.username, u.avatar_url FROM developer_reviews r JOIN users u ON r.author_user_id = u.id WHERE r.developer_user_id = ? ORDER BY r.created_at DESC");
    $stmt_dev_reviews->execute([$profile_user_id]);
    $dev_reviews = $stmt_dev_reviews->fetchAll();
    
} else {
    // Для звичайного гравця - його бібліотека + статус
    // SELECT games.*, user_library.status <-- Важливо отримати статус
    $stmt_games = $pdo->prepare("
        SELECT games.*, user_library.status 
        FROM games
        JOIN user_library ON games.id = user_library.game_id
        WHERE user_library.user_id = ?
        ORDER BY user_library.added_at DESC
    ");
    $stmt_games->execute([$profile_user_id]);
    $games_list = $stmt_games->fetchAll();
}

// === ФУНКЦІЯ ДЛЯ ВІДОБРАЖЕННЯ СТАТУСУ ===
function getStatusBadge($status) {
    switch ($status) {
        case 'playing': return ['text' => 'Граю зараз', 'class' => 'btn-success', 'icon' => 'fa-gamepad'];
        case 'completed': return ['text' => 'Пройдено', 'class' => 'btn-primary', 'icon' => 'fa-check-circle'];
        case 'planned': return ['text' => 'В планах', 'class' => 'btn-warning', 'icon' => 'fa-calendar-alt'];
        case 'dropped': return ['text' => 'Закинув', 'class' => 'btn-danger', 'icon' => 'fa-ban'];
        default: return ['text' => 'В колекції', 'class' => 'btn-secondary', 'icon' => 'fa-archive'];
    }
}

$pageTitle = 'Профіль: ' . htmlspecialchars($profile_user['username']);
require_once 'includes/header.php';
?>

<div class="container content-section">
    <div class="row">

        <div class="col-lg-3">
            <div class="profile-sidebar sticky-top" style="top: 100px;">
                <div class="profile-avatar text-center">
                    <?php if ($is_own_profile): ?>
                        <form action="profile.php?id=<?php echo $profile_user_id; ?>" method="POST" enctype="multipart/form-data">
                            <label for="avatar_upload" class="avatar-label">
                                <img src="<?php echo htmlspecialchars($profile_user['avatar_url']); ?>" alt="Avatar" class="img-fluid rounded-circle profile-avatar-img">
                            </label>
                            <input type="file" name="new_avatar" id="avatar_upload" class="d-none" accept="image/*" onchange="this.form.submit()">
                        </form>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars($profile_user['avatar_url']); ?>" alt="Avatar" class="img-fluid rounded-circle profile-avatar-img">
                    <?php endif; ?>
                </div>
                <div class="text-center"><?php echo $message; ?></div>
                <h3 class="profile-username text-center mt-3"><?php echo htmlspecialchars($profile_user['username']); ?></h3>
                <?php if ($is_developer): ?>
                    <p class="text-center text-accent mb-2"><i class="fas fa-check-circle"></i> Офіційний розробник</p>
                <?php endif; ?>
                <div class="profile-usermenu">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-clock"></i> Приєднався: <?php echo date('d.m.Y', strtotime($profile_user['created_at'])); ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">

            <?php if ($is_developer && !empty($slider_games)): ?>
                <h2 class="mb-4">Новини</h2>
                <div class="profile-content mb-4 p-0">
                    <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            <?php foreach ($slider_games as $index => $slide_game): ?>
                            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                <a href="game_details.php?id=<?php echo $slide_game['id']; ?>" class="news-slider-item">
                                    <div class="row g-0">
                                        <div class="col-lg-7"><img src="<?php echo htmlspecialchars($slide_game['cover_url']); ?>" class="news-slider-main-img"></div>
                                        <div class="col-lg-5">
                                            <div class="news-slider-grid">
                                                <img src="<?php echo htmlspecialchars($slide_game['screenshot1']); ?>">
                                                <img src="<?php echo htmlspecialchars($slide_game['screenshot2']); ?>">
                                                <img src="<?php echo htmlspecialchars($slide_game['screenshot3']); ?>">
                                                <img src="<?php echo htmlspecialchars($slide_game['screenshot4']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="news-slider-caption">
                                        <img src="<?php echo htmlspecialchars($slide_game['cover_url']); ?>">
                                        <div class="news-slider-info">
                                            <h5><?php echo htmlspecialchars($slide_game['title']); ?></h5>
                                            <p><?php echo htmlspecialchars(substr($slide_game['description'], 0, 150)) . '...'; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="profile-content mb-4"> 
                <?php if ($is_developer): ?>
                    <ul class="nav nav-tabs profile-tabs" id="gameTabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="all-games-tab" data-bs-toggle="tab" data-bs-target="#all-games" type="button">Усі ігри (<?php echo count($games_list); ?>)</button></li>
                    </ul>
                    <div class="tab-content" id="gameTabsContent">
                        <div class="tab-pane fade show active" id="all-games">
                            <?php foreach ($games_list as $game): ?>
                                <a href="game_details.php?id=<?php echo $game['id']; ?>" class="game-list-item-horizontal">
                                    <img class="game-list-img" src="<?php echo htmlspecialchars($game['cover_url']); ?>">
                                    <div class="game-list-info">
                                        <h5 class="game-list-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                        <p class="game-list-tags"><?php echo htmlspecialchars($game['tags']); ?></p>
                                    </div>
                                    <div class="game-list-date"><?php echo htmlspecialchars($game['release_date']); ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php elseif (empty($games_list)): ?>
                    <div class="p-5 bg-dark-green rounded text-center">
                        <h3 class="text-white">Ваша колекція порожня</h3>
                        <a href="index.php" class="btn btn-success">Переглянути каталог ігор</a>
                    </div>

                <?php else: ?>
                    <h2 class="mb-4">Моя бібліотека</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($games_list as $game): 
                             $status_info = getStatusBadge($game['status'] ?? 'owned');
                        ?>
                            <div class="col">
                                <div class="card h-100 game-card">
                                    <a href="game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none">
                                        <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" class="card-img-top" alt="Cover">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                            
                                            <?php if ($is_own_profile): ?>
                                                <div class="dropdown mt-2">
                                                    <button class="btn <?php echo $status_info['class']; ?> btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas <?php echo $status_info['icon']; ?>"></i> <?php echo $status_info['text']; ?>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-dark w-100">
                                                        <li>
                                                            <form method="POST">
                                                                <input type="hidden" name="update_status" value="1">
                                                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                                                                <button type="submit" name="status" value="playing" class="dropdown-item"><i class="fas fa-gamepad text-success"></i> Граю зараз</button>
                                                                <button type="submit" name="status" value="completed" class="dropdown-item"><i class="fas fa-check-circle text-primary"></i> Пройдено</button>
                                                                <button type="submit" name="status" value="planned" class="dropdown-item"><i class="fas fa-calendar-alt text-warning"></i> В планах</button>
                                                                <button type="submit" name="status" value="dropped" class="dropdown-item"><i class="fas fa-ban text-danger"></i> Закинув</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php else: ?>
                                                <div class="mt-2">
                                                    <span class="badge <?php echo str_replace('btn-', 'bg-', $status_info['class']); ?> w-100 p-2">
                                                        <i class="fas <?php echo $status_info['icon']; ?>"></i> <?php echo $status_info['text']; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($is_developer): ?>
                <h2 class="mb-4">Стіна розробника</h2>
                <div class="profile-content mb-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="profile.php?id=<?php echo $profile_user_id; ?>" method="POST">
                            <div class="mb-3"><textarea class="form-control" name="comment_text" rows="3" placeholder="Ваш коментар..." required></textarea></div>
                            <button type="submit" name="submit_dev_review" class="btn btn-success">Відправити</button>
                        </form>
                    <?php else: ?>
                        <p class="text-center lead"><a href="login.php" class="text-accent">Увійдіть</a></p>
                    <?php endif; ?>
                </div>
                <div class="game-review-list">
                    <?php if (empty($dev_reviews)): ?><p>Коментарів ще немає.</p><?php else: ?>
                        <?php foreach ($dev_reviews as $review): ?>
                            <div class="game-review-card">
                                <div class="game-review-user-info-wrapper"> 
                                    <img src="<?php echo htmlspecialchars($review['avatar_url']); ?>" class="game-review-avatar">
                                    <div class="game-review-user-text"><a href="profile.php?id=<?php echo $review['author_user_id']; ?>" class="game-review-username text-decoration-none"><?php echo htmlspecialchars($review['username']); ?></a></div>
                                </div>
                                <div class="game-review-body-content">
                                    <p><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>
                                    <div class="review-footer">
                                        <small class="game-review-date-bottom">Додано: <?php echo date('d.m.Y \о H:i', strtotime($review['created_at'])); ?></small>
                                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['author_user_id']): ?>
                                            <form method="POST" action="profile.php?id=<?php echo $profile_user_id; ?>" class="delete-review-form">
                                                <input type="hidden" name="comment_id" value="<?php echo $review['id']; ?>">
                                                <button type="submit" name="delete_dev_review" class="btn-delete-review"><i class="fas fa-trash"></i> Видалити</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>