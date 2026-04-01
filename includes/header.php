<?php 

$bp = isset($base_path) ? $base_path : '';
?>
<!DOCTYPE html>
<html lang="uk" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'GameLib - Бібліотека ігор'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.AppConfig = {
            basePath: '<?php echo BASE_URL; ?>'
        };
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo resolve_url($page_css); ?>">
    <?php endif; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $bp; ?>index.php">
            <i class="fas fa-gamepad"></i> GameLib
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"><i class="fas fa-bars text-white fs-4"></i></button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Головна</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>community/community.php">Спільнота</a></li>
            </ul>

            <ul class="navbar-nav align-items-lg-center">

                <li class="nav-item me-lg-3 mb-2 mb-lg-0 position-relative" style="min-width: 250px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-search"></i></span>
                        <input type="text" id="live-search-input" class="form-control bg-dark text-white border-secondary border-start-0 ps-0" placeholder="Пошук ігор..." autocomplete="off">
                    </div>
                    <div id="live-search-results" class="position-absolute w-100 bg-dark-green border border-secondary rounded shadow d-none" style="top: 100%; left: 0; z-index: 1050;"></div>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link dropdown-toggle position-relative px-0" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="markNotificationsRead()">
                            <i class="fas fa-bell fs-5 text-white"></i>
                            <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" style="font-size: 0.6rem;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow-lg p-0" aria-labelledby="notifDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;" id="notif-list">
                            <li class="p-3 text-center text-white-50 small">Немає нових сповіщень</li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-nav-btn d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fs-5 me-2 text-accent"></i>
                            <span class="text-white" style="text-transform: none;"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Профіль'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>user/profile.php"><i class="fas fa-layer-group me-2 w-20px text-center"></i> Моя бібліотека</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>user/settings.php"><i class="fas fa-cog me-2 w-20px text-center"></i> Налаштування</a></li>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <li><hr class="dropdown-divider dropdown-divider-custom"></li>
                                <li><a class="dropdown-item text-warning" href="<?php echo BASE_URL; ?>dashboards/admin_panel.php"><i class="fas fa-shield-alt me-2 w-20px text-center"></i> Адмін-панель</a></li>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'developer'): ?>
                                <li><hr class="dropdown-divider dropdown-divider-custom"></li>
                                <li><a class="dropdown-item text-warning" href="<?php echo BASE_URL; ?>dashboards/developer_panel.php"><i class="fas fa-code me-2 w-20px text-center"></i> Панель розробника</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider dropdown-divider-custom"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2 w-20px text-center"></i> Вийти</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-success btn-sm px-4 rounded-pill" href="<?php echo BASE_URL; ?>auth/login.php">Увійти</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

