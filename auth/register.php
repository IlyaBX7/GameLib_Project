<?php
require_once '../includes/db_connect.php';
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username)) $errors[] = "Ім'я користувача не може бути порожнім.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Невірний формат email.";

    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W_]/", $password)) {
        $errors[] = "Пароль повинен містити щонайменше 8 символів, велику та малу літери, цифру та спеціальний символ (!@#$%^&* тощо).";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Користувач з таким email вже зареєстрований.";
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = 'user';

            header("Location: ../user/profile.php");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Помилка реєстрації: " . $e->getMessage();
        }
    }
}

$pageTitle = 'Реєстрація';
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section" style="max-width: 500px;">
    <h2 class="mb-4 text-center text-white">Створити акаунт</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger shadow-sm">
            <?php foreach ($errors as $error): ?>
                <p class="mb-1"><i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="bg-dark-green p-4 rounded border border-secondary shadow">
        <div class="mb-3">
            <label for="username" class="form-label text-white">Ім'я користувача</label>
            <input type="text" class="form-control bg-dark text-white border-secondary" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label text-white">Email</label>
            <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label text-white">Пароль</label>
            <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" required>
            <div class="form-text text-white-50 mt-2 small" style="line-height: 1.4;">
                <i class="fas fa-shield-alt text-accent me-1"></i> Мінімум 8 символів, обов'язково велика та мала літери, цифра і спецсимвол.
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100 fw-bold fs-5">Зареєструватися</button>
    </form>

    <?php
    $googleClientID = '78698731201-o8ildr7r3bqdfh0peoor9o0e77eut636.apps.googleusercontent.com';
    $googleRedirectUri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth/google_callback.php'; 
    $googleLoginUrl = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . $googleClientID . '&redirect_uri=' . urlencode($googleRedirectUri) . '&scope=email%20profile';
    ?>

    <div class="mt-3 text-center">
        <p class="text-white-50 small mb-2">Або увійдіть через соцмережі:</p>
        <a href="<?php echo $googleLoginUrl; ?>" class="btn btn-outline-light w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center py-2" style="border-color: #555;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 20px; margin-right: 10px;">
            Увійти через Google
        </a>
    </div>

    <p class="text-center mt-4 text-white-50">
        Вже маєте акаунт? <a href="login.php" class="text-accent fw-bold text-decoration-none">Увійти</a>
    </p>
</div>

<?php require_once '../includes/footer.php'; ?>