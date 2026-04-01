<?php
$pageTitle = 'GameLib - Як ми працюємо';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/header.php';
?>

<div class="container content-section">
    <h1 class="mb-5 text-center">Як працює GameLib?</h1>

    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-dark-green border-light-green text-center p-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-accent text-dark rounded-circle" style="width: 50px; height: 50px; font-weight: bold; font-size: 1.5rem;">1</span>
                </div>
                <h4 class="text-white">Реєстрація</h4>
                <p class="text-white-50">Створіть свій безкоштовний обліковий запис за лічені хвилини. Ви можете бути звичайним гравцем або розробником ігор.</p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-dark-green border-light-green text-center p-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-accent text-dark rounded-circle" style="width: 50px; height: 50px; font-weight: bold; font-size: 1.5rem;">2</span>
                </div>
                <h4 class="text-white">Дізнавайтесь</h4>
                <p class="text-white-50">Переглядайте наш каталог ігор, шукайте новинки, читайте огляди та новини від розробників.</p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-dark-green border-light-green text-center p-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-accent text-dark rounded-circle" style="width: 50px; height: 50px; font-weight: bold; font-size: 1.5rem;">3</span>
                </div>
                <h4 class="text-white">Колекціонуйте</h4>
                <p class="text-white-50">Додавайте ігри до своєї бібліотеки, позначайте їх статус ("Граю", "Пройдено", "Закинуто") та ставте оцінки.</p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100 bg-dark-green border-light-green text-center p-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-accent text-dark rounded-circle" style="width: 50px; height: 50px; font-weight: bold; font-size: 1.5rem;">4</span>
                </div>
                <h4 class="text-white">Впливайте</h4>
                <p class="text-white-50">Пишіть відгуки, ставте рекомендації та допомагайте іншим гравцям обирати найкраще.</p>
            </div>
        </div>
    </div>

    <div class="mt-5 p-5 bg-dark-green rounded text-center">
        <h3 class="text-white mb-3">Ви розробник ігор?</h3>
        <p class="lead text-white-50 mb-4">Приєднуйтесь до нас як розробник, публікуйте свої ігри, новини та отримуйте прямий фідбек від аудиторії.</p>
        <a href="../register.php" class="btn btn-success btn-lg">Стати партнером</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
