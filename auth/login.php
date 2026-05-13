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
    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <div class="alert alert-success">
            <p class="mb-0">Пароль успішно змінено. Ви можете увійти з новим паролем.</p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="bg-dark-green p-4 rounded">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100">Увійти</button>
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

    <p class="text-center mt-3 mb-1">
        Немає акаунту? <a href="register.php" class="text-accent">Зареєструватися</a>
    </p>
    <p class="text-center">
        <a href="reset_password.php" class="text-accent">Забули пароль?</a>
    </p>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    
    if (type === 'password') {
        eyeIcon.innerHTML = '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>';
    } else {
        eyeIcon.innerHTML = '<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755l-.976-.976z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>