<?php
require_once 'includes/db_connect.php';
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT title, cover_url FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) die("Гру не знайдено.");

$stmt_ach = $pdo->prepare("SELECT * FROM achievements WHERE game_id = ?");
$stmt_ach->execute([$game_id]);
$achievements = $stmt_ach->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Досягнення - " . $game['title'];
require_once 'includes/header.php';
?>

<div class="container content-section">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php" class="text-decoration-none text-accent-light">Головна</a>
            </li>
            <li class="breadcrumb-item">
                <a href="game_details.php?id=<?php echo $game_id; ?>" class="text-decoration-none text-accent-light">
                    <?php echo htmlspecialchars($game['title']); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-white-50" aria-current="page">Досягнення</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between mb-4 p-3" style="background-color: #1b2838; border-radius: 4px;">
        <div>
            <h4 class="text-white mb-0">Глобальна статистика</h4> <h2 class="text-white mb-0"><?php echo htmlspecialchars($game['title']); ?></h2>
        </div>
        <img src="<?php echo htmlspecialchars($game['cover_url']); ?>" style="height: 60px; border-radius: 2px;">
    </div>

    <div class="achievements-list">
        <div class="d-flex justify-content-between text-white-50 mb-2 px-3">
            <small>Усього досягнень: <?php echo count($achievements); ?></small>
        </div>

        <?php foreach ($achievements as $ach): ?>
        <div class="achievement-row d-flex align-items-center mb-1 p-2">
            <img src="<?php echo htmlspecialchars($ach['icon_url']); ?>" class="achievement-icon" alt="icon">
            
            <div class="achievement-info flex-grow-1 ms-3">
                <h5 class="mb-1 text-white" style="font-size: 1rem; font-weight: bold;">
                    <?php echo htmlspecialchars($ach['title']); ?>
                </h5>
                <p class="mb-0" style="color: #b8b6b4; font-size: 0.9rem;">
                    <?php echo htmlspecialchars($ach['description']); ?>
                </p>
            </div>
            
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.achievement-row {
    background-color: #1b1b1b;
    border: 1px solid #333;
}
.achievement-icon {
    width: 64px;
    height: 64px;
    border-radius: 4px;
}
.text-accent-light {
    color: #85c0ef; 
}
.text-accent-light:hover {
    color: #ffffff;
    text-decoration: underline !important;
}
.breadcrumb-item+.breadcrumb-item::before {
    color: #666;
}
</style>

<?php require_once 'includes/footer.php'; ?>