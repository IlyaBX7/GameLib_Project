<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'developer'])) {
    header("Location: ../index.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_game'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $release_date = $_POST['release_date'];
    $tags = trim($_POST['tags'] ?? '');
    $cover_url = trim($_POST['cover_url'] ?? '');
    $screenshot1 = trim($_POST['screenshot1'] ?? '');
    $screenshot2 = trim($_POST['screenshot2'] ?? '');
    $screenshot3 = trim($_POST['screenshot3'] ?? '');
    $screenshot4 = trim($_POST['screenshot4'] ?? '');
    $publisher_id = $_SESSION['user_id']; 

    if (!empty($title) && !empty($cover_url)) {

        $stmt = $pdo->prepare("INSERT INTO games (title, description, release_date, tags, cover_url, screenshot1, screenshot2, screenshot3, screenshot4, publisher_id, is_approved) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        if ($stmt->execute([$title, $description, $release_date, $tags, $cover_url, $screenshot1, $screenshot2, $screenshot3, $screenshot4, $publisher_id])) {
            $message = '<div class="alert alert-success shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> Гру успішно додано до бази GameLib!</div>';
        } else {
            $message = '<div class="alert alert-danger shadow-sm"><i class="fas fa-times-circle me-2"></i> Помилка при збереженні в базу.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning shadow-sm"><i class="fas fa-exclamation-triangle me-2"></i> Заповніть обов\'язкові поля (Назва та Обкладинка).</div>';
    }
}

$pageTitle = 'Додавання гри (RAWG API)';
$base_path = '../';
require_once '../includes/header.php';
?>

<div class="container content-section mt-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="bg-dark p-4 rounded border border-secondary shadow-sm sticky-top" style="top: 100px;">
                <h4 class="text-accent mb-3"><i class="fas fa-magic me-2"></i> Автозаповнення</h4>
                <p class="text-white-50 small mb-3">Введіть назву гри. Система знайде її в базі RAWG і автоматично заповнить усі поля форми праворуч!</p>

                <div class="input-group mb-3">
                    <input type="text" id="rawg-search-input" class="form-control bg-dark-green text-white border-secondary" placeholder="Назва гри (напр. Witcher 3)...">
                    <button class="btn btn-success fw-bold" type="button" id="rawg-search-btn"><i class="fas fa-search"></i></button>
                </div>

                <div id="rawg-loader" class="text-center d-none my-3">
                    <div class="spinner-border text-accent" role="status"><span class="visually-hidden">Завантаження...</span></div>
                </div>

                <div id="rawg-results" class="list-group"></div>
            </div>
        </div>

        <div class="col-lg-8">
            <h2 class="mb-4 text-white"><i class="fas fa-plus-circle text-accent me-2"></i> Нова гра</h2>
            <?php echo $message; ?>

            <form action="add_game.php" method="POST" class="bg-dark p-4 rounded border border-secondary shadow-sm" id="game-form">

                <div class="mb-3">
                    <label class="form-label text-white fw-bold">Назва гри *</label>
                    <input type="text" name="title" id="form-title" class="form-control bg-dark-green text-white border-secondary" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-white fw-bold">Дата виходу</label>
                        <input type="date" name="release_date" id="form-release" class="form-control bg-dark-green text-white border-secondary">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white fw-bold">Жанри (через кому)</label>
                        <input type="text" name="tags" id="form-tags" class="form-control bg-dark-green text-white border-secondary" placeholder="RPG, Action, Open World...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-bold">Опис гри</label>
                    <textarea name="description" id="form-desc" class="form-control bg-dark-green text-white border-secondary" rows="5" placeholder="Додайте опис..."></textarea>
                </div>

                <hr class="border-secondary my-4">
                <h5 class="text-accent mb-3"><i class="fas fa-image me-2"></i> Медіа-матеріали</h5>

                <div class="mb-3">
                    <label class="form-label text-white fw-bold">Головна обкладинка (URL) *</label>
                    <input type="url" name="cover_url" id="form-cover" class="form-control bg-dark-green text-white border-secondary" required placeholder="https://...">
                    <img id="preview-cover" src="" class="img-fluid rounded mt-2 d-none" style="max-height: 200px; border: 2px solid var(--accent-color);">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Скріншот 1 (URL)</label>
                        <input type="url" name="screenshot1" id="form-screen1" class="form-control bg-dark-green text-white border-secondary" placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Скріншот 2 (URL)</label>
                        <input type="url" name="screenshot2" id="form-screen2" class="form-control bg-dark-green text-white border-secondary" placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Скріншот 3 (URL)</label>
                        <input type="url" name="screenshot3" id="form-screen3" class="form-control bg-dark-green text-white border-secondary" placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Скріншот 4 (URL)</label>
                        <input type="url" name="screenshot4" id="form-screen4" class="form-control bg-dark-green text-white border-secondary" placeholder="https://...">
                    </div>
                </div>

                <button type="submit" name="save_game" class="btn btn-success w-100 fw-bold fs-5 mt-3"><i class="fas fa-save me-2"></i> Зберегти гру в базу</button>
            </form>
        </div>
    </div>
</div>

<script>

const RAWG_API_KEY = '288baf617f204b65ba0a773a0ed39ef0';

document.getElementById('rawg-search-btn').addEventListener('click', function() {
    const query = document.getElementById('rawg-search-input').value.trim();
    if (!query) return;

    const resultsDiv = document.getElementById('rawg-results');
    const loader = document.getElementById('rawg-loader');

    resultsDiv.innerHTML = '';
    loader.classList.remove('d-none');

    fetch(`https://api.rawg.io/api/games?key=${RAWG_API_KEY}&search=${encodeURIComponent(query)}&page_size=5`)
        .then(response => response.json())
        .then(data => {
            loader.classList.add('d-none');

            if (data.results && data.results.length > 0) {
                data.results.forEach(game => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action bg-dark-green text-white border-secondary d-flex align-items-center mb-1 rounded';
                    btn.innerHTML = `<img src="${game.background_image}" style="width:40px; height:40px; object-fit:cover; border-radius:4px; margin-right:10px;"> 
                                     <span class="fw-bold">${game.name}</span> <small class="ms-auto text-white-50">${game.released ? game.released.substring(0,4) : ''}</small>`;

                    btn.onclick = () => fetchGameDetails(game.id);
                    resultsDiv.appendChild(btn);
                });
            } else {
                resultsDiv.innerHTML = '<div class="p-3 text-center text-warning">Нічого не знайдено</div>';
            }
        })
        .catch(err => {
            loader.classList.add('d-none');
            resultsDiv.innerHTML = '<div class="p-3 text-center text-danger">Помилка API</div>';
            console.error(err);
        });
});

function fetchGameDetails(gameId) {
    const loader = document.getElementById('rawg-loader');
    loader.classList.remove('d-none');

    Promise.all([
        fetch(`https://api.rawg.io/api/games/${gameId}?key=${RAWG_API_KEY}`).then(r => r.json()),
        fetch(`https://api.rawg.io/api/games/${gameId}/screenshots?key=${RAWG_API_KEY}`).then(r => r.json())
    ])
    .then(([gameData, screenshotsData]) => {
        loader.classList.add('d-none');

        document.getElementById('form-title').value = gameData.name || '';
        document.getElementById('form-release').value = gameData.released || '';

        if (gameData.description) {
            document.getElementById('form-desc').value = gameData.description.replace(/<[^>]*>?/gm, '');
        }

        if (gameData.genres && gameData.genres.length > 0) {
            document.getElementById('form-tags').value = gameData.genres.map(g => g.name).join(', ');
        }

        if (gameData.background_image) {
            document.getElementById('form-cover').value = gameData.background_image;
            const preview = document.getElementById('preview-cover');
            preview.src = gameData.background_image;
            preview.classList.remove('d-none');
        }

        if (screenshotsData.results && screenshotsData.results.length > 0) {
            const screens = screenshotsData.results;
            if(screens[0]) document.getElementById('form-screen1').value = screens[0].image;
            if(screens[1]) document.getElementById('form-screen2').value = screens[1].image;
            if(screens[2]) document.getElementById('form-screen3').value = screens[2].image;
            if(screens[3]) document.getElementById('form-screen4').value = screens[3].image;
        }

        document.getElementById('form-title').focus();
        document.getElementById('rawg-results').innerHTML = '<div class="alert alert-success mt-2"><i class="fas fa-check-circle"></i> Дані успішно завантажено! Перевірте форму і натисніть "Зберегти".</div>';
    })
    .catch(err => {
        loader.classList.add('d-none');
        console.error("Помилка завантаження деталей:", err);
        alert("Не вдалося завантажити деталі гри.");
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>