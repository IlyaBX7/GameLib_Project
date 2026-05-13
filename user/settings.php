<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/cloudinary.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
$user_id = $_SESSION['user_id'];
$message = '';

$stmt_lib = $pdo->prepare("SELECT g.id, g.title FROM user_library ul JOIN games g ON ul.game_id = g.id WHERE ul.user_id = ?");
$stmt_lib->execute([$user_id]);
$user_games = $stmt_lib->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['save_settings'])) {
        $username = trim($_POST['username']);
        $bio = trim($_POST['bio']);
        $country = trim($_POST['country']);
        $accent_color = $_POST['accent_color'];
        $privacy_profile = $_POST['privacy_profile'];
        $showcase_game_id = !empty($_POST['showcase_game_id']) ? (int)$_POST['showcase_game_id'] : null;
        $steam_id = trim($_POST['steam_id']);
        $show_level_frame = isset($_POST['show_level_frame']) ? 1 : 0; 

        $banner_query = "";
        $params = [$username, $bio, $country, $accent_color, $privacy_profile, $showcase_game_id, $steam_id, $show_level_frame];

        if (isset($_FILES['new_banner']) && $_FILES['new_banner']['error'] === 0) {
            $cloudinaryUrl = uploadToCloudinary($_FILES['new_banner']);
            if ($cloudinaryUrl) {
                $banner_query = ", banner_url = ?";
                $params[] = $cloudinaryUrl;
            } else {
                $upload_error = true;
            }
        }
        $params[] = $user_id;

        $stmt_update = $pdo->prepare("UPDATE users SET username=?, bio=?, country=?, accent_color=?, privacy_profile=?, showcase_game_id=?, steam_id=?, show_level_frame=? $banner_query WHERE id=?");
        $stmt_update->execute($params);
        $_SESSION['username'] = $username;
        
        if (isset($upload_error)) {
            $message = '<div class="alert alert-warning shadow-sm"><i class="fas fa-exclamation-triangle me-2"></i> Основні налаштування збережено, але виникла помилка при завантаженні зображення в Cloudinary.</div>';
        } else {
            $message = '<div class="alert alert-success shadow-sm"><i class="fas fa-check-circle me-2"></i> Основні налаштування успішно збережено!</div>';
        }
    }

    if (isset($_POST['change_email'])) {
        $new_email = trim($_POST['new_email']);
        $curr_pass = $_POST['current_password_email'];

        $stmt_pass = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt_pass->execute([$user_id]);
        $hash = $stmt_pass->fetchColumn();

        if (password_verify($curr_pass, $hash)) {
            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                $stmt_check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt_check->execute([$new_email, $user_id]);
                if ($stmt_check->fetch()) {
                    $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Цей email вже використовується іншим користувачем.</div>';
                } else {
                    $pdo->prepare("UPDATE users SET email = ? WHERE id = ?")->execute([$new_email, $user_id]);
                    $message = '<div class="alert alert-success shadow-sm"><i class="fas fa-check-circle me-2"></i> Електронну пошту успішно змінено!</div>';
                }
            } else {
                $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Невірний формат email.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Невірний поточний пароль.</div>';
        }
    }

    if (isset($_POST['change_password'])) {
        $curr_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $conf_pass = $_POST['confirm_new_password'];

        $stmt_pass = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt_pass->execute([$user_id]);
        $hash = $stmt_pass->fetchColumn();

        if (password_verify($curr_pass, $hash)) {
            if ($new_pass === $conf_pass) {
                if (strlen($new_pass) < 8 || !preg_match("/[A-Z]/", $new_pass) || !preg_match("/[a-z]/", $new_pass) || !preg_match("/[0-9]/", $new_pass) || !preg_match("/[\W_]/", $new_pass)) {
                    $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-shield-alt me-2"></i> Новий пароль заслабкий. Він має відповідати вимогам безпеки.</div>';
                } else {
                    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$new_hash, $user_id]);
                    $message = '<div class="alert alert-success shadow-sm"><i class="fas fa-lock me-2"></i> Пароль успішно змінено на новий!</div>';
                }
            } else {
                $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Нові паролі не співпадають.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Невірний поточний пароль.</div>';
        }
    }

    if (isset($_POST['delete_account'])) {
        $password = $_POST['delete_password'];
        $stmt_pass = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt_pass->execute([$user_id]);
        $hash = $stmt_pass->fetchColumn();

        if (password_verify($password, $hash)) {
            $pdo->prepare("DELETE FROM user_library WHERE user_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM friendships WHERE user_id1 = ? OR user_id2 = ?")->execute([$user_id, $user_id]);
            $pdo->prepare("DELETE FROM followers WHERE user_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM review_likes WHERE user_id = ?")->execute([$user_id]);

            $pdo->prepare("DELETE FROM developer_reviews WHERE author_user_id = ? OR developer_user_id = ?")->execute([$user_id, $user_id]);
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

            session_destroy();
            header("Location: ../index.php"); exit;
        } else {
            $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-exclamation-triangle me-2"></i> Невірний пароль! Акаунт не видалено.</div>';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Налаштування';
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-4 text-white"><i class="fas fa-cog text-accent me-2"></i> Налаштування профілю</h2>

            <?php echo $message; ?>

            <ul class="nav nav-tabs profile-tabs mb-4" role="tablist">
                <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#general"><i class="fas fa-user me-1"></i> Основні</button></li>
                <li class="nav-item"><button class="nav-link text-warning fw-bold" data-bs-toggle="tab" data-bs-target="#security"><i class="fas fa-shield-alt me-1"></i> Безпека</button></li>
                <li class="nav-item"><button class="nav-link text-danger fw-bold" data-bs-toggle="tab" data-bs-target="#danger"><i class="fas fa-skull me-1"></i> Видалення</button></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade show active" id="general">
                    <form action="settings.php" method="POST" enctype="multipart/form-data" class="bg-dark p-4 rounded border border-secondary shadow-sm">
                        <h4 class="text-accent mb-4 border-bottom border-secondary pb-2">Особисті дані</h4>
                        <div class="mb-3">
                            <label class="form-label text-white">Ім'я користувача</label>
                            <input type="text" name="username" class="form-control bg-dark-green text-white border-secondary" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Про себе</label>
                            <textarea name="bio" class="form-control bg-dark-green text-white border-secondary" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white">Країна</label>
                            <input type="text" name="country" class="form-control bg-dark-green text-white border-secondary" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
                        </div>

                        <h4 class="text-accent mb-4 border-bottom border-secondary pb-2 mt-5">Кастомізація профілю</h4>

                        <div class="form-check form-switch mb-4 bg-dark-green p-3 rounded border border-secondary d-flex align-items-center">
                            <input class="form-check-input bg-dark border-secondary ms-0 me-3 mt-0" type="checkbox" id="showLevelFrame" name="show_level_frame" value="1" <?php echo (!isset($user['show_level_frame']) || $user['show_level_frame'] == 1) ? 'checked' : ''; ?> style="width: 3em; height: 1.5em; cursor: pointer;">
                            <div>
                                <label class="form-check-label text-white fw-bold" for="showLevelFrame" style="cursor: pointer;">
                                    <i class="fas fa-magic text-accent me-1"></i> Відображати рангову рамку аватара
                                </label>
                                <div class="form-text text-white-50 mt-1 mb-0" style="line-height: 1.3;">Якщо вимкнути, навколо аватара завжди буде звичайна рамка вашого акцентного кольору (без світіння та ефектів).</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-white">Акцентний колір</label>
                                <input type="color" name="accent_color" class="form-control form-control-color bg-dark-green border-secondary w-100" value="<?php echo htmlspecialchars($user['accent_color'] ?? '#00ff64'); ?>" style="height: 40px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Фонове зображення</label>
                                <input type="file" name="new_banner" class="form-control bg-dark-green text-white border-secondary" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-white">Вітрина: Улюблена гра</label>
                            <select name="showcase_game_id" class="form-select bg-dark-green text-white border-secondary">
                                <option value="">Не відображати</option>
                                <?php foreach($user_games as $g): ?>
                                    <option value="<?php echo $g['id']; ?>" <?php echo ($user['showcase_game_id'] == $g['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($g['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h4 class="text-accent mb-4 border-bottom border-secondary pb-2 mt-5">Інтеграції</h4>
                        <div class="mb-4">
                            <label class="form-label text-white"><i class="fab fa-steam text-accent"></i> Steam ID</label>
                            <input type="text" name="steam_id" class="form-control bg-dark-green text-white border-secondary" placeholder="Наприклад: 76561198000000000" value="<?php echo htmlspecialchars($user['steam_id'] ?? ''); ?>">
                            <small class="text-white-50">Введіть свій 17-значний Steam ID (формату SteamID64), щоб синхронізувати ігри.</small>
                        </div>

                        <h4 class="text-accent mb-4 border-bottom border-secondary pb-2 mt-5">Приватність</h4>
                        <div class="mb-4">
                            <label class="form-label text-white">Хто може бачити ваш профіль?</label>
                            <select name="privacy_profile" class="form-select bg-dark-green text-white border-secondary">
                                <option value="public" <?php echo ($user['privacy_profile'] == 'public') ? 'selected' : ''; ?>>Всі користувачі (Публічний)</option>
                                <option value="friends" <?php echo ($user['privacy_profile'] == 'friends') ? 'selected' : ''; ?>>Тільки друзі</option>
                                <option value="private" <?php echo ($user['privacy_profile'] == 'private') ? 'selected' : ''; ?>>Ніхто (Приватний)</option>
                            </select>
                        </div>

                        <button type="submit" name="save_settings" class="btn btn-success w-100 fw-bold fs-5 mt-2">Зберегти налаштування</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="security">
                    <form action="settings.php" method="POST" class="bg-dark p-4 rounded border border-secondary shadow-sm mb-4">
                        <h4 class="text-warning mb-4 border-bottom border-secondary pb-2"><i class="fas fa-envelope me-2"></i> Зміна Email</h4>
                        <div class="mb-3">
                            <label class="form-label text-white-50">Поточний Email</label>
                            <input type="text" class="form-control bg-dark-green text-white-50 border-secondary" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Новий Email</label>
                            <input type="email" name="new_email" class="form-control bg-dark-green text-white border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white">Поточний пароль (для підтвердження)</label>
                            <input type="password" name="current_password_email" class="form-control bg-dark-green text-white border-secondary" required>
                        </div>
                        <button type="submit" name="change_email" class="btn btn-outline-warning w-100 fw-bold">Оновити Email</button>
                    </form>

                    <form action="settings.php" method="POST" class="bg-dark p-4 rounded border border-secondary shadow-sm">
                        <h4 class="text-warning mb-4 border-bottom border-secondary pb-2"><i class="fas fa-key me-2"></i> Зміна Пароля</h4>
                        <div class="mb-3">
                            <label class="form-label text-white">Поточний пароль</label>
                            <input type="password" name="current_password" class="form-control bg-dark-green text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Новий пароль</label>
                            <input type="password" name="new_password" class="form-control bg-dark-green text-white border-secondary" required>
                            <div class="form-text text-white-50 small mt-1">Мінімум 8 символів, велика і мала літери, цифра та спецсимвол.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white">Підтвердіть новий пароль</label>
                            <input type="password" name="confirm_new_password" class="form-control bg-dark-green text-white border-secondary" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning w-100 fw-bold text-dark">Змінити Пароль</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="danger">
                    <div class="bg-dark p-4 rounded border border-danger shadow-sm">
                        <h4 class="text-danger mb-3"><i class="fas fa-skull-crossbones me-2"></i> Видалення акаунту</h4>
                        <p class="text-white-50 mb-4" style="line-height: 1.6;">
                            Увага! Ця дія незворотна. Ваша бібліотека ігор, відгуки, оцінки, налаштування та дружні зв'язки будуть видалені назавжди без жодної можливості відновлення. 
                        </p>

                        <form action="settings.php" method="POST" onsubmit="return confirm('ВИДАЛЕННЯ АКАУНТУ: Ви абсолютно впевнені? Всі ваші дані зникнуть назавжди.');">
                            <div class="mb-4">
                                <label class="form-label text-white fw-bold">Для підтвердження видалення введіть ваш поточний пароль:</label>
                                <input type="password" name="delete_password" class="form-control bg-dark-green text-white border-danger" required>
                            </div>
                            <button type="submit" name="delete_account" class="btn btn-danger w-100 fw-bold fs-5"><i class="fas fa-trash-alt me-2"></i> Видалити акаунт назавжди</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>