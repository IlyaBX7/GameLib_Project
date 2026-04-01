<?php
session_start();
require_once '../includes/db_connect.php';

$stmt_top = $pdo->query("
    SELECT u.id, u.username, u.avatar_url, u.user_role,
           (
               (SELECT COUNT(*) FROM user_library WHERE user_id = u.id) * 50 +
               (SELECT COUNT(*) FROM game_reviews WHERE user_id = u.id) * 100 +
               (SELECT COUNT(*) FROM friendships WHERE (user_id1 = u.id OR user_id2 = u.id) AND status = 'accepted') * 20 +
               IF(u.user_role = 'developer', (SELECT COUNT(*) FROM followers WHERE developer_id = u.id) * 50, 0)
           ) as xp
    FROM users u
    ORDER BY xp DESC
    LIMIT 3
");
$top_users = $stmt_top->fetchAll(PDO::FETCH_ASSOC);

$filter_role = isset($_GET['role']) ? $_GET['role'] : 'all';

if ($filter_role === 'developer') {
    $stmt = $pdo->prepare("SELECT id, username, avatar_url, user_role, country, created_at FROM users WHERE user_role = 'developer' ORDER BY created_at DESC");
    $stmt->execute();
} elseif ($filter_role === 'gamer') {
    $stmt = $pdo->prepare("SELECT id, username, avatar_url, user_role, country, created_at FROM users WHERE user_role = 'user' OR user_role IS NULL ORDER BY created_at DESC");
    $stmt->execute();
} else {
    $stmt = $pdo->query("SELECT id, username, avatar_url, user_role, country, created_at FROM users ORDER BY created_at DESC");
}

$users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Спільнота GameLib';
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section">

    <div class="text-center mb-5 pb-4 border-bottom border-secondary">
        <h2 class="text-white mb-3 fw-bold"><i class="fas fa-trophy text-warning me-2"></i> Зал Слави GameLib</h2>
        <p class="text-white-50 lead mb-4">Найактивніші гравці та розробники нашої платформи.</p>

        <?php if (count($top_users) >= 3): ?>
            <div class="podium">
                <div class="podium-place place-2">
                    <a href="../user/profile.php?id=<?php echo $top_users[1]['id']; ?>" class="text-decoration-none">
                        <img src="<?php echo htmlspecialchars(resolve_url($top_users[1]['avatar_url'] ?? 'img/avatars/default.png')); ?>" class="podium-avatar">
                        <span class="podium-username d-block"><?php echo htmlspecialchars($top_users[1]['username']); ?></span>
                        <span class="podium-xp"><?php echo $top_users[1]['xp']; ?> XP</span>
                        <div class="podium-step">2</div>
                    </a>
                </div>

                <div class="podium-place place-1" style="z-index: 10;">
                    <i class="fas fa-crown text-warning" style="font-size: 3rem; position: absolute; top: -50px; left: 50%; transform: translateX(-50%); text-shadow: 0 0 15px #ffd700;"></i>
                    <a href="../user/profile.php?id=<?php echo $top_users[0]['id']; ?>" class="text-decoration-none">
                        <img src="<?php echo htmlspecialchars(resolve_url($top_users[0]['avatar_url'] ?? 'img/avatars/default.png')); ?>" class="podium-avatar">
                        <span class="podium-username d-block fs-4"><?php echo htmlspecialchars($top_users[0]['username']); ?></span>
                        <span class="podium-xp fs-5"><?php echo $top_users[0]['xp']; ?> XP</span>
                        <div class="podium-step">1</div>
                    </a>
                </div>

                <div class="podium-place place-3">
                    <a href="../user/profile.php?id=<?php echo $top_users[2]['id']; ?>" class="text-decoration-none">
                        <img src="<?php echo htmlspecialchars(resolve_url($top_users[2]['avatar_url'] ?? 'img/avatars/default.png')); ?>" class="podium-avatar">
                        <span class="podium-username d-block"><?php echo htmlspecialchars($top_users[2]['username']); ?></span>
                        <span class="podium-xp"><?php echo $top_users[2]['xp']; ?> XP</span>
                        <div class="podium-step">3</div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info bg-dark border-secondary text-white-50 d-inline-block">Недостатньо користувачів для формування рейтингу Топ-3.</div>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="mb-0"><i class="fas fa-users text-accent"></i> Спільнота гравців</h2>

        <div class="btn-group shadow-sm" role="group">
            <a href="community.php" class="btn <?php echo $filter_role === 'all' ? 'btn-success fw-bold' : 'btn-outline-success'; ?>">Усі</a>
            <a href="community.php?role=gamer" class="btn <?php echo $filter_role === 'gamer' ? 'btn-success fw-bold' : 'btn-outline-success'; ?>">Геймери</a>
            <a href="community.php?role=developer" class="btn <?php echo $filter_role === 'developer' ? 'btn-success fw-bold' : 'btn-outline-success'; ?>">Розробники</a>
        </div>
    </div>

    <div class="mb-4">
        <div class="input-group input-group-lg shadow-sm">
            <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-search"></i></span>
            <input type="text" id="community-search" class="form-control bg-dark border-secondary text-white" placeholder="Почніть вводити нікнейм користувача для швидкого пошуку...">
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="community-grid">
        <?php if (empty($users_list)): ?>
            <div class="col-12 text-center py-5 w-100">
                <p class="text-white-50 fs-5">Користувачів у цій категорії ще немає.</p>
            </div>
        <?php else: ?>
            <?php foreach ($users_list as $u): ?>
                <div class="col user-item" data-username="<?php echo mb_strtolower(htmlspecialchars($u['username']), 'UTF-8'); ?>">
                    <div class="card bg-dark border-secondary h-100 text-center p-4 user-card shadow-sm">
                        <a href="../user/profile.php?id=<?php echo $u['id']; ?>" class="text-decoration-none">
                            <img src="<?php echo htmlspecialchars(resolve_url($u['avatar_url'] ?? 'img/avatars/default.png')); ?>" 
                                 class="rounded-circle mb-3 user-card-avatar" 
                                 style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #2a473a;" 
                                 alt="Avatar">

                            <h5 class="card-title text-white mb-2"><?php echo htmlspecialchars($u['username']); ?></h5>

                            <div class="mb-3">
                                <?php if ($u['user_role'] === 'admin'): ?>
                                    <span class="badge bg-danger p-2"><i class="fas fa-shield-alt"></i> Адміністратор</span>
                                <?php elseif ($u['user_role'] === 'developer'): ?>
                                    <span class="badge bg-warning text-dark p-2"><i class="fas fa-code"></i> Розробник</span>
                                <?php else: ?>
                                    <span class="badge bg-success p-2 text-dark"><i class="fas fa-gamepad"></i> Геймер</span>
                                <?php endif; ?>
                            </div>

                            <div class="text-white-50 small">
                                <?php if (!empty($u['country'])): ?>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($u['country']); ?></p>
                                <?php endif; ?>
                                <p class="mb-0"><i class="fas fa-calendar-alt"></i> З нами від: <?php echo date('d.m.Y', strtotime($u['created_at'])); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="col-12 text-center py-5 d-none w-100" id="no-results-msg">
            <h4 class="text-white-50"><i class="fas fa-ghost mb-3 fs-1"></i><br>Користувача з таким нікнеймом не знайдено</h4>
        </div>
    </div>
</div>

<style>

.podium { display: flex; align-items: flex-end; justify-content: center; gap: 15px; margin-top: 50px; }
.podium-place { text-align: center; width: 30%; max-width: 200px; position: relative; transition: transform 0.3s; }
.podium-place:hover { transform: translateY(-10px); }
.podium-avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid var(--border-color); margin-bottom: 15px; background: #000; }
.podium-step { border-radius: 10px 10px 0 0; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #000; font-weight: 900; font-size: 2rem; box-shadow: 0 -5px 15px rgba(0,0,0,0.5); }

.place-1 .podium-step { height: 180px; background: linear-gradient(145deg, #ffd700, #ffaa00); }
.place-1 .podium-avatar { border-color: #ffd700; width: 130px; height: 130px; box-shadow: 0 0 20px rgba(255, 215, 0, 0.5); }

.place-2 .podium-step { height: 130px; background: linear-gradient(145deg, #e0e0e0, #a0a0a0); }
.place-2 .podium-avatar { border-color: #c0c0c0; box-shadow: 0 0 15px rgba(192, 192, 192, 0.3); }

.place-3 .podium-step { height: 90px; background: linear-gradient(145deg, #cd7f32, #8b4513); }
.place-3 .podium-avatar { border-color: #cd7f32; box-shadow: 0 0 15px rgba(205, 127, 50, 0.3); }

.podium-username { color: #fff; font-weight: bold; font-size: 1.1rem; text-shadow: 1px 1px 5px #000; }
.podium-xp { color: var(--accent-color); font-weight: bold; font-size: 0.95rem; text-shadow: 1px 1px 3px #000; margin-bottom: 10px; display: block; }

.user-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    border: 1px solid var(--bg-light-green, #2a473a) !important;
}
.user-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0, 255, 100, 0.15) !important;
    border-color: var(--accent-color, #00ff64) !important;
}
.user-card-avatar {
    transition: border-color 0.3s ease;
}
.user-card:hover .user-card-avatar {
    border-color: var(--accent-color, #00ff64) !important;
}
</style>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('community-search');
    const userItems = document.querySelectorAll('.user-item');
    const noResultsMsg = document.getElementById('no-results-msg');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasVisibleCards = false;

            userItems.forEach(item => {
                const username = item.getAttribute('data-username');

                if (username.includes(searchTerm)) {
                    item.style.setProperty('display', 'block', 'important');
                    hasVisibleCards = true;
                } else {

                    item.style.setProperty('display', 'none', 'important');
                }
            });

            if (hasVisibleCards || searchTerm === '') {
                noResultsMsg.classList.add('d-none');
            } else {
                noResultsMsg.classList.remove('d-none');
            }
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>