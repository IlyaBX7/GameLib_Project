<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'GameLib - Моя Біліотека Ігор'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css?v=2.0"> 
    <link rel="stylesheet" href="css/profile.css?v=2.0">
    
    <link rel="icon" href="img/GameLib_logo.png" type="image/png">
</head>
<body>

    <header class="navbar navbar-expand-lg navbar-dark bg-dark-green sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/GameLib_logo.png" alt="GameLib Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                <strong>GameLib</strong>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Головна</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>" href="profile.php">Моя бібліотека</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : ''; ?>" href="search.php">Пошук</a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'developer'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'developer_panel.php') ? 'active' : ''; ?>" href="developer_panel.php">
                                Панель розробника
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_panel.php') ? 'active' : ''; ?>" href="admin_panel.php">
                                Панель адміністратора
                            </a>
                        </li>
                    <?php endif; ?>
                    
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-success me-2">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="btn btn-outline-success">Вийти</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-success me-2">Увійти</a>
                        <a href="register.php" class="btn btn-success">Реєстрація</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main>