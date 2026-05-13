<?php
require_once '../includes/db_connect.php';
session_start(); 

$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($game_id <= 0) die("Помилка: Невірний ID гри.");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизовано']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    if ($_POST['ajax_action'] == 'manage_library') {
        $rating = !empty($_POST['rating']) ? (int)$_POST['rating'] : null;

        $stmt_check = $pdo->prepare("SELECT id FROM user_library WHERE user_id = ? AND game_id = ?");
        $stmt_check->execute([$user_id, $game_id]);

        if ($stmt_check->fetch()) {
            $stmt_update = $pdo->prepare("UPDATE user_library SET rating = ? WHERE user_id = ? AND game_id = ?");
            $stmt_update->execute([$rating, $user_id, $game_id]);
            $msg = 'Оцінку успішно оновлено!';
            $btn_text = '<i class="fas fa-sync-alt me-1"></i> Зберегти оцінку';
            $btn_class = 'btn-outline-success';
        } else {
            $stmt_add = $pdo->prepare("INSERT INTO user_library (user_id, game_id, rating) VALUES (?, ?, ?)");
            $stmt_add->execute([$user_id, $game_id, $rating]);
            $msg = 'Гру додано до бібліотеки!';
            $btn_text = '<i class="fas fa-sync-alt me-1"></i> Зберегти оцінку';
            $btn_class = 'btn-outline-success';
        }

        $stmt_rating = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(rating) as rating_count FROM user_library WHERE game_id = ? AND rating IS NOT NULL AND rating > 0");
        $stmt_rating->execute([$game_id]);
        $rating_data = $stmt_rating->fetch(PDO::FETCH_ASSOC);
        $avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : 0;

        echo json_encode([
            'status' => 'success', 
            'message' => $msg,
            'btn_text' => $btn_text,
            'btn_class' => $btn_class,
            'avg_rating' => $avg_rating,
            'rating_count' => $rating_data['rating_count']
        ]);
        exit;
    }

    if ($_POST['ajax_action'] == 'rate_review') {
        $review_id = (int)$_POST['review_id'];
        $is_helpful = (int)$_POST['is_helpful'];

        $stmt_check_like = $pdo->prepare("SELECT id FROM review_likes WHERE review_id = ? AND user_id = ?");
        $stmt_check_like->execute([$review_id, $user_id]);
        if ($stmt_check_like->fetch()) {
            $stmt_upd = $pdo->prepare("UPDATE review_likes SET is_helpful = ? WHERE review_id = ? AND user_id = ?");
            $stmt_upd->execute([$is_helpful, $review_id, $user_id]);
        } else {
            $stmt_ins = $pdo->prepare("INSERT INTO review_likes (review_id, user_id, is_helpful) VALUES (?, ?, ?)");
            $stmt_ins->execute([$review_id, $user_id, $is_helpful]);
        }

        $stmt_counts = $pdo->prepare("SELECT 
            (SELECT COUNT(*) FROM review_likes WHERE review_id = ? AND is_helpful = 1) as likes,
            (SELECT COUNT(*) FROM review_likes WHERE review_id = ? AND is_helpful = 0) as dislikes
        ");
        $stmt_counts->execute([$review_id, $review_id]);
        $counts = $stmt_counts->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'likes' => $counts['likes'], 
            'dislikes' => $counts['dislikes']
        ]);
        exit;
    }
}

$message = ''; 
$review_message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $user_id = $_SESSION['user_id'];
    $is_recommended = (int)$_POST['is_recommended']; 
    $comment_text = trim($_POST['comment_text']);
    if (empty($comment_text)) { $review_message = '<div class="alert alert-danger">Напишіть текст відгуку.</div>'; } 
    else {
        $stmt_check = $pdo->prepare("SELECT id FROM game_reviews WHERE user_id = ? AND game_id = ?");
        $stmt_check->execute([$user_id, $game_id]);
        if ($stmt_check->fetch()) {
            $stmt_update = $pdo->prepare("UPDATE game_reviews SET is_recommended = ?, comment_text = ? WHERE user_id = ? AND game_id = ?");
            $stmt_update->execute([$is_recommended, $comment_text, $user_id, $game_id]);
            $review_message = '<div class="alert alert-success">Відгук оновлено!</div>';
        } else {
            $stmt_insert = $pdo->prepare("INSERT INTO game_reviews (game_id, user_id, is_recommended, comment_text) VALUES (?, ?, ?, ?)");
            $stmt_insert->execute([$game_id, $user_id, $is_recommended, $comment_text]);
            $review_message = '<div class="alert alert-success">Дякуємо за відгук!</div>';
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_game_review'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
    $user_id = $_SESSION['user_id'];
    $comment_id = (int)$_POST['comment_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM game_reviews WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);
        $review_message = '<div class="alert alert-info">Ваш відгук видалено.</div>';
    } catch (PDOException $e) {
        $review_message = '<div class="alert alert-danger">Помилка: '. $e->getMessage() .'</div>';
    }
}

$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$game) die("Гру не знайдено.");

$similar_games = [];
if (!empty($game['tags'])) {

    $tags = array_filter(array_map('trim', explode(',', $game['tags'])));
    if (!empty($tags)) {
        $conditions = [];
        $params = [];
        foreach ($tags as $tag) {
            $conditions[] = "tags LIKE ?";
            $params[] = "%" . $tag . "%";
        }

        $sql = "SELECT id, title, cover_url, tags FROM games WHERE id != ? AND is_approved = 1 AND (" . implode(' OR ', $conditions) . ") ORDER BY RAND() LIMIT 4";
        array_unshift($params, $game_id);
        $stmt_sim = $pdo->prepare($sql);
        $stmt_sim->execute($params);
        $similar_games = $stmt_sim->fetchAll(PDO::FETCH_ASSOC);
    }
}

$stmt_rating = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(rating) as rating_count FROM user_library WHERE game_id = ? AND rating IS NOT NULL AND rating > 0");
$stmt_rating->execute([$game_id]);
$rating_data = $stmt_rating->fetch(PDO::FETCH_ASSOC);
$avg_rating = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : 0;
$rating_count = $rating_data['rating_count'];

$user_library_data = null;
$user_review_likes = [];
if (isset($_SESSION['user_id'])) {
    $stmt_lib = $pdo->prepare("SELECT id, status, rating FROM user_library WHERE user_id = ? AND game_id = ?");
    $stmt_lib->execute([$_SESSION['user_id'], $game_id]);
    $user_library_data = $stmt_lib->fetch(PDO::FETCH_ASSOC);

    $stmt_url = $pdo->prepare("SELECT review_id, is_helpful FROM review_likes WHERE user_id = ?");
    $stmt_url->execute([$_SESSION['user_id']]);
    foreach ($stmt_url->fetchAll() as $row) {
        $user_review_likes[$row['review_id']] = $row['is_helpful'];
    }
}

$stmt_ach = $pdo->prepare("SELECT * FROM achievements WHERE game_id = ? LIMIT 5");
$stmt_ach->execute([$game_id]);
$achievements = $stmt_ach->fetchAll(PDO::FETCH_ASSOC);
$ach_count_stmt = $pdo->prepare("SELECT COUNT(*) FROM achievements WHERE game_id = ?");
$ach_count_stmt->execute([$game_id]);
$total_ach = $ach_count_stmt->fetchColumn();

$stmt_reviews = $pdo->prepare("
    SELECT r.*, u.username, u.avatar_url,
        (SELECT COUNT(*) FROM review_likes rl WHERE rl.review_id = r.id AND rl.is_helpful = 1) as likes,
        (SELECT COUNT(*) FROM review_likes rl WHERE rl.review_id = r.id AND rl.is_helpful = 0) as dislikes
    FROM game_reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.game_id = ? 
    ORDER BY r.created_at DESC
");
$stmt_reviews->execute([$game_id]);
$reviews = $stmt_reviews->fetchAll();

$pageTitle = htmlspecialchars($game['title']);
$base_path = '../';
$page_css = $base_path . 'css/game_details.css';
$page_js = $base_path . 'js/game_details.js';
require_once '../includes/header.php';
?>

<div class="container content-section">
    <div class="row mt-4">

        <div class="col-lg-4">
            <div class="new-release-preview sticky-top" style="top: 90px; z-index: 1;">
                <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="img-fluid rounded mb-3 shadow-lg" alt="Cover" style="width: 100%; object-fit: cover;">

                <div class="bg-dark-green p-3 rounded mb-3 text-center border border-secondary shadow-sm">
                    <h6 class="text-white-50 mb-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Рейтинг спільноти</h6>
                    <div id="community-rating-container">
                        <?php if ($rating_count > 0): ?>
                            <h2 class="text-accent mb-0 fw-bold" id="comm-rating-val"><i class="fas fa-star"></i> <?php echo $avg_rating; ?><span class="fs-5 text-white-50">/10</span></h2>
                            <small class="text-white-50" id="comm-rating-count">На основі <?php echo $rating_count; ?> оцінок</small>
                        <?php else: ?>
                            <h5 class="text-white-50 mb-0 py-2" id="comm-rating-val">Ще немає оцінок</h5>
                            <small class="text-white-50 d-none" id="comm-rating-count"></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-dark-green p-3 rounded mb-3 border border-secondary shadow-sm">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div id="rating-msg"></div>
                        <form id="ajax-rating-form">
                            <label class="form-label text-white fw-bold mb-2 text-center w-100">
                                <i class="fas fa-layer-group text-accent me-1"></i> Ваша оцінка гри
                            </label>

                            <div class="star-rating-container">
                                <div class="star-rating">
                                    <?php for($i=10; $i>=1; $i--): ?>
                                        <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" 
                                            <?php echo ($user_library_data && $user_library_data['rating'] == $i) ? 'checked' : ''; ?>>
                                        <label for="star<?php echo $i; ?>" title="Оцінка: <?php echo $i; ?> з 10"><i class="fas fa-star"></i></label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <button type="submit" id="rating-btn" class="btn w-100 fw-bold <?php echo $user_library_data ? 'btn-outline-success' : 'btn-success'; ?>">
                                <?php if ($user_library_data): ?>
                                    <i class="fas fa-sync-alt me-1"></i> Зберегти оцінку
                                <?php else: ?>
                                    <i class="fas fa-plus me-1"></i> Додати до бібліотеки
                                <?php endif; ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-2">
                            <p class="text-white-50 small mb-3">Оцінювати та додавати ігри можуть лише зареєстровані гравці.</p>
                            <a href="../auth/login.php" class="btn btn-outline-success btn-sm w-100">Увійти в акаунт</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-dark-green p-3 rounded mb-3 info-block border border-secondary">
                    <ul class="list-unstyled mb-0 small-text" style="color: #a0a0a0;">
                        <li class="mb-1">
                            <span style="color: #6c757d;">Розробник:</span>
                            <?php if (!empty($game['publisher_id'])): ?>
                                <a href="../user/profile.php?id=<?php echo $game['publisher_id']; ?>" class="text-white fw-bold text-decoration-underline"><?php echo htmlspecialchars($game['developer']); ?></a>
                            <?php else: ?>
                                <span class="text-white fw-bold"><?php echo htmlspecialchars($game['developer']); ?></span>
                            <?php endif; ?>
                        </li>
                        <li class="mb-1"><span style="color: #6c757d;">Видавець:</span> <span class="text-white fw-bold"><?php echo htmlspecialchars($game['publisher']); ?></span></li>
                        <li><span style="color: #6c757d;">Дата виходу:</span> <span class="text-white fw-bold"><?php echo htmlspecialchars($game['release_date']); ?></span></li>
                    </ul>
                </div>

                <?php if (!empty($game['platforms'])): ?>
                <div class="mb-4">
                    <h5 class="text-accent mb-3"><i class="fas fa-laptop-house"></i> Платформи</h5>
                    <div class="game-features-list mb-3">
                        <?php 
                        $platforms = explode(',', $game['platforms']);
                        foreach ($platforms as $plat):
                            $plat = trim($plat);
                            if (!empty($plat)):
                                $icon = 'fa-desktop';
                                $lower_plat = strtolower($plat);
                                if (strpos($lower_plat, 'pc') !== false || strpos($lower_plat, 'windows') !== false) $icon = 'fab fa-windows';
                                elseif (strpos($lower_plat, 'playstation') !== false) $icon = 'fab fa-playstation';
                                elseif (strpos($lower_plat, 'xbox') !== false) $icon = 'fab fa-xbox';
                                elseif (strpos($lower_plat, 'nintendo') !== false || strpos($lower_plat, 'switch') !== false) $icon = 'fas fa-gamepad';
                                elseif (strpos($lower_plat, 'mac') !== false) $icon = 'fab fa-apple';
                                elseif (strpos($lower_plat, 'linux') !== false) $icon = 'fab fa-linux';
                                elseif (strpos($lower_plat, 'android') !== false) $icon = 'fab fa-android';
                                elseif (strpos($lower_plat, 'ios') !== false) $icon = 'fab fa-apple';
                        ?>
                        <a href="genres.php?platform=<?php echo urlencode($plat); ?>" class="feature-link-item">
                            <div class="feature-item">
                                <i class="<?php echo $icon; ?> text-accent me-2" style="width: 20px; text-align: center;"></i>
                                <span><?php echo htmlspecialchars($plat); ?></span>
                            </div>
                        </a>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($game['features'])): ?>
                <div class="mb-4">
                    <h5 class="text-accent mb-3"><i class="fas fa-list-ul"></i> Особливості</h5>
                    <div class="game-features-list mb-3">
                        <?php 
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
                            $features = explode(',', $game['features']);
                            foreach($features as $feat): 
                                $feat = trim($feat);
                                $icon = isset($feature_icons[$feat]) ? $feature_icons[$feat] : 'fas fa-check';
                        ?>
                        <a href="genres.php?feature=<?php echo urlencode($feat); ?>" class="feature-link-item">
                            <div class="feature-item">
                                <i class="<?php echo $icon; ?> text-accent me-2" style="width: 20px; text-align: center;"></i>
                                <span><?php echo htmlspecialchars($feat); ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5 class="text-accent mb-3"><i class="fas fa-language"></i> Підтримувані мови</h5>
                    <?php if (!empty($game['languages'])): ?>
                        <div class="game-features-list mb-3">
                            <?php 
                                $language_icons = [
                                    "Українська" => "🇺🇦",
                                    "Англійська" => "🇬🇧",
                                    "Французька" => "🇫🇷",
                                    "Німецька" => "🇩🇪",
                                    "Іспанська" => "🇪🇸"
                                ];
                                $langs = explode(',', $game['languages']);
                                foreach ($langs as $lang): 
                                    $lang = trim($lang);
                                    if (!empty($lang)):
                                        $formatted_lang = mb_convert_case($lang, MB_CASE_TITLE, "UTF-8");
                                        $emoji = isset($language_icons[$formatted_lang]) ? $language_icons[$formatted_lang] : '🌐';
                            ?>
                            <a href="genres.php?language=<?php echo urlencode($formatted_lang); ?>" class="feature-link-item">
                                <div class="feature-item">
                                    <span class="me-2 text-center" style="display:inline-block; width: 20px; font-size: 1.1rem;"><?php echo $emoji; ?></span>
                                    <span><?php echo htmlspecialchars($formatted_lang); ?></span>
                                </div>
                            </a>
                            <?php 
                                    endif;
                                endforeach; 
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="game-features-list mb-3">
                            <div class="feature-item">
                                <span class="text-white-50">Інформація відсутня</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($total_ach > 0): ?>
                <div class="achievements-block mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-white mb-0">Досягнення: <?php echo $total_ach; ?></h6>
                    </div>
                    <div class="achievements-list-sidebar">
                        <?php foreach($achievements as $ach): ?>
                            <div class="ach-sidebar-item">
                                <img src="<?php echo htmlspecialchars(resolve_url($ach['icon_url'])); ?>" alt="icon">
                                <span><?php echo htmlspecialchars($ach['title']); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <a href="game_achievements.php?id=<?php echo $game_id; ?>" class="text-accent text-center d-block mt-3 text-decoration-none" style="font-size: 0.9rem;">Переглянути всі <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-lg-8">
            <h1 class="display-5 mb-3 fw-bold text-white"><?php echo htmlspecialchars($game['title']); ?></h1>

            <div id="gameScreenshotCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded border border-secondary shadow-lg">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <?php if (!empty($game["screenshot$i"])): ?>
                            <div class="carousel-item <?php echo ($i==1)?'active':''; ?>">
                                <img src="<?php echo htmlspecialchars(resolve_url($game["screenshot$i"])); ?>" class="d-block w-100 carousel-img" alt="Screenshot" style="aspect-ratio: 16/9; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#gameScreenshotCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#gameScreenshotCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>

            <div class="p-4 bg-dark-green rounded mb-4 border border-secondary shadow-sm">
                <h3 class="text-accent mb-3"><i class="fas fa-info-circle me-2"></i> Про гру</h3>
                <p class="mb-0 text-light" style="line-height: 1.7; font-size: 1.05rem;"><?php echo nl2br(htmlspecialchars($game['description'])); ?></p>
            </div>

            <div class="mb-4">
                <h5 class="text-white-50 mb-3"><i class="fas fa-tags text-accent me-2"></i>Теги:</h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php 
                    if (!empty($game['tags'])) {
                        $tags_array = explode(',', $game['tags']);
                        foreach ($tags_array as $tag) {
                            $tag = trim($tag);
                            if (!empty($tag)) {
                                echo '<a href="genres.php?genre=' . urlencode($tag) . '" class="badge bg-light-green border border-secondary text-accent text-decoration-none p-2 genre-card" style="font-size: 0.9rem; transition: all 0.2s;">' . htmlspecialchars($tag) . '</a>';
                            }
                        }
                    } else {
                        echo '<span class="text-white-50 small">Теги відсутні</span>';
                    }
                    ?>
                </div>
            </div>

            <h3 class="mb-3 text-white"><i class="fas fa-desktop text-accent me-2"></i> Системні вимоги</h3>
            <div class="bg-dark-green rounded p-4 mb-4 border border-secondary shadow-sm">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="text-accent mb-3">Мінімальні</h5>
                        <div class="text-white-50 small" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($game['sys_min'] ?? '')); ?></div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-accent mb-3">Рекомендовані</h5>
                        <div class="text-white-50 small" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($game['sys_rec'] ?? '')); ?></div>
                    </div>
                </div>
            </div>

            <div class="mb-5 bg-dark-green p-4 rounded border border-secondary shadow-sm">
                <h4 class="text-warning mb-3"><i class="fas fa-tags me-2"></i> Де купити дешевше? (Live Ціни)</h4>
                <div id="cheapshark-tracker">
                    <div class="text-center text-white-50 my-3">
                        <div class="spinner-border text-warning spinner-border-sm me-2" role="status"></div>
                        Шукаємо найкращі пропозиції в магазинах...
                    </div>
                </div>
            </div>

            <?php if (!empty($similar_games)): ?>
            <h3 class="mb-3 text-white"><i class="fas fa-clone text-accent me-2"></i> Схожі ігри</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-5">
                <?php foreach ($similar_games as $sim_game): ?>
                    <div class="col">
                        <div class="card h-100 game-card border-secondary shadow-sm transition-hover">
                            <a href="game_details.php?id=<?php echo $sim_game['id']; ?>" class="text-decoration-none">
                                <img src="<?php echo htmlspecialchars(resolve_url($sim_game['cover_url'])); ?>" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Cover">
                                <div class="card-body p-2">
                                    <h6 class="card-title text-white text-truncate mb-1" style="font-size: 0.95rem;"><?php echo htmlspecialchars($sim_game['title']); ?></h6>
                                    <p class="card-text text-accent mb-0 text-truncate" style="font-size: 0.75rem;"><?php echo htmlspecialchars($sim_game['tags']); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <h2 class="mb-4 text-white"><i class="fas fa-comments text-accent me-2"></i> Відгуки користувачів</h2>
            <div class="bg-dark-green rounded p-4 mb-4 border border-secondary shadow-sm">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <h4 class="text-accent mb-3">Залишити відгук</h4>
                    <?php echo $review_message; ?>
                    <form action="game_details.php?id=<?php echo $game_id; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-white">Ваша рекомендація:</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_recommended" id="rec_yes" value="1" checked>
                                    <label class="form-check-label text-success" for="rec_yes"><i class="fas fa-thumbs-up"></i> Рекомендую</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_recommended" id="rec_no" value="0">
                                    <label class="form-check-label text-danger" for="rec_no"><i class="fas fa-thumbs-down"></i> Не рекомендую</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control bg-dark text-white border-secondary" name="comment_text" rows="4" placeholder="Напишіть, що ви думаєте про гру..." required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn btn-success fw-bold px-4">Відправити відгук</button>
                    </form>
                <?php else: ?>
                    <p class="text-center lead mb-0"><a href="../auth/login.php" class="text-accent fw-bold text-decoration-none">Увійдіть</a>, щоб залишити відгук.</p>
                <?php endif; ?>
            </div>

            <div class="game-review-list">
                <?php if (empty($reviews)): ?>
                    <div class="text-center p-5 border border-secondary rounded bg-dark-green shadow-sm">
                        <h4 class="text-white-50 mb-0"><i class="far fa-comment-dots fs-2 mb-3 d-block"></i>Відгуків ще немає. Будьте першим!</h4>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="game-review-card bg-dark-green border border-secondary shadow-sm">
                            <div class="game-review-user-info-wrapper"> 
                                <img src="<?php echo htmlspecialchars(resolve_url($review['avatar_url'] ?? 'img/avatars/default.png')); ?>" alt="Avatar" class="game-review-avatar">
                                <div class="game-review-user-text"><span class="game-review-username text-truncate d-block"><?php echo htmlspecialchars($review['username']); ?></span></div>
                            </div>
                            <div class="game-review-body-content">
                                <h6 class="mb-3 <?php echo $review['is_recommended'] ? 'text-success' : 'text-danger'; ?> fw-bold">
                                    <i class="fas <?php echo $review['is_recommended'] ? 'fa-thumbs-up' : 'fa-thumbs-down'; ?> me-1"></i> 
                                    <?php echo $review['is_recommended'] ? 'Рекомендує' : 'Не рекомендує'; ?>
                                </h6>
                                <p class="text-light"><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>

                                <div class="review-footer border-top border-secondary pt-3 mt-3 w-100 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <small class="text-white-50"><i class="far fa-clock me-1"></i><?php echo date('d.m.Y', strtotime($review['created_at'])); ?></small>

                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $review['user_id']): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-white-50 small d-none d-sm-inline">Корисно?</span>

                                                <button type="button" class="btn btn-sm py-0 px-2 ajax-rate-btn btn-like <?php echo (isset($user_review_likes[$review['id']]) && $user_review_likes[$review['id']] == 1) ? 'btn-success' : 'btn-outline-success'; ?>" data-review-id="<?php echo $review['id']; ?>" data-helpful="1">
                                                    <i class="fas fa-thumbs-up"></i> <span class="like-count"><?php echo $review['likes']; ?></span>
                                                </button>

                                                <button type="button" class="btn btn-sm py-0 px-2 ajax-rate-btn btn-dislike <?php echo (isset($user_review_likes[$review['id']]) && $user_review_likes[$review['id']] == 0) ? 'btn-danger' : 'btn-outline-danger'; ?>" data-review-id="<?php echo $review['id']; ?>" data-helpful="0">
                                                    <i class="fas fa-thumbs-down"></i> <span class="dislike-count"><?php echo $review['dislikes']; ?></span>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-dark border border-secondary text-success"><i class="fas fa-thumbs-up"></i> <?php echo $review['likes']; ?></span>
                                                <span class="badge bg-dark border border-secondary text-danger"><i class="fas fa-thumbs-down"></i> <?php echo $review['dislikes']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                                        <form method="POST" action="game_details.php?id=<?php echo $game_id; ?>" class="delete-review-form m-0 mt-2 mt-sm-0">
                                            <input type="hidden" name="comment_id" value="<?php echo $review['id']; ?>">
                                            <button type="submit" name="delete_game_review" class="btn btn-sm btn-outline-danger py-0"><i class="fas fa-trash"></i> Видалити</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    window.GameDetailsData = {
        gameId: <?php echo json_encode($game_id); ?>,
        gameTitle: <?php echo json_encode($game['title'] ?? ''); ?>
    };
</script>

<?php require_once '../includes/footer.php'; ?>