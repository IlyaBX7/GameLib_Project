<?php
require_once 'includes/db_connect.php';
session_start(); 

$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($game_id <= 0) die("Помилка: Невірний ID гри.");

$message = ''; 
$review_message = ''; 

// Логіка додавання до бібліотеки
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_library'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
    $user_id = $_SESSION['user_id'];
    $stmt_check = $pdo->prepare("SELECT id FROM user_library WHERE user_id = ? AND game_id = ?");
    $stmt_check->execute([$user_id, $game_id]);
    if ($stmt_check->fetch()) { $message = '<div class="alert alert-warning">Ця гра вже є у вашій бібліотеці.</div>'; } 
    else {
        $stmt_add = $pdo->prepare("INSERT INTO user_library (user_id, game_id) VALUES (?, ?)");
        $stmt_add->execute([$user_id, $game_id]);
        $message = '<div class="alert alert-success">Гру додано!</div>';
    }
}

// Логіка відгуків
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
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

// Логіка видалення відгуку
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_game_review'])) {
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
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

// ОТРИМАННЯ ДАНИХ
$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$game) die("Гру не знайдено.");

$stmt_ach = $pdo->prepare("SELECT * FROM achievements WHERE game_id = ? LIMIT 5");
$stmt_ach->execute([$game_id]);
$achievements = $stmt_ach->fetchAll(PDO::FETCH_ASSOC);
$ach_count_stmt = $pdo->prepare("SELECT COUNT(*) FROM achievements WHERE game_id = ?");
$ach_count_stmt->execute([$game_id]);
$total_ach = $ach_count_stmt->fetchColumn();

$stmt_reviews = $pdo->prepare("SELECT r.*, u.username, u.avatar_url FROM game_reviews r JOIN users u ON r.user_id = u.id WHERE r.game_id = ? ORDER BY r.created_at DESC");
$stmt_reviews->execute([$game_id]);
$reviews = $stmt_reviews->fetchAll();

$pageTitle = htmlspecialchars($game['title']);
require_once 'includes/header.php';
?>

<div class="container content-section">
    <div class="row mt-4">

        <div class="col-lg-4">
            <div class="new-release-preview sticky-top" style="top: 90px; z-index: 1;">
                <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" class="img-fluid rounded mb-3" alt="Cover">
                <?php echo $message; ?>
                <form action="game_details.php?id=<?php echo $game_id; ?>" method="POST">
                    <button type="submit" name="add_to_library" class="btn btn-success btn-lg w-100 mb-3">+ Додати до бібліотеки</button>
                </form>
                
                <div class="bg-dark-green p-3 rounded mb-3 info-block">
                    <ul class="list-unstyled mb-0 small-text" style="color: #a0a0a0;">
                        <li class="mb-1">
                            <span style="color: #6c757d;">Розробник:</span>
                            <?php if (!empty($game['publisher_id'])): ?>
                                <a href="profile.php?id=<?php echo $game['publisher_id']; ?>" class="text-white fw-bold text-decoration-underline"><?php echo htmlspecialchars($game['developer']); ?></a>
                            <?php else: ?>
                                <span class="text-white fw-bold"><?php echo htmlspecialchars($game['developer']); ?></span>
                            <?php endif; ?>
                        </li>
                        <li class="mb-1"><span style="color: #6c757d;">Видавець:</span> <span class="text-white fw-bold"><?php echo htmlspecialchars($game['publisher']); ?></span></li>
                        <li><span style="color: #6c757d;">Дата виходу:</span> <span class="text-white fw-bold"><?php echo htmlspecialchars($game['release_date']); ?></span></li>
                    </ul>
                </div>

                <?php if (!empty($game['features'])): ?>
                <div class="game-features-list mb-3">
                    <?php 
                        $features = explode(',', $game['features']);
                        foreach($features as $feat): 
                            $feat = trim($feat);
                            $icon = 'fa-check'; // Іконка за замовчуванням
                            if ($feat == 'Одиночна гра') $icon = 'fa-user';
                            elseif ($feat == 'Багатокористувацька') $icon = 'fa-users';
                            elseif ($feat == 'Кооператив') $icon = 'fa-handshake';
                            elseif ($feat == 'Досягнення') $icon = 'fa-trophy';
                            elseif ($feat == 'Підтримка контролерів') $icon = 'fa-gamepad';
                            elseif ($feat == 'Хмарні збереження') $icon = 'fa-cloud';
                    ?>
                    <div class="feature-item">
                        <i class="fas <?php echo $icon; ?> text-accent me-2" style="width: 20px; text-align: center;"></i>
                        <span><?php echo $feat; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="languages-block mb-3">
                    <h6 class="text-white mb-2">Мови:</h6>
                    <table class="table table-dark table-sm table-borderless languages-table mb-0">
                        <thead><tr><th></th><th>Текст</th><th>Звук</th><th>Субт.</th></tr></thead>
                        <tbody>
                            <?php 
                                // Стандартний список мов, який ми перевіряємо
                                $all_langs = ['Українська', 'Англійська', 'Французька', 'Німецька', 'Іспанська'];
                                $game_langs = !empty($game['languages']) ? explode(',', $game['languages']) : [];
                                // Чистимо пробіли
                                $game_langs = array_map('trim', $game_langs);
                                
                                $visible_langs = array_slice($all_langs, 0, 5);
                            ?>
                            
                            <?php foreach($all_langs as $lang): ?>
                                <?php if (in_array($lang, $game_langs)): ?>
                                <tr>
                                    <td class="lang-name"><?php echo $lang; ?></td>
                                    <td class="text-accent">✔</td>
                                    <td class="text-muted"></td> 
                                    <td class="text-accent">✔</td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_ach > 0): ?>
                <div class="achievements-block mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-white mb-0">Досягнення: <?php echo $total_ach; ?></h6>
                    </div>
                    <div class="achievements-list-sidebar">
                        <?php foreach($achievements as $ach): ?>
                            <div class="ach-sidebar-item">
                                <img src="<?php echo htmlspecialchars($ach['icon_url']); ?>" alt="icon">
                                <span><?php echo htmlspecialchars($ach['title']); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <a href="game_achievements.php?id=<?php echo $game_id; ?>" class="view-all-link">Переглянути всі</a>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-lg-8">
            <h1 class="display-5 mb-3"><?php echo htmlspecialchars($game['title']); ?></h1>

            <div id="gameScreenshotCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <?php for($i=1; $i<=5; $i++): ?>
                    <div class="carousel-item <?php echo ($i==1)?'active':''; ?>">
                        <img src="<?php echo htmlspecialchars($game["screenshot$i"]); ?>" class="d-block w-100 carousel-img" alt="sc">
                    </div>
                    <?php endfor; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#gameScreenshotCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#gameScreenshotCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
            
            <div class="p-3 bg-dark-green rounded mb-4">
                <h3 class="text-accent">Про гру</h3>
                <p class="lead mb-0"><?php echo nl2br(htmlspecialchars($game['description'])); ?></p>
            </div>
            
            <h3 class="mb-3">Теги</h3>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <?php $tags = explode(',', $game['tags']); foreach ($tags as $tag): ?>
                    <span class="btn btn-outline-success disabled"><?php echo htmlspecialchars(trim($tag)); ?></span>
                <?php endforeach; ?>
            </div>

            <h3 class="mb-3">Системні вимоги</h3>
            <div class="bg-dark-green rounded p-4 mb-4">
                <div class="row">
                    <div class="col-md-6"><h5 class="text-accent">Мінімальні</h5><pre class="text-white-50"><?php echo htmlspecialchars($game['sys_min']); ?></pre></div>
                    <div class="col-md-6"><h5 class="text-accent">Рекомендовані</h5><pre class="text-white-50"><?php echo htmlspecialchars($game['sys_rec']); ?></pre></div>
                </div>
            </div>

            <h2 class="mb-4">Відгуки користувачів</h2>
            <div class="bg-dark-green rounded p-4 mb-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <h4 class="text-accent mb-3">Залишити відгук</h4>
                    <?php echo $review_message; ?>
                    <form action="game_details.php?id=<?php echo $game_id; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Ваша рекомендація:</label>
                            <div class="form-check"><input class="form-check-input" type="radio" name="is_recommended" id="rec_yes" value="1" checked><label class="form-check-label" for="rec_yes"><i class="fas fa-thumbs-up"></i> Рекомендую</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="is_recommended" id="rec_no" value="0"><label class="form-check-label" for="rec_no"><i class="fas fa-thumbs-down"></i> Не рекомендую</label></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Коментар:</label><textarea class="form-control" name="comment_text" rows="4" required></textarea></div>
                        <button type="submit" name="submit_review" class="btn btn-success">Відправити</button>
                    </form>
                <?php else: ?>
                    <p class="text-center lead"><a href="login.php" class="text-accent">Увійдіть</a>, щоб залишити відгук.</p>
                <?php endif; ?>
            </div>

            <div class="game-review-list">
                <?php if (empty($reviews)): ?><p>Відгуків ще немає.</p><?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="game-review-card">
                            <div class="game-review-user-info-wrapper"> 
                                <img src="<?php echo htmlspecialchars($review['avatar_url']); ?>" alt="Avatar" class="game-review-avatar">
                                <div class="game-review-user-text"><span class="game-review-username"><?php echo htmlspecialchars($review['username']); ?></span></div>
                            </div>
                            <div class="game-review-body-content">
                                <h5 class="game-review-rec <?php echo $review['is_recommended'] ? 'positive' : 'negative'; ?>">
                                    <i class="fas <?php echo $review['is_recommended'] ? 'fa-thumbs-up' : 'fa-thumbs-down'; ?>"></i> <?php echo $review['is_recommended'] ? 'Рекомендовано' : 'Не рекомендовано'; ?>
                                </h5>
                                <p><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>
                                <div class="review-footer">
                                    <small class="game-review-date-bottom">Додано: <?php echo date('d.m.Y \о H:i', strtotime($review['created_at'])); ?></small>
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                                        <form method="POST" action="game_details.php?id=<?php echo $game_id; ?>" class="delete-review-form">
                                            <input type="hidden" name="comment_id" value="<?php echo $review['id']; ?>">
                                            <button type="submit" name="delete_game_review" class="btn-delete-review"><i class="fas fa-trash"></i> Видалити</button>
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

<?php require_once 'includes/footer.php'; ?>