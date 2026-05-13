<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_filter'])) {

    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    $genres = isset($_POST['genres']) ? $_POST['genres'] : [];
    $features = isset($_POST['features']) ? $_POST['features'] : [];
    $languages = isset($_POST['languages']) ? $_POST['languages'] : [];
    $platforms = isset($_POST['platforms']) ? $_POST['platforms'] : [];

    $where_sql = "is_approved = 1";
    $params = [];

    if (!empty($search)) {
        $where_sql .= " AND title LIKE ?";
        $params[] = '%' . $search . '%';
    }

    if (!empty($genres)) {
        $g_conds = [];
        foreach ($genres as $g) {
            $g_conds[] = "tags LIKE ?";
            $params[] = '%' . trim($g) . '%';
        }
        $where_sql .= " AND (" . implode(" OR ", $g_conds) . ")";
    }

    if (!empty($features)) {
        $f_conds = [];
        foreach ($features as $f) {
            $f_conds[] = "features LIKE ?";
            $params[] = '%' . trim($f) . '%';
        }
        $where_sql .= " AND (" . implode(" AND ", $f_conds) . ")";
    }

    if (!empty($languages)) {
        $l_conds = [];
        foreach ($languages as $l) {
            $l_conds[] = "languages LIKE ?";
            $params[] = '%' . trim($l) . '%';
        }
        $where_sql .= " AND (" . implode(" AND ", $l_conds) . ")";
    }

    if (!empty($platforms)) {
        $p_conds = [];
        foreach ($platforms as $p) {
            $p_conds[] = "platforms LIKE ?";
            $params[] = '%' . trim($p) . '%';
        }
        $where_sql .= " AND (" . implode(" OR ", $p_conds) . ")";
    }

    $sort = $_POST['sort'] ?? 'newest';
    $order_sql = "id DESC";
    if ($sort === 'title_asc') $order_sql = "title ASC";
    if ($sort === 'title_desc') $order_sql = "title DESC";

    $limit = 12;
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM games WHERE $where_sql");
    $stmt_count->execute($params);
    $total_games = $stmt_count->fetchColumn();
    $total_pages = ceil($total_games / $limit);

    $stmt_games = $pdo->prepare("SELECT id, title, cover_url, tags FROM games WHERE $where_sql ORDER BY $order_sql LIMIT $limit OFFSET $offset");
    $stmt_games->execute($params);
    $games = $stmt_games->fetchAll(PDO::FETCH_ASSOC);

    if (empty($games)) {
        echo '<div class="col-12 text-center py-5 w-100"><h3 class="text-white-50"><i class="fas fa-ghost mb-3 fs-1 d-block"></i>Ігор не знайдено</h3><p class="text-white-50">Спробуйте змінити критерії пошуку.</p></div>';
    } else {
        echo '<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-4">';
        foreach ($games as $game) {
            $cover_path = str_starts_with($game['cover_url'], 'http') ? $game['cover_url'] : '../' . ltrim($game['cover_url'], '/');
            echo '
            <div class="col">
                <div class="card h-100 game-card shadow-sm border-secondary bg-dark transition-hover">
                    <a href="game_details.php?id=' . $game['id'] . '" class="text-decoration-none">
                        <img src="' . htmlspecialchars($cover_path) . '" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Cover" onerror="this.src=\'../img/GameLib_logo.png\'">
                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title text-white text-truncate mb-1">' . htmlspecialchars($game['title']) . '</h5>
                            <p class="card-text text-accent small mb-0 text-truncate">' . htmlspecialchars($game['tags']) . '</p>
                        </div>
                    </a>
                </div>
            </div>';
        }
        echo '</div>';

        if ($total_pages > 1) {
            echo '<nav aria-label="Page navigation" class="mt-4"><ul class="pagination justify-content-center">';
            echo '<li class="page-item ' . ($page <= 1 ? 'disabled' : '') . '"><a class="page-link ajax-page bg-dark text-white border-secondary" href="#" data-page="' . ($page - 1) . '">Попередня</a></li>';
            for ($i = 1; $i <= $total_pages; $i++) {
                $activeClass = ($page == $i) ? 'bg-success border-success text-dark fw-bold' : 'bg-dark text-white border-secondary';
                $parentClass = ($page == $i) ? 'active' : '';
                echo '<li class="page-item ' . $parentClass . '"><a class="page-link ajax-page ' . $activeClass . '" href="#" data-page="' . $i . '">' . $i . '</a></li>';
            }
            echo '<li class="page-item ' . ($page >= $total_pages ? 'disabled' : '') . '"><a class="page-link ajax-page bg-dark text-white border-secondary" href="#" data-page="' . ($page + 1) . '">Наступна</a></li>';
            echo '</ul></nav>';
        }
    }
    exit; 
}

$pageTitle = 'Каталог ігор';
$base_path = '../';
$page_css = $base_path . 'css/genres.css';
$page_js = $base_path . 'js/genres.js';

$url_genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';
$url_feature = isset($_GET['feature']) ? trim($_GET['feature']) : '';
$url_language = isset($_GET['language']) ? trim($_GET['language']) : '';
$url_platform = isset($_GET['platform']) ? trim($_GET['platform']) : '';

require_once '../includes/header.php';
?>

<div class="container content-section mt-4">
    <div class="row">

        <div class="col-lg-3 mb-4">
            <div class="bg-dark p-4 rounded border border-secondary shadow-sm sticky-top" style="top: 100px; max-height: 85vh; overflow-y: auto;">
                <h5 class="text-white mb-4"><i class="fas fa-filter text-success me-2"></i> Розумний фільтр</h5>

                <form id="ajax-filter-form">
                    <input type="hidden" name="ajax_filter" value="1">
                    <input type="hidden" name="page" id="filter-page" value="1">

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-dark-green border-secondary text-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="filter-search" name="search" class="form-control bg-dark-green text-white border-secondary" placeholder="Пошук за назвою...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success mb-2 small text-uppercase fw-bold">Сортування</h6>
                        <select name="sort" id="filter-sort" class="form-select bg-dark-green text-white border-secondary">
                            <option value="newest">Спочатку нові</option>
                            <option value="title_asc">За алфавітом (А-Я)</option>
                            <option value="title_desc">За алфавітом (Я-А)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success mb-2 small text-uppercase fw-bold">Жанри</h6>
                        <div class="bg-dark-green p-3 rounded border border-secondary filter-scrollable" style="max-height: 200px; overflow-y: auto;">
                            <?php 
                            $genres = ['Екшен', 'Рольові ігри', 'РПГ', 'Шутер', 'Стратегія', 'Пригоди', 'Гонки', 'Симулятор', 'Спорт', 'Головоломка', 'Хоррор', 'Жахи', 'Платформер', 'Файтинг', 'Виживання', 'Відкритий світ', 'Пісочниця', 'Інді'];
                            if (!empty($url_genre) && !in_array($url_genre, $genres)) array_unshift($genres, $url_genre);
                            foreach ($genres as $index => $genre): 
                                $is_checked = (mb_strtolower($genre, 'UTF-8') === mb_strtolower($url_genre, 'UTF-8')) ? 'checked' : '';
                            ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox bg-dark border-secondary" type="checkbox" name="genres[]" value="<?php echo htmlspecialchars($genre); ?>" id="g<?php echo $index; ?>" <?php echo $is_checked; ?>>
                                    <label class="form-check-label text-white-50" for="g<?php echo $index; ?>"><?php echo htmlspecialchars($genre); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success mb-2 small text-uppercase fw-bold">Платформи</h6>
                        <div class="bg-dark-green p-3 rounded border border-secondary">
                            <?php 
                            $platforms_list = ['PC (Windows)', 'PlayStation', 'Xbox', 'Nintendo Switch', 'Mac', 'Linux'];
                            if (!empty($url_platform) && !in_array($url_platform, $platforms_list)) array_unshift($platforms_list, $url_platform);
                            foreach ($platforms_list as $index => $platform): 
                                $is_checked = (mb_strtolower($platform, 'UTF-8') === mb_strtolower($url_platform, 'UTF-8')) ? 'checked' : '';
                            ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox bg-dark border-secondary" type="checkbox" name="platforms[]" value="<?php echo htmlspecialchars($platform); ?>" id="p<?php echo $index; ?>" <?php echo $is_checked; ?>>
                                    <label class="form-check-label text-white-50" for="p<?php echo $index; ?>"><?php echo htmlspecialchars($platform); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success mb-2 small text-uppercase fw-bold">Особливості</h6>
                        <div class="bg-dark-green p-3 rounded border border-secondary filter-scrollable" style="max-height: 200px; overflow-y: auto;">
                            <?php 
                            $feature_icons = [
                                "Однокористувацька гра" => "fas fa-user",
                                "Багатокористувацька гра" => "fas fa-users",
                                "Гравець проти гравця" => "fas fa-crosshairs",
                                "Гравець проти гравця в мережі" => "fas fa-globe",
                                "Гравець проти гравця в локальній мережі" => "fas fa-ethernet",
                                "Кооперативна гра" => "fas fa-hands-helping",
                                "Мережева кооперативна гра" => "fas fa-user-friends",
                                "Локальна кооперативна гра" => "fas fa-users-cog",
                                "Спільний/розділений екран" => "fas fa-columns",
                                "Міжплатформна багатокористувацька гра" => "fas fa-random",
                                "Додаткове високоякісне аудіо" => "fas fa-headphones-alt",
                                "Підтримка відстежуваних контролерів" => "fas fa-vr-cardboard",
                                "З субтитрами" => "fas fa-closed-captioning",
                                "Голосовий чат" => "fas fa-microphone",
                                "Регульована складність" => "fas fa-sliders-h",
                                "Збереження будь-коли" => "fas fa-save",
                                "Об’ємний звук" => "fas fa-broadcast-tower",
                                "З підтримкою HDR" => "fas fa-tv",
                                "Повна підтримка контролерів" => "fas fa-gamepad",
                                "Підтримка контролерів Xbox" => "fab fa-xbox",
                                "Підтримка контролерів DualSense" => "fab fa-playstation",
                                "Стереозвук" => "fas fa-headphones",
                                "У власному темпі" => "fas fa-walking"
                            ];
                            $features_list = array_keys($feature_icons);
                            if (!empty($url_feature) && !in_array($url_feature, $features_list)) {
                                array_unshift($features_list, $url_feature);
                            }
                            foreach ($features_list as $index => $feature): 
                                $is_checked = (mb_strtolower($feature, 'UTF-8') === mb_strtolower($url_feature, 'UTF-8')) ? 'checked' : '';
                                $icon = isset($feature_icons[$feature]) ? $feature_icons[$feature] : 'fas fa-check';
                            ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox bg-dark border-secondary" type="checkbox" name="features[]" value="<?php echo htmlspecialchars($feature); ?>" id="f<?php echo $index; ?>" <?php echo $is_checked; ?>>
                                    <label class="form-check-label text-white-50" for="f<?php echo $index; ?>"><i class="<?php echo $icon; ?> me-1"></i> <?php echo htmlspecialchars($feature); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-success mb-2 small text-uppercase fw-bold">Мови</h6>
                        <div class="bg-dark-green p-3 rounded border border-secondary filter-scrollable" style="max-height: 150px; overflow-y: auto;">
                            <?php 
                            $language_icons = [
                                "Українська" => "🇺🇦",
                                "Англійська" => "🇬🇧",
                                "Французька" => "🇫🇷",
                                "Німецька" => "🇩🇪",
                                "Іспанська" => "🇪🇸"
                            ];
                            $languages_list = array_keys($language_icons);
                            if (!empty($url_language) && !in_array($url_language, $languages_list)) {
                                array_unshift($languages_list, $url_language);
                            }
                            foreach ($languages_list as $index => $language): 
                                $is_checked = (mb_strtolower($language, 'UTF-8') === mb_strtolower($url_language, 'UTF-8')) ? 'checked' : '';
                                $emoji = isset($language_icons[$language]) ? $language_icons[$language] : '🌐';
                            ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox bg-dark border-secondary" type="checkbox" name="languages[]" value="<?php echo htmlspecialchars($language); ?>" id="l<?php echo $index; ?>" <?php echo $is_checked; ?>>
                                    <label class="form-check-label text-white-50" for="l<?php echo $index; ?>"><span class="me-1"><?php echo $emoji; ?></span> <?php echo htmlspecialchars($language); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                <h4 class="mb-0 text-white"><i class="fas fa-layer-group text-success me-2"></i> Каталог</h4>
                <div id="filter-loader" class="text-success d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>Оновлення...
                </div>
            </div>

            <div id="games-grid-container" style="transition: opacity 0.3s ease;">
            </div>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>