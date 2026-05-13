<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/cloudinary.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sync_steam'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $user_id = $_SESSION['user_id'];

    $stmt_steam = $pdo->prepare("SELECT steam_id FROM users WHERE id = ?");
    $stmt_steam->execute([$user_id]);
    $steam_id = trim($stmt_steam->fetchColumn());

    if (empty($steam_id)) {
        $message = "<div class='alert alert-warning shadow-sm mt-3 fw-bold'><i class='fas fa-exclamation-triangle me-2'></i> Steam ID не вказано в налаштуваннях!</div>";
    } else {
        $api_key = '47B7338770F762231CCD7185B9592765'; 
        $steam_api_url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key={$api_key}&steamid={$steam_id}&include_appinfo=1&format=json";

        $response = @file_get_contents($steam_api_url);

        if ($response === FALSE) {
            $message = "<div class='alert alert-danger shadow-sm mt-3 fw-bold'><i class='fas fa-times-circle me-2'></i> Помилка з'єднання. Перевірте правильність Steam ID.</div>";
        } else {
            $data = json_decode($response, true);
            if (!isset($data['response']['games'])) {
                $message = "<div class='alert alert-warning shadow-sm mt-3 fw-bold'><i class='fas fa-eye-slash me-2'></i> Ігри не знайдені. Переконайтесь, що ваш профіль Steam відкритий.</div>";
            } else {
                $steam_games = $data['response']['games'];
                $added_count = 0;

                $stmt_local = $pdo->query("SELECT id, title FROM games");
                $local_games = $stmt_local->fetchAll(PDO::FETCH_ASSOC);

                foreach ($steam_games as $sg) {
                    $steam_title = mb_strtolower(trim($sg['name']), 'UTF-8'); 
                    foreach ($local_games as $lg) {
                        $local_title = mb_strtolower(trim($lg['title']), 'UTF-8');
                        if ($steam_title === $local_title) {
                            $check = $pdo->prepare("SELECT id FROM user_library WHERE user_id=? AND game_id=?");
                            $check->execute([$user_id, $lg['id']]);
                            if (!$check->fetch()) {
                                $pdo->prepare("INSERT INTO user_library (user_id, game_id, status) VALUES (?, ?, 'owned')")->execute([$user_id, $lg['id']]);
                                $added_count++;
                            }
                            break; 
                        }
                    }
                }
                $message = "<div class='alert alert-info shadow-sm mt-3 fw-bold' style='background-color: rgba(102, 192, 244, 0.1); border-color: #66c0f4; color: #66c0f4;'><i class='fab fa-steam me-2 fs-5'></i> Синхронізація успішна! Додано: {$added_count} ігор.</div>";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['new_avatar'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $file = $_FILES['new_avatar'];
        if ($file['error'] === 0) {
            $check = getimagesize($file['tmp_name']);
            if ($check !== false) {
                $cloudinaryUrl = uploadToCloudinary($file);
                
                if ($cloudinaryUrl) {
                    $stmt_old = $pdo->prepare("SELECT avatar_url FROM users WHERE id = ?");
                    $stmt_old->execute([$user_id]);
                    $old_avatar_path = $stmt_old->fetchColumn();

                    $pdo->prepare("UPDATE users SET avatar_url = ? WHERE id = ?")->execute([$cloudinaryUrl, $user_id]);
                    
                    // Видаляємо старий аватар, якщо він був локальним (для сумісності зі старими даними)
                    if ($old_avatar_path && strpos($old_avatar_path, 'img/avatars/') === 0 && $old_avatar_path != 'img/avatars/default.png' && file_exists($old_avatar_path)) { 
                        @unlink($old_avatar_path); 
                    }
                    
                    $message = '<div class="alert alert-success mt-3 shadow-sm">Аватар успішно оновлено!</div>';
                } else {
                    $message = '<div class="alert alert-danger mt-3 shadow-sm">Помилка при завантаженні аватара в Cloudinary.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger mt-3 shadow-sm">Вибраний файл не є зображенням.</div>';
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $game_id = (int)$_POST['game_id'];
        $new_status = $_POST['status'];

        if ($new_status === 'remove') {
            $pdo->prepare("DELETE FROM user_library WHERE user_id = ? AND game_id = ?")->execute([$user_id, $game_id]);
        } else {
            $allowed_statuses = ['playing', 'completed', 'planned', 'dropped', 'owned'];
            if (in_array($new_status, $allowed_statuses)) {
                $pdo->prepare("UPDATE user_library SET status = ? WHERE user_id = ? AND game_id = ?")->execute([$new_status, $user_id, $game_id]);
            }
        }
        $redirect_id = isset($_GET['id']) ? "?id=" . (int)$_GET['id'] : "";
        header("Location: profile.php" . $redirect_id); 
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_dev_review'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $author_user_id = $_SESSION['user_id'];
    $developer_user_id = (int)$_GET['id'];
    $comment_text = trim($_POST['comment_text']);
    if (!empty($comment_text)) {
        $pdo->prepare("INSERT INTO developer_reviews (developer_user_id, author_user_id, comment_text) VALUES (?, ?, ?)")->execute([$developer_user_id, $author_user_id, $comment_text]);
        $msg = "Користувач {$_SESSION['username']} залишив відгук на вашій стіні.";
        $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")->execute([$developer_user_id, $msg, "user/profile.php?id={$developer_user_id}"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_dev_review'])) {
     if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
     $user_id = $_SESSION['user_id'];
     $comment_id = (int)$_POST['comment_id'];
     $current_profile_id = (int)$_GET['id'];
     $pdo->prepare("DELETE FROM developer_reviews WHERE id = ? AND author_user_id = ?")->execute([$comment_id, $user_id]);
     header("Location: profile.php?id=" . $current_profile_id); exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_action'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $action = $_POST['friend_action']; $target_id = (int)$_POST['target_id']; $current_id = $_SESSION['user_id'];
    $current_profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $current_id;
    if ($action == 'add') {
        $pdo->prepare("INSERT INTO friendships (user_id1, user_id2, status) VALUES (?, ?, 'pending')")->execute([$current_id, $target_id]);
        $msg = "{$_SESSION['username']} надіслав(ла) вам заявку в друзі.";
        $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")->execute([$target_id, $msg, "user/profile.php"]);
    } elseif ($action == 'accept') {
        $pdo->prepare("UPDATE friendships SET status = 'accepted' WHERE user_id1 = ? AND user_id2 = ?")->execute([$target_id, $current_id]);
        $msg = "{$_SESSION['username']} прийняв(ла) вашу заявку в друзі.";
        $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")->execute([$target_id, $msg, "user/profile.php?id={$current_id}"]);
    } elseif ($action == 'remove' || $action == 'cancel') {
        $pdo->prepare("DELETE FROM friendships WHERE (user_id1 = ? AND user_id2 = ?) OR (user_id1 = ? AND user_id2 = ?)")->execute([$current_id, $target_id, $target_id, $current_id]);
    }
    header("Location: profile.php?id=" . $current_profile_id); exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['follow_action'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $action = $_POST['follow_action']; $dev_id = (int)$_POST['target_id']; $user_id = $_SESSION['user_id'];
    $current_profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $user_id;
    if ($action == 'follow') {
        $pdo->prepare("INSERT INTO followers (user_id, developer_id) VALUES (?, ?)")->execute([$user_id, $dev_id]);
        $msg = "{$_SESSION['username']} тепер стежить за вашими оновленнями.";
        $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")->execute([$dev_id, $msg, "user/profile.php?id={$user_id}"]);
    } elseif ($action == 'unfollow') {
        $pdo->prepare("DELETE FROM followers WHERE user_id = ? AND developer_id = ?")->execute([$user_id, $dev_id]);
    }
    header("Location: profile.php?id=" . $current_profile_id); exit;
}

$profile_user_id = 0; $is_own_profile = false;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $profile_user_id = (int)$_GET['id'];
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_user_id) { $is_own_profile = true; }
} elseif (isset($_SESSION['user_id'])) {
    $profile_user_id = $_SESSION['user_id']; $is_own_profile = true;
} else { header("Location: ../auth/login.php"); exit; }

$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$profile_user_id]);
$profile_user = $stmt_user->fetch();
if (!$profile_user) { die("<h2 class='text-white text-center mt-5'>Помилка: Користувача не знайдено.</h2>"); }

$is_developer = ($profile_user['user_role'] === 'developer');

$friendship_status = null; $action_user_id = null;
if (isset($_SESSION['user_id']) && !$is_own_profile) {
    $stmt_check = $pdo->prepare("SELECT status, user_id1 FROM friendships WHERE (user_id1 = ? AND user_id2 = ?) OR (user_id1 = ? AND user_id2 = ?)");
    $stmt_check->execute([$_SESSION['user_id'], $profile_user_id, $profile_user_id, $_SESSION['user_id']]);
    $fs = $stmt_check->fetch(PDO::FETCH_ASSOC);
    if ($fs) { $friendship_status = $fs['status']; $action_user_id = $fs['user_id1']; }
}

$is_visible = true;
$privacy = $profile_user['privacy_profile'] ?? 'public';
if (!$is_own_profile) {
    if ($privacy === 'private') $is_visible = false;
    elseif ($privacy === 'friends' && $friendship_status !== 'accepted') $is_visible = false;
}

$games_list = []; $friends_list = []; $followers_list = []; $followed_devs = []; $showcase_game = null;
$slider_games = []; $dev_reviews = []; $status_data = ['playing'=>0, 'completed'=>0, 'planned'=>0, 'dropped'=>0, 'owned'=>0]; $top_genres = [];

if ($is_developer) {
    $stmt_games = $pdo->prepare("SELECT * FROM games WHERE publisher_id = ? ORDER BY id DESC");
    $stmt_games->execute([$profile_user_id]);
    $games_list = $stmt_games->fetchAll();
    $slider_games = array_slice($games_list, 0, 4);

    $stmt_dev_reviews = $pdo->prepare("SELECT r.*, u.username, u.avatar_url FROM developer_reviews r JOIN users u ON r.author_user_id = u.id WHERE r.developer_user_id = ? ORDER BY r.created_at DESC");
    $stmt_dev_reviews->execute([$profile_user_id]);
    $dev_reviews = $stmt_dev_reviews->fetchAll();
} else {
    $stmt_games = $pdo->prepare("SELECT games.*, user_library.status FROM games JOIN user_library ON games.id = user_library.game_id WHERE user_library.user_id = ? ORDER BY user_library.added_at DESC");
    $stmt_games->execute([$profile_user_id]);
    $games_list = $stmt_games->fetchAll();
}

$stmt_friends = $pdo->prepare("SELECT u.id, u.username, u.avatar_url, u.user_role FROM friendships f JOIN users u ON (u.id = f.user_id1 OR u.id = f.user_id2) AND u.id != ? WHERE (f.user_id1 = ? OR f.user_id2 = ?) AND f.status = 'accepted'");
$stmt_friends->execute([$profile_user_id, $profile_user_id, $profile_user_id]);
$friends_list = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);

$pending_requests = [];
if ($is_own_profile) {
    $stmt_pending = $pdo->prepare("SELECT u.id, u.username, u.avatar_url FROM friendships f JOIN users u ON u.id = f.user_id1 WHERE f.user_id2 = ? AND f.status = 'pending'");
    $stmt_pending->execute([$profile_user_id]);
    $pending_requests = $stmt_pending->fetchAll(PDO::FETCH_ASSOC);
}

$is_following = false;
if ($is_developer) {
    $stmt_followers = $pdo->prepare("SELECT u.id, u.username, u.avatar_url, u.user_role FROM followers f JOIN users u ON f.user_id = u.id WHERE f.developer_id = ? ORDER BY f.created_at DESC");
    $stmt_followers->execute([$profile_user_id]);
    $followers_list = $stmt_followers->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'developer' && !$is_own_profile) {
        $stmt_check_follow = $pdo->prepare("SELECT id FROM followers WHERE user_id = ? AND developer_id = ?");
        $stmt_check_follow->execute([$_SESSION['user_id'], $profile_user_id]);
        if ($stmt_check_follow->fetch()) { $is_following = true; }
    }
} else {
    $stmt_followed = $pdo->prepare("SELECT u.id, u.username, u.avatar_url, u.user_role FROM followers f JOIN users u ON f.developer_id = u.id WHERE f.user_id = ? ORDER BY f.created_at DESC");
    $stmt_followed->execute([$profile_user_id]);
    $followed_devs = $stmt_followed->fetchAll(PDO::FETCH_ASSOC);
}

if (!$is_developer || count($games_list) > 0) {
    $stmt_stats = $pdo->prepare("SELECT status, COUNT(*) as cnt FROM user_library WHERE user_id = ? GROUP BY status");
    $stmt_stats->execute([$profile_user_id]);
    foreach($stmt_stats->fetchAll() as $row) { $status_data[$row['status']] = $row['cnt']; }

    $stmt_genres = $pdo->prepare("SELECT g.tags FROM user_library ul JOIN games g ON ul.game_id = g.id WHERE ul.user_id = ?");
    $stmt_genres->execute([$profile_user_id]);
    $all_tags = [];
    foreach($stmt_genres->fetchAll() as $row) {
        $tags = array_map('trim', explode(',', $row['tags']));
        foreach($tags as $t) { if(!empty($t)) { $all_tags[$t] = isset($all_tags[$t]) ? $all_tags[$t] + 1 : 1; } }
    }
    arsort($all_tags);
    $top_genres = array_slice($all_tags, 0, 5, true);
}

if (!empty($profile_user['showcase_game_id'])) {
    $stmt_showcase = $pdo->prepare("SELECT id, title, cover_url, description FROM games WHERE id = ?");
    $stmt_showcase->execute([$profile_user['showcase_game_id']]);
    $showcase_game = $stmt_showcase->fetch(PDO::FETCH_ASSOC);
}

$stmt_rev_count = $pdo->prepare("SELECT COUNT(*) FROM game_reviews WHERE user_id = ?");
$stmt_rev_count->execute([$profile_user_id]);
$review_count = $stmt_rev_count->fetchColumn();

$bonus_xp = isset($profile_user['bonus_xp']) ? (int)$profile_user['bonus_xp'] : 0;
$xp = (count($games_list) * 50) + ($review_count * 100) + (count($friends_list) * 20) + $bonus_xp;
if ($is_developer) $xp += (count($followers_list) * 50);

$level = floor(sqrt($xp / 100)) + 1;
$current_lvl_xp = pow($level - 1, 2) * 100;
$next_lvl_xp = pow($level, 2) * 100;
$progress_percent = 0;
if ($next_lvl_xp > $current_lvl_xp) {
    $progress_percent = (($xp - $current_lvl_xp) / ($next_lvl_xp - $current_lvl_xp)) * 100;
}

$show_frame = isset($profile_user['show_level_frame']) ? $profile_user['show_level_frame'] : 1;
$frame_class = 'avatar-frame-standard';

if ($show_frame) {
    if ($level >= 50) { $frame_class = 'avatar-frame-rgb'; } 
    elseif ($level >= 10) { $frame_class = 'avatar-frame-gold'; } 
    elseif ($level >= 5) { $frame_class = 'avatar-frame-silver'; }
}

$badges = [];
if (strtotime($profile_user['created_at']) < strtotime('-1 month')) { $badges[] = ['icon' => 'fa-crown text-warning', 'title' => 'Старожил сайту']; }
if (count($games_list) >= 5) { $badges[] = ['icon' => 'fa-layer-group text-info', 'title' => 'Колекціонер']; }
if ($review_count >= 1) { $badges[] = ['icon' => 'fa-pen-nib text-danger', 'title' => 'Критик']; }
if ($is_developer && count($games_list) >= 1) { $badges[] = ['icon' => 'fa-code text-success', 'title' => 'Творець']; }

if ($level >= 50) { $badges[] = ['icon' => 'fa-gem rgb-text-animation', 'title' => 'Легендарний Ранг (50+ Рівень)']; } 
elseif ($level >= 10) { $badges[] = ['icon' => 'fa-medal text-warning', 'title' => 'Золотий Ранг (10+ Рівень)']; } 
elseif ($level >= 5) { $badges[] = ['icon' => 'fa-medal text-light', 'title' => 'Срібний Ранг (5+ Рівень)']; }

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status) {
        switch ($status) {
            case 'playing': return ['text' => 'Граю зараз', 'class' => 'btn-success', 'icon' => 'fa-gamepad'];
            case 'completed': return ['text' => 'Пройдено', 'class' => 'btn-primary', 'icon' => 'fa-check-circle'];
            case 'planned': return ['text' => 'В планах', 'class' => 'btn-warning', 'icon' => 'fa-calendar-alt'];
            case 'dropped': return ['text' => 'Закинув', 'class' => 'btn-danger', 'icon' => 'fa-ban'];
            default: return ['text' => 'В колекції', 'class' => 'btn-secondary', 'icon' => 'fa-archive'];
        }
    }
}

$pageTitle = 'Профіль: ' . htmlspecialchars($profile_user['username']);
$base_path = '../';
$page_css = $base_path . 'css/profile.css';
require_once '../includes/header.php';
?>

<style>
    <?php if (!empty($profile_user['accent_color'])): ?>
    :root {
        --accent-color: <?php echo htmlspecialchars($profile_user['accent_color']); ?> !important;
        --accent-hover: <?php echo htmlspecialchars($profile_user['accent_color']); ?>cc !important;
    }
    <?php endif; ?>

    <?php if (!empty($profile_user['banner_url'])): ?>
    body {
        background: linear-gradient(rgba(11, 18, 14, 0.85), rgba(18, 30, 23, 0.95)), url('<?php echo htmlspecialchars(resolve_url($profile_user['banner_url'])); ?>') center center / cover fixed !important;
    }
    .profile-sidebar, .profile-content, .info-block {
        background-color: rgba(26, 43, 33, 0.85) !important;
        backdrop-filter: blur(8px);
    }
    <?php endif; ?>

    .avatar-frame-standard { border-color: var(--accent-color) !important; }
    .avatar-frame-silver { border-color: #e0e0e0 !important; box-shadow: 0 0 15px #ffffff, 0 0 25px rgba(192, 192, 192, 0.6) !important; }
    .avatar-frame-gold { border-color: #ffd700 !important; animation: goldGlow 2s infinite alternate ease-in-out; }
    @keyframes goldGlow { 0% { box-shadow: 0 0 15px #ffd700, 0 0 30px rgba(255, 170, 0, 0.6); border-color: #ffd700; } 100% { box-shadow: 0 0 25px #ffaa00, 0 0 45px rgba(255, 102, 0, 0.8); border-color: #ffea00; } }
    .avatar-frame-rgb { animation: rgbBorderColor 3s linear infinite, rgbGlow 3s linear infinite; }
    @keyframes rgbBorderColor { 0% { border-color: #ff0000; } 17% { border-color: #ff7f00; } 33% { border-color: #ffff00; } 50% { border-color: #00ff00; } 67% { border-color: #0000ff; } 84% { border-color: #4b0082; } 100% { border-color: #ff0000; } }
    @keyframes rgbGlow { 0% { box-shadow: 0 0 25px #ff0000; } 17% { box-shadow: 0 0 25px #ff7f00; } 33% { box-shadow: 0 0 25px #ffff00; } 50% { box-shadow: 0 0 25px #00ff00; } 67% { box-shadow: 0 0 25px #0000ff; } 84% { box-shadow: 0 0 25px #4b0082; } 100% { box-shadow: 0 0 25px #ff0000; } }
    .rgb-text-animation { animation: rgbTextColor 3s linear infinite; }
    @keyframes rgbTextColor { 0% { color: #ff0000; } 17% { color: #ff7f00; } 33% { color: #ffff00; } 50% { color: #00ff00; } 67% { color: #0000ff; } 84% { color: #4b0082; } 100% { color: #ff0000; } }
</style>

<div class="container content-section">
    <div class="row mt-4">

        <div class="col-lg-3">
            <div class="profile-sidebar sticky-top" style="top: 100px;">
                <div class="profile-avatar text-center">
                    <?php if ($is_own_profile): ?>
                        <form action="profile.php?id=<?php echo $profile_user_id; ?>" method="POST" enctype="multipart/form-data">
                            <label for="avatar_upload" class="avatar-label" style="cursor: pointer;">
                                <img src="<?php echo htmlspecialchars(resolve_url($profile_user['avatar_url'] ?? 'img/avatars/default.png')); ?>" alt="Avatar" class="img-fluid rounded-circle profile-avatar-img shadow <?php echo $frame_class; ?>" style="width: 150px !important; height: 150px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; border-width: 5px; border-style: solid; transition: border-color 0.3s, box-shadow 0.3s;">
                            </label>
                            <input type="file" name="new_avatar" id="avatar_upload" class="d-none" accept="image/*" onchange="this.form.submit()">
                        </form>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars(resolve_url($profile_user['avatar_url'] ?? 'img/avatars/default.png')); ?>" alt="Avatar" class="img-fluid rounded-circle profile-avatar-img shadow <?php echo $frame_class; ?>" style="width: 150px !important; height: 150px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; border-width: 5px; border-style: solid; transition: border-color 0.3s, box-shadow 0.3s;">
                    <?php endif; ?>
                </div>

                <div class="text-center"><?php echo $message; ?></div>
                <h3 class="profile-username text-center mt-3 mb-1"><?php echo htmlspecialchars($profile_user['username']); ?></h3>

                <p class="text-center text-accent mb-3 fw-bold">
                    <?php if ($is_developer): ?> <i class="fas fa-check-circle"></i> Розробник
                    <?php else: echo ($profile_user['user_role'] === 'admin') ? 'Адміністратор' : 'Геймер'; endif; ?>
                </p>

                <div class="mt-3 bg-dark p-3 rounded border border-secondary text-center shadow-sm">
                    <h6 class="text-white mb-2 fw-bold"><i class="fas fa-star text-warning"></i> Рівень <?php echo $level; ?></h6>
                    <div class="progress mb-1 border border-secondary" style="height: 12px; background-color: #0b120e;">
                        <div class="progress-bar bg-accent progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $progress_percent; ?>%;"></div>
                    </div>
                    <small class="text-white-50"><?php echo $xp; ?> / <?php echo $next_lvl_xp; ?> XP</small>

                    <?php if(!empty($badges)): ?>
                    <div class="mt-3 d-flex justify-content-center flex-wrap gap-2">
                        <?php foreach($badges as $b): ?>
                            <div title="<?php echo htmlspecialchars($b['title']); ?>" class="bg-dark-green border border-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; cursor: pointer;">
                                <i class="fas <?php echo htmlspecialchars($b['icon']); ?> fs-5"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($is_own_profile): ?>
                    <div class="text-center mt-4 mb-3">
                        <a href="settings.php" class="btn btn-outline-success w-100 fw-bold mb-2"><i class="fas fa-cog"></i> Налаштування</a>
                        <?php if (!empty($profile_user['steam_id'])): ?>
                            <form method="POST" action="profile.php">
                                <button type="submit" name="sync_steam" class="btn w-100 fw-bold text-white shadow-sm" style="background-color: #171a21; border: 1px solid #66c0f4; transition: 0.2s;" onmouseover="this.style.backgroundColor='#66c0f4'; this.style.color='#171a21';" onmouseout="this.style.backgroundColor='#171a21'; this.style.color='white';">
                                    <i class="fab fa-steam fs-5 align-middle me-1"></i> Sync Steam
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <div class="text-center mt-4 mb-3">
                        <?php 
                        $viewer_role = $_SESSION['user_role'] ?? 'user';
                        $target_role = $profile_user['user_role'] ?? 'user';
                        if ($target_role === 'developer' && $viewer_role !== 'developer'): 
                        ?>
                            <form method="POST" action="profile.php?id=<?php echo $profile_user_id; ?>">
                                <input type="hidden" name="target_id" value="<?php echo $profile_user_id; ?>">
                                <?php if ($is_following): ?>
                                    <button type="submit" name="follow_action" value="unfollow" class="btn btn-outline-danger w-100 fw-bold"><i class="fas fa-bell-slash"></i> Відписатися</button>
                                <?php else: ?>
                                    <button type="submit" name="follow_action" value="follow" class="btn btn-success w-100 fw-bold"><i class="fas fa-bell"></i> Підписатися</button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="profile.php?id=<?php echo $profile_user_id; ?>">
                                <input type="hidden" name="target_id" value="<?php echo $profile_user_id; ?>">
                                <?php if ($friendship_status === 'accepted'): ?>
                                    <button type="submit" name="friend_action" value="remove" class="btn btn-outline-danger w-100 fw-bold"><i class="fas fa-user-minus"></i> Видалити з друзів</button>
                                <?php elseif ($friendship_status === 'pending'): ?>
                                    <?php if ($action_user_id == $_SESSION['user_id']): ?>
                                        <button type="submit" name="friend_action" value="cancel" class="btn btn-warning w-100 text-dark fw-bold"><i class="fas fa-clock"></i> Запит надіслано</button>
                                    <?php else: ?>
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="friend_action" value="accept" class="btn btn-success w-100 fw-bold"><i class="fas fa-check"></i> Прийняти</button>
                                            <button type="submit" name="friend_action" value="remove" class="btn btn-danger w-100 fw-bold"><i class="fas fa-times"></i> Відхилити</button>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="submit" name="friend_action" value="add" class="btn btn-success w-100 fw-bold"><i class="fas fa-user-plus"></i> Додати в друзі</button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="profile-usermenu mt-3">
                    <ul class="list-group list-group-flush">
                        <?php if ($is_own_profile && count($pending_requests) > 0): ?>
                            <li class="list-group-item border-secondary bg-dark mb-2" style="border-radius: 8px;">
                                <strong class="text-accent d-block mb-2"><i class="fas fa-bell"></i> Заявки в друзі (<?php echo count($pending_requests); ?>)</strong>
                                <?php foreach ($pending_requests as $req): ?>
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-secondary last-border-none">
                                        <a href="profile.php?id=<?php echo $req['id']; ?>" class="text-decoration-none text-white small d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars(resolve_url($req['avatar_url'])); ?>" style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important;" class="rounded-circle me-2">
                                            <?php echo htmlspecialchars($req['username']); ?>
                                        </a>
                                        <form method="POST" action="profile.php?id=<?php echo $profile_user_id; ?>" class="d-flex gap-1 m-0">
                                            <input type="hidden" name="target_id" value="<?php echo $req['id']; ?>">
                                            <button type="submit" name="friend_action" value="accept" class="btn btn-success btn-sm py-0 px-2"><i class="fas fa-check"></i></button>
                                            <button type="submit" name="friend_action" value="remove" class="btn btn-danger btn-sm py-0 px-2"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </li>
                        <?php endif; ?>

                        <li class="list-group-item border-secondary"><i class="fas fa-user-friends text-white-50"></i> Друзі: <span class="badge bg-success rounded-pill"><?php echo count($friends_list); ?></span></li>

                        <?php if ($is_developer): ?>
                            <li class="list-group-item border-secondary"><i class="fas fa-users text-white-50"></i> Підписники: <span class="badge bg-success rounded-pill"><?php echo count($followers_list); ?></span></li>
                        <?php else: ?>
                            <li class="list-group-item border-secondary"><i class="fas fa-building text-white-50"></i> Улюблені студії: <span class="badge bg-success rounded-pill"><?php echo count($followed_devs); ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($profile_user['country'])): ?>
                            <li class="list-group-item border-secondary"><i class="fas fa-map-marker-alt text-white-50"></i> <?php echo htmlspecialchars($profile_user['country']); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($profile_user['birth_date'])): ?>
                            <li class="list-group-item border-secondary"><i class="fas fa-birthday-cake text-white-50"></i> <?php echo date('d.m.Y', strtotime($profile_user['birth_date'])); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($profile_user['favorite_genre'])): ?>
                            <li class="list-group-item border-secondary"><i class="fas fa-gamepad text-white-50"></i> <?php echo htmlspecialchars($profile_user['favorite_genre']); ?></li>
                        <?php endif; ?>
                        <li class="list-group-item border-secondary"><i class="fas fa-clock text-white-50"></i> З нами від: <?php echo date('d.m.Y', strtotime($profile_user['created_at'])); ?></li>
                    </ul>
                </div>

                <?php if (!empty($profile_user['bio'])): ?>
                    <hr class="border-secondary mt-3 mb-3">
                    <div class="text-start text-white-50 small" style="line-height: 1.5;">
                        <strong class="text-white">Про себе:</strong><br>
                        <?php echo nl2br(htmlspecialchars($profile_user['bio'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-9 mt-4 mt-lg-0">

            <?php if (!$is_visible): ?>
                <div class="profile-content text-center py-5 shadow-sm">
                    <i class="fas fa-lock text-white-50 mb-4" style="font-size: 5rem;"></i>
                    <h2 class="text-white">Цей профіль закритий</h2>
                    <p class="text-white-50 fs-5 mb-0">
                        <?php echo ($privacy === 'friends') ? "Тільки друзі можуть бачити інформацію цього користувача." : "Цей користувач приховав свою сторінку."; ?>
                    </p>
                </div>
            <?php else: ?>

                <?php if ($is_developer && !empty($slider_games)): ?>
                    <h2 class="mb-4 text-white">Новини розробника</h2>
                    <div class="profile-content mb-4 p-0 shadow-sm border border-secondary">
                        <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded">
                                <?php foreach ($slider_games as $index => $slide_game): ?>
                                <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                    <a href="../games/game_details.php?id=<?php echo $slide_game['id']; ?>" class="news-slider-item" style="display: block; position: relative;">
                                        <div class="row g-0">
                                            <div class="col-lg-7"><img src="<?php echo htmlspecialchars(resolve_url($slide_game['cover_url'])); ?>" style="width: 100%; height: 350px; object-fit: cover;"></div>
                                            <div class="col-lg-5">
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; height: 350px; gap: 2px;">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($slide_game['screenshot1'])); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($slide_game['screenshot2'])); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($slide_game['screenshot3'])); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($slide_game['screenshot4'])); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 20px;">
                                            <h5 class="text-white text-shadow mb-0"><?php echo htmlspecialchars($slide_game['title']); ?></h5>
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

                <?php if ($showcase_game): ?>
                <div class="card bg-dark border-secondary mb-4 overflow-hidden shadow position-relative">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars(resolve_url($showcase_game['cover_url'])); ?>" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 200px;" alt="Cover">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4 d-flex flex-column justify-content-center h-100 position-relative">
                                <span class="badge bg-accent text-dark position-absolute top-0 end-0 m-3 shadow-sm"><i class="fas fa-star"></i> Улюблена гра</span>
                                <h3 class="card-title text-white mb-3"><?php echo htmlspecialchars($showcase_game['title']); ?></h3>
                                <p class="card-text text-white-50 mb-4"><?php echo mb_substr(htmlspecialchars($showcase_game['description']), 0, 150) . '...'; ?></p>
                                <div><a href="../games/game_details.php?id=<?php echo $showcase_game['id']; ?>" class="btn btn-outline-success px-4 fw-bold">Перейти до гри</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="profile-content mb-4 shadow-sm border border-secondary"> 
                    <ul class="nav nav-tabs profile-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#games-panel"><i class="fas fa-gamepad"></i> <?php echo $is_developer ? 'Усі ігри' : 'Бібліотека'; ?> (<?php echo count($games_list); ?>)</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#friends-panel"><i class="fas fa-user-friends"></i> Друзі (<?php echo count($friends_list); ?>)</button>
                        </li>
                        <?php if ($is_developer): ?>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#follows-panel"><i class="fas fa-users"></i> Підписники (<?php echo count($followers_list); ?>)</button></li>
                        <?php else: ?>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#follows-panel"><i class="fas fa-building"></i> Улюблені студії (<?php echo count($followed_devs); ?>)</button></li>
                        <?php endif; ?>

                        <?php if (!$is_developer): ?>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#analytics-panel"><i class="fas fa-chart-pie"></i> Аналітика</button>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="games-panel">
                            <?php if ($is_developer): ?>
                                <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                                <?php foreach ($games_list as $game): ?>
                                    <div class="col">
                                        <div class="card h-100 game-card bg-dark-green border-secondary shadow-sm transition-hover">
                                            <a href="../games/game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none w-100 h-100 d-flex flex-column">
                                                <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="card-img-top" style="height: 140px; object-fit: cover; border-bottom: 1px solid #2a473a;" alt="Cover">
                                                <div class="card-body p-3">
                                                    <h6 class="text-white fw-bold text-truncate mb-1"><?php echo htmlspecialchars($game['title']); ?></h6>
                                                    <p class="text-accent small text-truncate mb-0"><?php echo htmlspecialchars($game['tags']); ?></p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php elseif (empty($games_list)): ?>
                                <div class="p-5 text-center">
                                    <h4 class="text-white-50"><i class="fas fa-folder-open fs-1 mb-3"></i><br>Колекція ігор порожня</h4>
                                    <?php if ($is_own_profile): ?><a href="../index.php" class="btn btn-outline-success mt-3 fw-bold">Перейти до каталогу</a><?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="row row-cols-1 row-cols-md-3 g-4">
                                    <?php foreach ($games_list as $game): $status_info = getStatusBadge($game['status'] ?? 'owned'); ?>
                                        <div class="col">
                                            <div class="card h-100 game-card bg-dark d-flex flex-column">
                                                <a href="../games/game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none flex-grow-1">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="card-img-top" alt="Cover" style="height:150px; object-fit:cover;">
                                                    <div class="card-body pb-2">
                                                        <h6 class="card-title text-white text-truncate mb-0"><?php echo htmlspecialchars($game['title']); ?></h6>
                                                    </div>
                                                </a>
                                                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                                    <?php if ($is_own_profile): ?>
                                                        <div class="dropdown w-100">
                                                            <button class="btn <?php echo $status_info['class']; ?> btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas <?php echo $status_info['icon']; ?>"></i> <?php echo $status_info['text']; ?>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-dark w-100 shadow p-1">
                                                                <li>
                                                                    <form method="POST" action="profile.php" class="m-0">
                                                                        <input type="hidden" name="update_status" value="1">
                                                                        <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">

                                                                        <button type="submit" name="status" value="playing" class="dropdown-item rounded mb-1"><i class="fas fa-gamepad text-success w-20px text-center me-2"></i> Граю зараз</button>
                                                                        <button type="submit" name="status" value="completed" class="dropdown-item rounded mb-1"><i class="fas fa-check-circle text-primary w-20px text-center me-2"></i> Пройдено</button>
                                                                        <button type="submit" name="status" value="planned" class="dropdown-item rounded mb-1"><i class="fas fa-calendar-alt text-warning w-20px text-center me-2"></i> В планах</button>
                                                                        <button type="submit" name="status" value="dropped" class="dropdown-item rounded mb-1"><i class="fas fa-ban text-danger w-20px text-center me-2"></i> Закинув</button>

                                                                        <hr class="dropdown-divider border-secondary my-1">

                                                                        <button type="submit" name="status" value="remove" class="dropdown-item rounded text-danger fw-bold" onclick="return confirm('Ви впевнені, що хочете назавжди видалити цю гру зі своєї бібліотеки?');">
                                                                            <i class="fas fa-trash-alt w-20px text-center me-2"></i> Видалити
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-100"><span class="badge <?php echo str_replace('btn-', 'bg-', $status_info['class']); ?> w-100 p-2"><i class="fas <?php echo $status_info['icon']; ?>"></i> <?php echo $status_info['text']; ?></span></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="friends-panel">
                            <?php if (empty($friends_list)): ?>
                                <div class="p-5 text-center"><h4 class="text-white-50"><i class="fas fa-user-slash fs-1 mb-3"></i><br>Список друзів порожній</h4></div>
                            <?php else: ?>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                                    <?php foreach ($friends_list as $friend): ?>
                                        <div class="col">
                                            <div class="card bg-dark border-secondary h-100 text-center p-3 user-card">
                                                <a href="profile.php?id=<?php echo $friend['id']; ?>" class="text-decoration-none">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($friend['avatar_url'] ?? 'img/avatars/default.png')); ?>" class="rounded-circle mb-3 border border-secondary" style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important;">
                                                    <h6 class="text-white mb-1"><?php echo htmlspecialchars($friend['username']); ?></h6>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="follows-panel">
                            <?php $display_list = $is_developer ? $followers_list : $followed_devs; if (empty($display_list)): ?>
                                <div class="p-5 text-center"><h4 class="text-white-50"><i class="fas <?php echo $is_developer ? 'fa-users' : 'fa-building'; ?> fs-1 mb-3"></i><br><?php echo $is_developer ? 'У вас поки немає підписників' : 'Ви ще не підписані на розробників'; ?></h4></div>
                            <?php else: ?>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                                    <?php foreach ($display_list as $user_item): ?>
                                        <div class="col">
                                            <div class="card bg-dark border-secondary h-100 text-center p-3 user-card">
                                                <a href="profile.php?id=<?php echo $user_item['id']; ?>" class="text-decoration-none">
                                                    <img src="<?php echo htmlspecialchars(resolve_url($user_item['avatar_url'] ?? 'img/avatars/default.png')); ?>" class="rounded-circle mb-3 border border-secondary" style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important;">
                                                    <h6 class="text-white mb-1"><?php echo htmlspecialchars($user_item['username']); ?></h6>
                                                    <small class="text-accent">
                                                        <?php if ($user_item['user_role'] === 'developer'): ?> <i class="fas fa-check-circle"></i> Розробник <?php endif; ?>
                                                    </small>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!$is_developer): ?>
                        <div class="tab-pane fade" id="analytics-panel">
                            <?php if(empty($games_list)): ?>
                                <div class="p-5 text-center"><h4 class="text-white-50"><i class="fas fa-chart-bar fs-1 mb-3"></i><br>Додайте ігри у бібліотеку, щоб побачити аналітику</h4></div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="bg-dark border border-secondary p-4 rounded shadow-sm text-center h-100">
                                            <h5 class="text-white mb-4"><i class="fas fa-chart-pie text-accent me-2"></i> Статус ігор</h5>
                                            <div style="position: relative; height: 250px; width: 100%;">
                                                <canvas id="statusChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="bg-dark border border-secondary p-4 rounded shadow-sm text-center h-100">
                                            <h5 class="text-white mb-4"><i class="fas fa-chart-bar text-accent me-2"></i> Улюблені жанри</h5>
                                            <div style="position: relative; height: 250px; width: 100%;">
                                                <canvas id="genreChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>

                <?php if ($is_developer): ?>
                    <h2 class="mb-4 text-white"><i class="fas fa-comments text-accent me-2"></i> Стіна розробника</h2>

                    <div class="profile-content mb-4 bg-dark border border-secondary">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="profile.php?id=<?php echo $profile_user_id; ?>" method="POST">
                                <div class="mb-3">
                                    <textarea class="form-control bg-dark-green text-white border-secondary" name="comment_text" rows="3" placeholder="Залиште повідомлення розробнику..." required></textarea>
                                </div>
                                <button type="submit" name="submit_dev_review" class="btn btn-success fw-bold px-4">Відправити</button>
                            </form>
                        <?php else: ?>
                            <p class="text-center lead mb-0"><a href="../auth/login.php" class="text-accent fw-bold text-decoration-none">Увійдіть</a>, щоб залишити повідомлення.</p>
                        <?php endif; ?>
                    </div>

                    <div class="game-review-list">
                        <?php if (empty($dev_reviews)): ?>
                            <div class="text-center p-4 border border-secondary rounded bg-dark">
                                <h5 class="text-white-50 mb-0">Повідомлень ще немає.</h5>
                            </div>
                        <?php else: ?>
                            <?php foreach ($dev_reviews as $review): ?>
                                <div class="game-review-card bg-dark-green border border-secondary shadow-sm mb-3">
                                    <div class="game-review-user-info-wrapper"> 
                                        <img src="<?php echo htmlspecialchars(resolve_url($review['avatar_url'] ?? 'img/avatars/default.png')); ?>" alt="Avatar" class="game-review-avatar" style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; border: 2px solid #2a473a;">
                                        <div class="game-review-user-text">
                                            <a href="profile.php?id=<?php echo $review['author_user_id']; ?>" class="game-review-username text-truncate d-block text-decoration-none text-white"><?php echo htmlspecialchars($review['username']); ?></a>
                                        </div>
                                    </div>
                                    <div class="game-review-body-content">
                                        <p class="text-light mb-0" style="font-size: 0.95rem; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>
                                        <div class="review-footer border-top border-secondary pt-3 mt-3 w-100 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50 small"><i class="far fa-clock me-1"></i> <?php echo date('d.m.Y \о H:i', strtotime($review['created_at'])); ?></span>
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['author_user_id']): ?>
                                                <form method="POST" action="profile.php?id=<?php echo $profile_user_id; ?>" class="m-0">
                                                    <input type="hidden" name="comment_id" value="<?php echo $review['id']; ?>">
                                                    <button type="submit" name="delete_dev_review" class="btn btn-sm btn-outline-danger py-0"><i class="fas fa-trash"></i> Видалити</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php if($is_visible && !$is_developer && !empty($games_list)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Граю зараз', 'Пройдено', 'В планах', 'Закинуто', 'Просто в колекції'],
            datasets: [{
                data: [
                    <?php echo $status_data['playing'] ?? 0; ?>, <?php echo $status_data['completed'] ?? 0; ?>, 
                    <?php echo $status_data['planned'] ?? 0; ?>, <?php echo $status_data['dropped'] ?? 0; ?>, 
                    <?php echo $status_data['owned'] ?? 0; ?>
                ],
                backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: '#d1d1d1', padding: 20, font: { size: 12 } } } } }
    });

    const ctxGenre = document.getElementById('genreChart').getContext('2d');
    new Chart(ctxGenre, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($top_genres)); ?>,
            datasets: [{
                label: 'Кількість ігор',
                data: <?php echo json_encode(array_values($top_genres)); ?>,
                backgroundColor: '<?php echo htmlspecialchars($profile_user['accent_color'] ?? '#00ff64'); ?>',
                borderRadius: 6
            }]
        },
        options: { 
            responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
            scales: { y: { ticks: { color: '#d1d1d1', stepSize: 1 }, grid: { color: '#2a473a' } }, x: { ticks: { color: '#d1d1d1' }, grid: { display: false } } }
        }
    });
});
</script>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>