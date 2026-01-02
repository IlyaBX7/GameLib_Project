<?php
require_once 'includes/db_connect.php';
session_start();

$errors = [];

// Якщо форма відправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Введіть email та пароль.";
    } else {
        // Шукаємо користувача
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Перевіряємо пароль
        if ($user && password_verify($password, $user['password_hash'])) {
            // Пароль вірний! Зберігаємо дані в сесію
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role']; // <-- ВАЖЛИВО: Зберігаємо роль
            
            header("Location: profile.php"); // Перенаправляємо на профіль
            exit;
        } else {
            $errors[] = "Невірний email або пароль.";
        }
    }
}

$pageTitle = 'Вхід';
require_once 'includes/header.php';
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
    <p class="text-center mt-3">
        Немає акаунту? <a href="register.php" class="text-accent">Зареєструватися</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>