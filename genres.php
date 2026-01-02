<?php
$pageTitle = 'Огляд жанрів';
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

// 1. Отримуємо всі ігри з БД
$stmt = $pdo->prepare("SELECT * FROM games ORDER BY title ASC");
$stmt->execute();
$all_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Список жанрів
$genres = [
    'Екшн', 'Шутер', 'Пригоди', 'Рольова гра', 'Стратегія', 
    'Симулятор', 'Гонки', 'Спорт', 'Файтинг', 'Головоломка',
    'Хоррор', 'Виживання', 'Відкритий світ', 'Пісочниця', 
    'Кооператив', 'Багатокористувацька', 'Інді', 'Фантастика', 
    'Фентезі', 'Зомбі', 'Детектив', 'Метроїдванія', 
    'Платформер', 'Казуальна', 'Безкоштовна'
];
?>

<div class="container content-section">
    <h2 class="mb-4">Каталог ігор за жанрами</h2>
    
    <div class="row">
        
        <div class="col-lg-3 mb-4">
            <div class="genre-sidebar sticky-top" style="top: 100px;">
                <h5 class="text-white mb-3">Оберіть жанр:</h5>
                <div class="list-group" id="genreList">
                    <button class="list-group-item list-group-item-action genre-btn active" onclick="filterGames('all', this)">
                        <i class="fas fa-gamepad me-2"></i> Всі ігри
                    </button>
                    
                    <?php foreach ($genres as $genre): ?>
                        <button class="list-group-item list-group-item-action genre-btn" onclick="filterGames('<?php echo $genre; ?>', this)">
                            <?php echo $genre; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h4 class="mb-3 text-accent" id="currentGenreTitle">Всі ігри</h4>
            
            <div class="row row-cols-1 row-cols-md-3 g-4" id="gamesContainer">
                <?php if (empty($all_games)): ?>
                    <p>Ігор поки що немає.</p>
                <?php else: ?>
                    <?php foreach ($all_games as $game): ?>
                        <div class="col game-item" data-tags="<?php echo htmlspecialchars($game['tags']); ?>">
                            <div class="card h-100 game-card">
                                <a href="game_details.php?id=<?php echo $game['id']; ?>" class="text-decoration-none">
                                    <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($game['title']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                        <p class="card-text text-muted small mb-2">
                                            <?php echo htmlspecialchars($game['tags']); ?>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div id="noGamesMessage" class="text-center mt-5 d-none">
                <h3><i class="fas fa-search"></i></h3>
                <p>У цьому жанрі поки що немає ігор.</p>
            </div>
        </div>
        
    </div>
</div>

<script>
// Функція фільтрації (така ж, як і була)
function filterGames(selectedGenre, btnElement) {
    const buttons = document.querySelectorAll('.genre-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Якщо передали елемент кнопки - робимо його активним
    if (btnElement) {
        btnElement.classList.add('active');
    }

    const titleElement = document.getElementById('currentGenreTitle');
    titleElement.textContent = (selectedGenre === 'all') ? 'Всі ігри' : selectedGenre;

    const games = document.getElementsByClassName('game-item');
    let visibleCount = 0;

    for (let i = 0; i < games.length; i++) {
        const gameTags = games[i].getAttribute('data-tags');
        if (selectedGenre === 'all' || gameTags.includes(selectedGenre)) {
            games[i].classList.remove('d-none');
            visibleCount++;
        } else {
            games[i].classList.add('d-none');
        }
    }

    const noGamesMsg = document.getElementById('noGamesMessage');
    if (visibleCount === 0) {
        noGamesMsg.classList.remove('d-none');
    } else {
        noGamesMsg.classList.add('d-none');
    }
}

// === НОВИЙ КОД: Авто-вибір жанру з URL ===
document.addEventListener('DOMContentLoaded', function() {
    // 1. Читаємо параметр ?genre=... з адреси
    const urlParams = new URLSearchParams(window.location.search);
    const genreParam = urlParams.get('genre');

    if (genreParam) {
        // 2. Шукаємо кнопку з таким текстом
        const buttons = document.querySelectorAll('.genre-btn');
        buttons.forEach(btn => {
            // trim() прибирає зайві пробіли
            if (btn.innerText.trim() === genreParam) {
                // 3. Імітуємо клік по ній
                filterGames(genreParam, btn);
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>