<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$news_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT n.*, u.username 
    FROM news_articles n 
    LEFT JOIN users u ON n.author_id = u.id 
    WHERE n.id = ?
");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    die("<h2 class='text-center text-white mt-5'>Помилка: Новину не знайдено.</h2>");
}

$pageTitle = htmlspecialchars($news['title']);
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <img src="<?php echo htmlspecialchars(resolve_url($news['image_url'])); ?>" class="img-fluid rounded-4 mb-4 shadow-lg w-100" style="max-height: 450px; object-fit: cover;" alt="Cover">

            <h1 class="text-white mb-3 fw-bold"><?php echo htmlspecialchars($news['title']); ?></h1>

            <div class="d-flex align-items-center text-white-50 mb-4 pb-3 border-bottom border-secondary">
                <span class="me-4"><i class="fas fa-user-edit text-accent me-1"></i> <?php echo htmlspecialchars($news['username'] ?? 'Адміністрація'); ?></span>
                <span><i class="far fa-clock text-accent me-1"></i> <?php echo date('d.m.Y \о H:i', strtotime($news['created_at'])); ?></span>
            </div>

            <div class="text-light" style="line-height: 1.8; font-size: 1.1rem;">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>

            <div class="mt-5 pt-3 border-top border-secondary text-center">
                <a href="../index.php" class="btn btn-outline-success px-4 py-2"><i class="fas fa-arrow-left me-2"></i> Повернутися на головну</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>