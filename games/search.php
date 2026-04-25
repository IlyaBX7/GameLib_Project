<?php
session_start();
require_once '../includes/db_connect.php';

$pageTitle = 'Пошук';
$search_query = '';
$results = [];

if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    if (!empty($search_query)) {
        $sql_query = "SELECT * FROM games WHERE (title LIKE ? OR tags LIKE ?) AND is_approved = 1";
        $search_param = "%" . $search_query . "%";
        try {
            $stmt = $pdo->prepare($sql_query);
            $stmt->execute([$search_param, $search_param]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $results = [];
        }
    }
}

// Handle AJAX request for live search
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    if (empty($search_query)) {
        echo '';
        exit;
    }
    ?>
    <h3 class="mb-4 text-white">
        Результати за запитом: <span class="text-accent">"<?php echo htmlspecialchars($search_query); ?>"</span>
    </h3>

    <div class="profile-content">
        <?php if (empty($results)): ?>
            <div class="text-center py-5">
                <h4 class="text-white-50"><i class="fas fa-search-minus fs-1 mb-3"></i><br>За вашим запитом нічого не знайдено.</h4>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($results as $game): ?>
                    <a href="game_details.php?id=<?php echo $game['id']; ?>" class="list-group-item game-list-item-horizontal">
                        <img class="game-list-img" src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" alt="Cover">
                        <div class="game-list-info">
                            <h5 class="game-list-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                            <p class="game-list-tags"><?php echo htmlspecialchars($game['tags']); ?></p>
                        </div>
                        <div class="game-list-date" style="color: var(--text-muted); font-size: 0.85rem;">
                            <i class="far fa-calendar-alt"></i> <?php echo htmlspecialchars($game['release_date']); ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    exit;
}

$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section">
    <h2 class="mb-4">Пошук ігор</h2>

    <div class="profile-content mb-4"> 
        <form action="search.php" method="GET" id="search-form">
            <div class="input-group input-group-lg border border-secondary rounded overflow-hidden shadow-sm">
                <input type="text" 
                       id="advanced-search-input"
                       class="form-control bg-dark text-white border-0" 
                       name="query" 
                       autocomplete="off"
                       placeholder="Введіть назву гри або жанр (наприклад: RPG)..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-success px-4" type="submit">
                    <i class="fas fa-search"></i> Знайти
                </button>
            </div>
        </form>
    </div>

    <div id="search-results-container">
        <?php if (isset($_GET['query']) && !empty($search_query)): ?>
            <h3 class="mb-4 text-white">
                Результати за запитом: <span class="text-accent">"<?php echo htmlspecialchars($search_query); ?>"</span>
            </h3>

            <div class="profile-content">
                <?php if (empty($results)): ?>
                    <div class="text-center py-5">
                        <h4 class="text-white-50"><i class="fas fa-search-minus fs-1 mb-3"></i><br>За вашим запитом нічого не знайдено.</h4>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($results as $game): ?>
                            <a href="game_details.php?id=<?php echo $game['id']; ?>" class="list-group-item game-list-item-horizontal">
                                <img class="game-list-img" src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" alt="Cover">
                                <div class="game-list-info">
                                    <h5 class="game-list-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                    <p class="game-list-tags"><?php echo htmlspecialchars($game['tags']); ?></p>
                                </div>
                                <div class="game-list-date" style="color: var(--text-muted); font-size: 0.85rem;">
                                    <i class="far fa-calendar-alt"></i> <?php echo htmlspecialchars($game['release_date']); ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('advanced-search-input');
    const resultsContainer = document.getElementById('search-results-container');
    let timeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            
            if (query.length === 0) {
                resultsContainer.innerHTML = '';
                // Оновлюємо URL, прибираючи параметр пошуку
                window.history.replaceState({}, document.title, window.location.pathname);
                return;
            }

            // Оновлюємо URL (без перезавантаження сторінки)
            window.history.replaceState({}, document.title, window.location.pathname + '?query=' + encodeURIComponent(query));

            timeout = setTimeout(() => {
                resultsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-accent" role="status"></div><div class="mt-2 text-white-50 small">Шукаємо...</div></div>';
                
                fetch(`search.php?query=${encodeURIComponent(query)}&ajax=1`)
                    .then(response => response.text())
                    .then(html => {
                        resultsContainer.innerHTML = html;
                    });
            }, 300);
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>