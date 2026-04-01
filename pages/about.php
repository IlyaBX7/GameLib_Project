<?php
$pageTitle = 'GameLib - Про нас';
$base_path = '../';
require_once '../includes/db_connect.php';
require_once '../includes/header.php';
?>

<div class="container content-section">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4 text-center">Про нас</h1>

            <div class="bg-dark-green p-5 rounded shadow-lg text-white">
                <div class="row align-items-center mb-5">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <img src="../img/GameLib_logo.png" alt="GameLib Logo" class="img-fluid" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <h3 class="text-accent mb-3">Ласкаво просимо до GameLib!</h3>
                        <p class="lead">GameLib — це ваш персональний простір для організації ігрового світу. Ми створили цю платформу з простою метою: надати геймерам зручний інструмент для каталогізації своїх ігор, відстеження прогресу та відкриття нових шедеврів.</p>
                        <p>Наш проект народився з пристрасті до відеігор та бажання об'єднати спільноту гравців та розробників.</p>
                    </div>
                </div>

                <hr class="border-secondary my-5">

                <h3 class="mb-4 text-center text-accent">Наша місія</h3>
                <div class="row text-center g-4">
                    <div class="col-md-4">
                        <div class="p-4 bg-black bg-opacity-25 rounded h-100">
                            <i class="fas fa-layer-group fa-3x text-accent mb-3"></i>
                            <h5>Організація</h5>
                            <p class="small text-white-50">Надати найкращий інструмент для створення та управління вашою цифровою бібліотекою ігор.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-black bg-opacity-25 rounded h-100">
                            <i class="fas fa-users fa-3x text-accent mb-3"></i>
                            <h5>Спільнота</h5>
                            <p class="small text-white-50">Об'єднати гравців та розробників, надаючи платформу для обміну відгуками та новинами.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-black bg-opacity-25 rounded h-100">
                            <i class="fas fa-search fa-3x text-accent mb-3"></i>
                            <h5>Відкриття</h5>
                            <p class="small text-white-50">Допомогти вам знайти вашу наступну улюблену гру серед тисяч доступних тайтлів.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
