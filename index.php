<?php
$pageTitle = 'GameLib - Головна';
require_once 'includes/db_connect.php'; 
require_once 'includes/header.php'; 

// 1. Запит для Hero-слайдера
$stmt_hero = $pdo->prepare("SELECT * FROM games WHERE is_in_hero_slider = 1 ORDER BY id DESC LIMIT 4");
$stmt_hero->execute();
$hero_games = $stmt_hero->fetchAll(PDO::FETCH_ASSOC);

// 2. Запит для Новин
$stmt_news = $pdo->prepare("SELECT n.*, u.username FROM news_articles n JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC LIMIT 3");
$stmt_news->execute();
$news_articles = $stmt_news->fetchAll(PDO::FETCH_ASSOC);

// 3. Запит для "Популярне в колекціях"
$stmt_popular = $pdo->prepare("
    SELECT g.id, g.title, g.cover_url, COUNT(ul.user_id) as collection_count
    FROM games g
    JOIN user_library ul ON g.id = ul.game_id
    GROUP BY g.id
    ORDER BY collection_count DESC
    LIMIT 4
");
$stmt_popular->execute();
$popular_games = $stmt_popular->fetchAll(PDO::FETCH_ASSOC);

// 4. Запит для "Популярні новинки"
$stmt_new = $pdo->prepare("SELECT * FROM games ORDER BY id DESC LIMIT 10");
$stmt_new->execute();
$new_games = $stmt_new->fetchAll(PDO::FETCH_ASSOC);
$first_game = $new_games[0] ?? null; 
?>

<div class="container"> 
    <section class="hero-carousel-container">
        <?php if (!empty($hero_games)): ?>
            <div id="heroNewsCarousel" class="carousel slide">
                <div class="carousel-inner">
                    <?php foreach ($hero_games as $index => $game): ?>
                        <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>" 
                             style="background-image: linear-gradient(to right, rgba(0,0,0,0.8) 20%, rgba(0,0,0,0)), url('<?php echo htmlspecialchars($game['cover_url']); ?>');">
                            <div class="hero-carousel-content">
                                <h1 class="display-4"><?php echo htmlspecialchars($game['title']); ?></h1>
                                <p class="lead"><?php echo htmlspecialchars(substr($game['description'], 0, 150)) . '...'; ?></p>
                                <a href="game_details.php?id=<?php echo $game['id']; ?>" class="btn btn-success btn-lg">Дізнатися більше</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-indicators hero-indicators">
                    <?php foreach ($hero_games as $index => $game): ?>
                        <button type="button" data-bs-target="#heroNewsCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo ($index == 0) ? 'active' : ''; ?>">
                            <div class="indicator-text">
                                <small><?php echo htmlspecialchars($game['tags']); ?></small>
                                <span><?php echo htmlspecialchars($game['title']); ?></span>
                            </div>
                            <div class="indicator-timer-bar"></div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="profile-content text-center p-5"><h4>Стрічка новин порожня.</h4></div>
        <?php endif; ?>
    </section>
</div>

<section class="content-section container">
    <h2 class="mb-4">Останні новини</h2>
    <div class="row g-4">
        <?php foreach ($news_articles as $news): ?>
            <div class="col-lg-4">
                <div class="card h-100 news-card" data-bs-toggle="modal" data-bs-target="#newsModal_<?php echo $news['id']; ?>">
                    <img src="<?php echo htmlspecialchars($news['image_url']); ?>" class="card-img-top news-card-img" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($news['content'], 0, 100)) . '...'; ?></p>
                    </div>
                    <div class="card-footer">
                        <small class="text-white-50">Автор: <?php echo htmlspecialchars($news['username']); ?> | <?php echo date('d.m.Y', strtotime($news['created_at'])); ?></small>
                    </div>
                </div>

                <div class="modal fade" id="newsModal_<?php echo $news['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content dark-theme-modal"> <div class="modal-header">
                                <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($news['title']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body">
                                <img src="<?php echo htmlspecialchars($news['image_url']); ?>" class="img-fluid mb-4 rounded shadow-sm" alt="News Image">
                                
                                <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($news['content']); ?></p>
                            </div>
                            
                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Опубліковано: <?php echo date('d.m.Y H:i', strtotime($news['created_at'])); ?></span>
                                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Закрити</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
                </div>
        <?php endforeach; ?>
        
        <?php if (empty($news_articles)): ?>
            <p>Новин ще немає.</p>
        <?php endif; ?>
    </div>
</section>

<section class="content-section container"> 
    <h2 class="mb-4">Популярне в колекціях</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php if (empty($popular_games)): ?>
            <p class="text-white-50">Поки що ніхто не додав ігри до колекцій.</p>
        <?php else: ?>
            <?php foreach ($popular_games as $game): ?>
                <div class="col">
                    <div class="card h-100 game-card">
                        <a href="game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none">
                            <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($game['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                <p class="card-text text-accent">
                                    <i class="fas fa-layer-group"></i> В <?php echo $game['collection_count']; ?> колекціях
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="content-section container"> 
    <h2 class="mb-4">Категорії</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <div class="col">
            <a href="genres.php?genre=Спорт" class="text-decoration-none">
                <div class="category-card" style="background-image: url('img/Categories/SPORTS_GAMES.jpg');"><h3>СПОРТИВНІ ІГРИ</h3></div>
            </a>
        </div>
        <div class="col">
            <a href="genres.php?genre=Файтинг" class="text-decoration-none">
                <div class="category-card" style="background-image: url('img/Categories/FIGHTING.jpg');"><h3>ФАЙТИНГ</h3></div>
            </a>
        </div>
        <div class="col">
            <a href="genres.php?genre=Гонки" class="text-decoration-none">
                <div class="category-card" style="background-image: url('img/Categories/RACES.jpg');"><h3>ГОНКИ</h3></div>
            </a>
        </div>
        <div class="col">
            <a href="genres.php?genre=Симулятор" class="text-decoration-none">
                <div class="category-card" style="background-image: url('img/Categories/SIMULATOR.jpg');"><h3>СИМУЛЯТОР</h3></div>
            </a>
        </div>
    </div>
</section>

<section class="content-section container popular-sections"> 
    <h2 class="mb-4">Популярні розділи</h2>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="#" class="btn btn-section-nav btn-lg">Нові надходження</a>
        <a href="#" class="btn btn-section-nav btn-lg">Найпопулярніші</a>
        <a href="genres.php" class="btn btn-section-nav btn-lg">Огляд жанрів</a>
        <a href="#" class="btn btn-section-nav btn-lg">Пошук за тегами</a>
    </div>
</section>

<section class="content-section container"> 
    <h2 class="mb-4">Популярні новинки</h2>
    
    <div class="row">
        <div class="col-lg-7">
            <div class="new-release-list">
                <?php foreach ($new_games as $index => $game): ?>
                    <a href="game_details.php?id=<?php echo $game['id']; ?>" class="release-item <?php echo ($index == 0) ? 'active' : ''; ?>" data-title="<?php echo htmlspecialchars($game['title']); ?>" data-img1="<?php echo htmlspecialchars($game['cover_url']); ?>" data-description="<?php echo htmlspecialchars(substr($game['description'], 0, 100)) . '...'; ?>" data-url="game_details.php?id=<?php echo $game['id']; ?>">
                        <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>"><div class="release-info"><h5><?php echo htmlspecialchars($game['title']); ?></h5><div class="tags"><?php echo htmlspecialchars($game['tags']); ?></div></div><div class="release-nav-arrow">&rsaquo;</div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
            <div class="new-release-preview sticky-top" style="top: 100px;">
                <?php if ($first_game): ?>
                    <h4 id="preview-title"><?php echo htmlspecialchars($first_game['title']); ?></h4>
                    <img id="preview-img1" src="<?php echo htmlspecialchars($first_game['cover_url']); ?>" class="img-fluid rounded mb-2" alt="<?php echo htmlspecialchars($first_game['title']); ?>">
                    <p id="preview-description" class="mt-2"><?php echo htmlspecialchars(substr($first_game['description'], 0, 100)) . '...'; ?></p>
                    <a href="game_details.php?id=<?php echo $first_game['id']; ?>" id="preview-button" class="btn btn-success mt-3">На сторінку гри</a>
                <?php else: ?>
                    <h4 id="preview-title">Поки що немає ігор</h4>
                    <p id="preview-description" class="mt-2">Каталог порожній.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="js/main.js?v=1.1"></script> 
<?php
require_once 'includes/footer.php';
?>