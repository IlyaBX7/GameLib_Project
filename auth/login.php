<?php
require_once '../includes/db_connect.php';
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Введіть email та пароль.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];

            header("Location: ../user/profile.php");
            exit;
        } else {
            $errors[] = "Невірний email або пароль.";
        }
    }
}

$pageTitle = 'Вхід';
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Вхід до акаунту</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <p class="mb-0"><?php echo $errors[0]; ?></p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="bg-dark-green p-4 rounded">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Увійти</button>
    </form>

    <?php
    $googleClientID = '78698731201-o8ildr7r3bqdfh0peoor9o0e77eut636.apps.googleusercontent.com';
    $googleRedirectUri = 'http://localhost/gamelib/google_callback.php'; 
    $googleLoginUrl = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . $googleClientID . '&redirect_uri=' . urlencode($googleRedirectUri) . '&scope=email%20profile';
    ?>

    <div class="mt-3 text-center">
        <p class="text-white-50 small mb-2">Або увійдіть через соцмережі:</p>
        <a href="<?php echo $googleLoginUrl; ?>" class="btn btn-outline-light w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center py-2" style="border-color: #555;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 20px; margin-right: 10px;">
            Увійти через Google
        </a>
    </div>

    <p class="text-center mt-3">
        Немає акаунту? <a href="register.php" class="text-accent">Зареєструватися</a>
    </p>
</div>

<?php require_once '../includes/footer.php'; ?>