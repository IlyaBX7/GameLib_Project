<footer class="custom-footer mt-auto pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">

            <div class="col-lg-4 col-md-6">
                <a class="navbar-brand d-flex align-items-center mb-3 text-decoration-none" href="<?php echo BASE_URL; ?>index.php">
                    <img src="<?php echo BASE_URL; ?>img/GameLib_logo.png" alt="GameLib Logo" style="height: 35px; margin-right: 12px;"> 
                    <span class="fs-4 fw-bold text-white">GameLib</span>
                </a>
                <p class="text-white-50 small pe-lg-4" style="line-height: 1.6;">
                    Ваша персональна бібліотека ігор. Відстежуйте прогрес, знаходьте нових друзів, спілкуйтеся з розробниками та відкривайте для себе найкращі новинки ігрової індустрії.
                </p>
            </div>

            <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-4 fs-6 text-uppercase letter-spacing-1">Навігація</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>index.php" class="footer-link"><i class="fas fa-angle-right me-2 text-accent"></i> Головна сторінка</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>community/community.php" class="footer-link"><i class="fas fa-angle-right me-2 text-accent"></i> Спільнота гравців</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>games/search.php" class="footer-link"><i class="fas fa-angle-right me-2 text-accent"></i> Розширений пошук</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>auth/login.php" class="footer-link"><i class="fas fa-angle-right me-2 text-accent"></i> Увійти / Зареєструватися</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h5 class="text-white mb-4 fs-6 text-uppercase letter-spacing-1">Залишайтесь на зв'язку</h5>
                <div class="d-flex gap-3 mb-4">
                    <a href="https://discord.com/" target="_blank" class="social-btn"><i class="fab fa-discord"></i></a>
                    <a href="https://web.telegram.org/k/" target="_blank" class="social-btn"><i class="fab fa-telegram-plane"></i></a>
                    <a href="https://x.com/" target="_blank" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="https://store.steampowered.com/" target="_blank" class="social-btn"><i class="fab fa-steam"></i></a>
                </div>
                <p class="text-white-50 small mb-1"><i class="fas fa-envelope text-accent me-2"></i> support@gamelib.com</p>
            </div>

        </div>

        <hr class="border-secondary mt-4 mb-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white-50 small mb-0">&copy; <?php echo date('Y'); ?> GameLib. Усі права захищено.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <p class="text-white-50 small mb-0">Дипломний проєкт</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/main.js?v=<?php echo time(); ?>"></script>
<?php if (isset($page_js)): ?>
    <script src="<?php echo resolve_url($page_js); ?>"></script>
<?php endif; ?>
</body>
</html>