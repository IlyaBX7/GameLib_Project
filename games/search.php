<?php
session_start();
require_once '../includes/db_connect.php';

$pageTitle = 'Пошук';
$search_query = '';
$results = [];

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {

    $search_query = trim($_GET['query']);

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

$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section">
    <h2 class="mb-4">Пошук ігор</h2>

    <div class="profile-content mb-4"> 
        <form action="search.php" method="GET">
            <div class="input-group input-group-lg">
                <input type="text" 
                       class="form-control" 
                       name="query" 
                       placeholder="Введіть назву гри або тег (наприклад: RPG)..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-search"></i> Знайти
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['query'])): ?>
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
                        <a href="game_details.php?id=<?php echo $game['id']; ?>" class="game-list-item-horizontal">
                            <img class="game-list-img" src="<?php echo htmlspecialchars(resolve_url($game['cover_url'])); ?>" alt="Cover">
                            <div class="game-list-info">
                                <h5 class="game-list-title"><?php echo htmlspecialchars($game['title']); ?></h5>
                                <p class="game-list-tags"><?php echo htmlspecialchars($game['tags']); ?></p>
                            </div>
                            <div class="game-list-date">
                                <?php echo htmlspecialchars($game['release_date']); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>