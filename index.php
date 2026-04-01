<?php
session_start();
require_once 'includes/db_connect.php';

$stmt_hero = $pdo->prepare("SELECT * FROM games WHERE is_in_hero_slider = 1 AND is_approved = 1 ORDER BY id DESC LIMIT 4");
$stmt_hero->execute();
$hero_games = $stmt_hero->fetchAll(PDO::FETCH_ASSOC);

$stmt_news = $pdo->query("SELECT * FROM news_articles ORDER BY created_at DESC LIMIT 3");
$news_list = $stmt_news->fetchAll(PDO::FETCH_ASSOC);

$stmt_popular = $pdo->prepare("
    SELECT g.*, COUNT(ul.user_id) as collection_count
    FROM games g
    LEFT JOIN user_library ul ON g.id = ul.game_id
    WHERE g.is_approved = 1
    GROUP BY g.id
    ORDER BY collection_count DESC, g.id DESC
    LIMIT 4
");
$stmt_popular->execute();
$popular_games = $stmt_popular->fetchAll(PDO::FETCH_ASSOC);

$recommended_games = [];
$user_fav_genre = '';
$recommendation_title = "Рекомендації для вас";
$recommendation_subtitle = "Випадкові ігри з нашого каталогу";

if (isset($_SESSION['user_id'])) {
    $stmt_user = $pdo->prepare("SELECT favorite_genre FROM users WHERE id = ?");
    $stmt_user->execute([$_SESSION['user_id']]);
    $user_fav_genre = $stmt_user->fetchColumn();

    if (!empty($user_fav_genre)) {
        $stmt_rec = $pdo->prepare("SELECT * FROM games WHERE tags LIKE ? AND is_approved = 1 ORDER BY RAND() LIMIT 4");
        $stmt_rec->execute(['%' . trim($user_fav_genre) . '%']);
        $recommended_games = $stmt_rec->fetchAll(PDO::FETCH_ASSOC);

        if (count($recommended_games) > 0) {
            $recommendation_title = "Спеціально для вас";
            $recommendation_subtitle = "Оскільки ваш улюблений жанр: <strong class='text-accent'>" . htmlspecialchars($user_fav_genre) . "</strong>";
        }
    }
}

if (empty($recommended_games)) {
    $stmt_rec = $pdo->prepare("SELECT * FROM games WHERE is_approved = 1 ORDER BY RAND() LIMIT 4");
    $stmt_rec->execute();
    $recommended_games = $stmt_rec->fetchAll(PDO::FETCH_ASSOC);
}

$stmt_new = $pdo->query("SELECT * FROM games WHERE is_approved = 1 ORDER BY id DESC LIMIT 10");
$new_games = $stmt_new->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'GameLib - Головна сторінка';
require_once 'includes/header.php';
?>

<style>
    .carousel-item img {
        height: 550px !important; 
        width: 100% !important;
        object-fit: cover !important; 
        object-position: center 15% !important; 
    }
</style>

<?php if (!empty($hero_games)): ?>
<div class="container mt-4 mb-5">
    <div id="mainHeroCarousel" class="carousel slide hero-carousel shadow-lg" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 overflow-hidden">
            <?php foreach ($hero_games as $index => $game): ?>
            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                <a href="games/game_details.php?id=<?php echo $game['id']; ?>">
                    <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="d-block w-100 hero-img" alt="<?php echo htmlspecialchars($game['title']); ?>">
                    <div class="carousel-caption hero-caption d-none d-md-block text-start p-4">
                        <h2 class="fw-bold text-white mb-2" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);"><?php echo htmlspecialchars($game['title']); ?></h2>
                        <p class="text-light mb-0" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);"><?php echo htmlspecialchars($game['tags']); ?></p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Попередня</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Наступна</span>
        </button>
    </div>
</div>
<?php endif; ?>

<div class="container content-section">

    <?php if (!empty($news_list)): ?>
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-2">
            <h3 class="text-white mb-1"><i class="fas fa-newspaper text-accent me-2"></i> Новини та оновлення</h3>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($news_list as $news): ?>
                <div class="col">
                    <div class="card bg-dark border-secondary h-100 shadow game-card">
                        <a href="community/news_details.php?id=<?php echo $news['id']; ?>" class="text-decoration-none d-flex flex-column h-100">
                            <img src="<?php echo htmlspecialchars(resolve_url($news['image_url'])); ?>" class="card-img-top" style="height: 160px; object-fit: cover;" alt="News Cover">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-white mb-2"><?php echo htmlspecialchars($news['title']); ?></h5>
                                <p class="card-text text-white-50 small mb-3 flex-grow-1">
                                    <?php echo htmlspecialchars(mb_substr($news['content'], 0, 120, 'UTF-8')) . '...'; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="text-accent small"><i class="far fa-clock"></i> <?php echo date('d.m.Y', strtotime($news['created_at'])); ?></div>
                                    <span class="btn btn-sm btn-outline-success">Читати далі</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-2">
            <div>
                <h3 class="text-white mb-1"><i class="fas fa-fire text-danger me-2"></i> Вибір спільноти</h3>
                <p class="text-white-50 mb-0 small">Ігри, які найчастіше додають у бібліотеки наші гравці</p>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($popular_games as $game): ?>
                <div class="col">
                    <div class="card bg-dark border-secondary h-100 game-card shadow">
                        <a href="games/game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none">
                            <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Cover">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-white text-truncate mb-1"><?php echo htmlspecialchars($game['title']); ?></h5>
                                <p class="card-text text-accent small mb-3"><?php echo htmlspecialchars($game['tags']); ?></p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="text-white-50 small"><i class="fas fa-users"></i> В колекції: <?php echo $game['collection_count']; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-2">
            <div>
                <h3 class="text-white mb-1"><i class="fas fa-magic text-accent me-2"></i> <?php echo $recommendation_title; ?></h3>
                <p class="text-white-50 mb-0 small"><?php echo $recommendation_subtitle; ?></p>
            </div>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="auth/login.php" class="btn btn-outline-success btn-sm">Увійти для точних рекомендацій</a>
            <?php endif; ?>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($recommended_games as $game): ?>
                <div class="col">
                    <div class="card bg-dark border-secondary h-100 game-card shadow">
                        <a href="games/game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none">
                            <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Cover">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-white text-truncate mb-1"><?php echo htmlspecialchars($game['title']); ?></h5>
                                <p class="card-text text-white-50 small mb-0 text-truncate"><?php echo htmlspecialchars($game['developer']); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-secondary pb-2">
            <h3 class="text-white mb-1"><i class="fas fa-list-ul text-accent me-2"></i> Огляд жанрів</h3>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php 

            $genres = ['Екшн', 'Рольові ігри ', 'Шутер', 'Стратегія', 'Пригоди', 'Гонки', 'Симулятор', 'Спорт', 'Головоломка', 'Хоррор', 'Платформер', 'Файтинг'];
            foreach ($genres as $genre): 
            ?>
            <div class="col">
                <a href="games/genres.php?genre=<?php echo urlencode($genre); ?>" class="text-decoration-none">
                    <div class="card bg-dark border-secondary text-center h-100 genre-card py-3 shadow-sm">
                        <div class="card-body p-2">
                            <h6 class="card-title text-white mb-0 fw-bold"><?php echo $genre; ?></h6>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!empty($new_games)): ?>
    <div class="mb-5 pb-5">
        <h3 class="text-white mb-4">Популярні новинки</h3>
        <div class="row">
            <div class="col-lg-7">
                <div class="list-group list-group-flush custom-game-list rounded" style="background: var(--bg-dark-green);">
                    <?php foreach ($new_games as $index => $game): ?>
                        <a href="games/game_details.php?id=<?php echo $game['id']; ?>" 
                           class="list-group-item list-group-item-action d-flex align-items-center bg-transparent border-secondary hover-game-item"
                           data-cover="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>"
                           data-title="<?php echo htmlspecialchars($game['title']); ?>"
                           data-desc="<?php echo htmlspecialchars(mb_substr($game['description'], 0, 180, 'UTF-8')) . '...'; ?>"
                           data-link="game_details.php?id=<?php echo $game['id']; ?>"
                           style="border-bottom: 1px solid var(--border-color); padding: 12px 15px;">

                            <img src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" alt="Cover" style="width: 130px; height: 65px; object-fit: cover; border-radius: 4px;" class="me-3">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="mb-0 text-white text-truncate" style="font-size: 1.1rem;"><?php echo htmlspecialchars($game['title']); ?></h5>
                                <small class="text-white-50 text-truncate d-block mt-1"><?php echo htmlspecialchars($game['tags']); ?></small>
                            </div>
                            <i class="fas fa-chevron-right text-accent ms-2 opacity-50"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="card border-0 sticky-top p-3" style="top: 100px; background: var(--bg-light-green); border-radius: 8px;">
                    <div class="card-body p-0">
                        <h4 id="preview-title" class="text-accent mb-3"><?php echo htmlspecialchars($new_games[0]['title']); ?></h4>
                        <img id="preview-cover" src="<?php echo htmlspecialchars(resolve_url($new_games[0]['cover_url'])); ?>" class="img-fluid rounded mb-3 shadow w-100" alt="Preview" style="aspect-ratio: 16/9; object-fit: cover;">
                        <p id="preview-desc" class="text-white-50 small mb-4" style="line-height: 1.6;">
                            <?php echo htmlspecialchars(mb_substr($new_games[0]['description'], 0, 180, 'UTF-8')) . '...'; ?>
                        </p>
                        <a id="preview-link" href="games/game_details.php?id=<?php echo $new_games[0]['id']; ?>" class="btn btn-success px-4">На сторінку гри</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const listItems = document.querySelectorAll('.hover-game-item');
    const previewCover = document.getElementById('preview-cover');
    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');
    const previewLink = document.getElementById('preview-link');

    listItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            previewCover.src = this.getAttribute('data-cover');
            previewTitle.textContent = this.getAttribute('data-title');
            previewDesc.textContent = this.getAttribute('data-desc');
            previewLink.href = this.getAttribute('data-link');
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>