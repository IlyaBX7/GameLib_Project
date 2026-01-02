<?php
require_once 'includes/db_connect.php';
session_start(); // Запускаємо сесію

$errors = []; // Масив для помилок

// Якщо форма відправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // --- Валідація ---
    if (empty($username)) $errors[] = "Ім'я користувача не може бути порожнім.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Невірний формат email.";
    if (strlen($password) < 6) $errors[] = "Пароль має бути не менше 6 символів.";

    // Перевірка, чи існує email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Користувач з таким email вже зареєстрований.";
    }

    // --- Якщо помилок немає ---
    if (empty($errors)) {
        // Хешуємо пароль
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Вставляємо в БД (роль 'user' ставиться за замовчуванням в БД)
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            
            // Отримуємо ID нового користувача і логінимо його
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = 'user'; // Встановлюємо роль при реєстрації
            
            // Перенаправляємо на сторінку профілю
            header("Location: profile.php");
            exit;
            
        } catch (PDOException $e) {
            $errors[] = "Помилка реєстрації: " . $e->getMessage();
        }
    }
}

$pageTitle = 'Реєстрація';
require_once 'includes/header.php';
?>

<div class="container content-section" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Створити акаунт</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p class="mb-0"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="bg-dark-green p-4 rounded">
        <div class="mb-3">
            <label for="username" class="form-label">Ім'я користувача</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль (мін. 6 символів)</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Зареєструватися</button>
    </form>
    <p class="text-center mt-3">
        Вже маєте акаунт? <a href="login.php" class="text-accent">Увійти</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>