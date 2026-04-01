

const allGamesData = window.AdminPanelData.allGames;

document.addEventListener('DOMContentLoaded', function() {
    const searchInputAdmin = document.getElementById('admin-edit-search');
    const searchResultsAdmin = document.getElementById('admin-edit-search-results');
    const editContainerAdmin = document.getElementById('admin-edit-form-container');

    if (searchInputAdmin) {
        searchInputAdmin.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            searchResultsAdmin.innerHTML = '';
            editContainerAdmin.classList.add('d-none');

            if (query.length === 0) return;

            const filtered = allGamesData.filter(g => g.title.toLowerCase().includes(query)).slice(0, 10);

            if (filtered.length === 0) {
                searchResultsAdmin.innerHTML = '<div class="list-group-item bg-dark-green text-white-50 border-secondary">Нічого не знайдено</div>';
                return;
            }

            filtered.forEach(game => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-group-item list-group-item-action bg-dark-green text-white border-secondary d-flex align-items-center rounded mb-1';
                btn.innerHTML = `<img src="${game.cover_url}" style="width:35px;height:35px;object-fit:cover;margin-right:15px;border-radius:4px;border:1px solid #2a473a;"> <strong class="fs-6">${game.title}</strong>`;

                btn.onclick = () => {
                    searchInputAdmin.value = game.title;
                    searchResultsAdmin.innerHTML = ''; 
                    populateAdminEditForm(game);
                };
                searchResultsAdmin.appendChild(btn);
            });
        });
    }

    function populateAdminEditForm(game) {
        editContainerAdmin.classList.remove('d-none');

        document.getElementById('edit-game-id').value = game.id;
        document.getElementById('edit-form-title').value = game.title || '';
        document.getElementById('edit-form-desc').value = game.description || '';
        document.getElementById('edit-form-tags').value = game.tags || '';
        document.getElementById('edit-form-developer').value = game.developer || '';
        document.getElementById('edit-form-publisher').value = game.publisher || '';
        document.getElementById('edit-form-release').value = game.release_date || '';
        document.getElementById('edit-form-cover').value = game.cover_url || '';
        document.getElementById('edit-form-sys-min').value = game.sys_min || '';
        document.getElementById('edit-form-sys-rec').value = game.sys_rec || '';
        document.getElementById('edit-form-screen1').value = game.screenshot1 || '';
        document.getElementById('edit-form-screen2').value = game.screenshot2 || '';
        document.getElementById('edit-form-screen3').value = game.screenshot3 || '';
        document.getElementById('edit-form-screen4').value = game.screenshot4 || '';

        const featuresArray = game.features ? game.features.split(',') : [];
        document.querySelectorAll('.edit-feature-cb').forEach(cb => { cb.checked = featuresArray.includes(cb.value); });

        const langsArray = game.languages ? game.languages.split(',') : [];
        document.querySelectorAll('.edit-lang-cb').forEach(cb => { cb.checked = langsArray.includes(cb.value); });

        const platArray = game.platforms ? game.platforms.split(',') : [];
        document.querySelectorAll('.edit-plat-cb').forEach(cb => { cb.checked = platArray.includes(cb.value); });
    }
});

const RAWG_API_KEY = '288baf617f204b65ba0a773a0ed39ef0'; 

const genreDictionary = { "Action": "Екшен", "Indie": "Інді", "Adventure": "Пригоди", "RPG": "РПГ", "Strategy": "Стратегія", "Shooter": "Шутер" };
function translateGenres(genresArray) { if (!genresArray || genresArray.length === 0) return ''; return genresArray.map(g => genreDictionary[g.name] || g.name).join(', '); }
async function translateText(text) { if (!text) return ''; try { const res = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=uk&dt=t&q=${encodeURIComponent(text)}`); const data = await res.json(); return data[0].map(item => item[0]).join(''); } catch (e) { return text; } }

document.addEventListener('DOMContentLoaded', function() {

    const searchBtn = document.getElementById('rawg-search-btn');
    const searchInput = document.getElementById('rawg-search-input');

    if(searchBtn) {
        searchBtn.addEventListener('click', performRawgSearch);
        searchInput.addEventListener('keypress', function (e) { if (e.key === 'Enter') { e.preventDefault(); performRawgSearch(); } });
    }

    function performRawgSearch() {
        const query = searchInput.value.trim();
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
                        btn.innerHTML = `<img src="${game.background_image || 'img/avatars/default.png'}" style="width:40px; height:40px; object-fit:cover; border-radius:4px; margin-right:10px; border: 1px solid #2a473a;"> 
                                         <span class="fw-bold">${game.name}</span>`;
                        btn.onclick = () => fetchRawgGameDetails(game.id);
                        resultsDiv.appendChild(btn);
                    });
                } else { resultsDiv.innerHTML = '<div class="text-warning">Нічого не знайдено</div>'; }
            })
            .catch(err => { loader.classList.add('d-none'); resultsDiv.innerHTML = '<div class="text-danger">Помилка API</div>'; });
    }

    function fetchRawgGameDetails(gameId) {
        const loader = document.getElementById('rawg-loader');
        loader.classList.remove('d-none');

        Promise.all([
            fetch(`https://api.rawg.io/api/games/${gameId}?key=${RAWG_API_KEY}`).then(r => r.json()),
            fetch(`https://api.rawg.io/api/games/${gameId}/screenshots?key=${RAWG_API_KEY}`).then(r => r.json()),
            fetch(`https://api.rawg.io/api/games/${gameId}/achievements?key=${RAWG_API_KEY}`).then(r => r.json())
        ])
        .then(async ([gameData, screenshotsData, achievementsData]) => {
            document.getElementById('form-title').value = gameData.name || '';
            document.getElementById('form-release').value = gameData.released || '';
            document.getElementById('form-tags').value = translateGenres(gameData.genres);

            if (gameData.background_image) document.getElementById('form-cover').value = gameData.background_image;

            if (screenshotsData.results && screenshotsData.results.length > 0) {
                const screens = screenshotsData.results;
                if(screens[0]) document.getElementById('form-screen1').value = screens[0].image;
                if(screens[1]) document.getElementById('form-screen2').value = screens[1].image;
                if(screens[2]) document.getElementById('form-screen3').value = screens[2].image;
                if(screens[3]) document.getElementById('form-screen4').value = screens[3].image;
            }

            if (gameData.description) {
                const cleanDesc = gameData.description.replace(/<[^>]*>?/gm, ''); 
                document.getElementById('form-desc').value = await translateText(cleanDesc);
            }

            if (gameData.platforms) {
                document.querySelectorAll('.plat-cb').forEach(cb => cb.checked = false);
                gameData.platforms.forEach(p => {
                    let pName = p.platform.name.toLowerCase();
                    if (pName.includes('pc') || pName.includes('windows')) document.getElementById('pl1').checked = true;
                    if (pName.includes('playstation')) document.getElementById('pl2').checked = true;
                    if (pName.includes('xbox')) document.getElementById('pl3').checked = true;
                    if (pName.includes('nintendo') || pName.includes('switch')) document.getElementById('pl4').checked = true;
                    if (pName.includes('mac') || pName.includes('macos')) document.getElementById('pl5').checked = true;
                    if (pName.includes('linux')) document.getElementById('pl6').checked = true;
                });
            }

            const achContainer = document.getElementById('rawg-achievements-container');
            achContainer.innerHTML = '';

            if (achievementsData.results && achievementsData.results.length > 0) {
                const topAchievements = achievementsData.results.slice(0, 10);
                let achHTML = '<h5 class="text-accent mb-3">Знайдені досягнення (' + topAchievements.length + ' шт.)</h5>';

                const translatedAchievements = await Promise.all(topAchievements.map(async (ach) => {
                    const transTitle = await translateText(ach.name);
                    const transDesc = await translateText(ach.description);
                    return { ...ach, transTitle, transDesc };
                }));

                translatedAchievements.forEach(ach => {
                    achHTML += `
                    <div class="d-flex align-items-center mb-2 bg-dark p-2 rounded border border-secondary shadow-sm">
                        <img src="${ach.image}" style="width: 40px; height: 40px; margin-right: 10px;">
                        <div>
                            <input type="hidden" name="rawg_ach_title[]" value="${ach.transTitle.replace(/"/g, '&quot;')}">
                            <input type="hidden" name="rawg_ach_desc[]" value="${ach.transDesc.replace(/"/g, '&quot;')}">
                            <input type="hidden" name="rawg_ach_image[]" value="${ach.image}">
                            <strong class="text-white">${ach.transTitle}</strong>
                        </div>
                    </div>`;
                });
                achContainer.innerHTML = achHTML;
                document.getElementById('af4').checked = true; 
            }

            loader.classList.add('d-none');
            document.getElementById('rawg-results').innerHTML = '<div class="alert alert-success mt-2 fw-bold shadow-sm">Дані успішно завантажено!</div>';
        });
    }

    const heroSearchInput = document.getElementById('hero-search');
    const heroItems = Array.from(document.querySelectorAll('.hero-game-item'));
    const paginationContainer = document.getElementById('hero-pagination');
    let currentPage = 1; const itemsPerPage = 10;

    if (heroSearchInput && heroItems.length > 0) {
        function updateHeroView() {
            const searchTerm = heroSearchInput.value.toLowerCase();
            const filteredItems = heroItems.filter(item => item.getAttribute('data-title').includes(searchTerm));
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage) || 1;
            if (currentPage > totalPages) currentPage = totalPages;
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            heroItems.forEach(item => { item.style.setProperty('display', 'none', 'important'); });
            filteredItems.slice(startIndex, endIndex).forEach(item => { item.style.setProperty('display', 'flex', 'important'); });
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            paginationContainer.innerHTML = '';
            if (totalPages <= 1) return; 
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link bg-dark text-white border-secondary" href="#" data-page="${i}">${i}</a>`;
                    paginationContainer.appendChild(li);
                }
            }
            paginationContainer.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt(this.getAttribute('data-page'));
                    updateHeroView();
                });
            });
        }
        heroSearchInput.addEventListener('input', function() { currentPage = 1; updateHeroView(); });
        updateHeroView();
    }

    const apiSearchForm = document.getElementById('api-search-form');
    if (apiSearchForm) {
        apiSearchForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const query = document.getElementById('api-search-query').value;
            const resultsContainer = document.getElementById('api-search-results');
            resultsContainer.innerHTML = '<div class="text-white w-100">Шукаю...</div>';

            try {
                const response = await fetch(`../api/api_search.php?q=${query}`);
                const games = await response.json();
                resultsContainer.innerHTML = '';
                if (!games || games.length === 0) { resultsContainer.innerHTML = '<div class="text-warning">Нічого не знайдено.</div>'; return; }

                games.forEach(game => {
                    const bgImage = game.cover ? game.cover : `https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/${game.appid}/header.jpg`;
                    const gameId = game.id ? game.id : game.appid;
                    const card = `
                        <div class="col-md-4">
                            <div class="card bg-dark-green h-100 border-secondary shadow-sm transition-hover">
                                <img src="${bgImage}" class="card-img-top border-bottom border-secondary" style="height: 140px; object-fit: cover;" onerror="this.src=window.AppConfig.basePath + 'img/GameLib_logo.png'">
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="card-title text-white fs-6 mb-3 fw-bold">${game.name}</h6>
                                    <form method="POST" action="import_game.php" class="mt-auto m-0">
                                        <input type="hidden" name="api_game_id" value="${gameId}">
                                        <button type="submit" class="btn btn-outline-success btn-sm w-100 fw-bold"><i class="fas fa-download me-1"></i> Імпортувати</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                    resultsContainer.innerHTML += card;
                });
            } catch (error) { resultsContainer.innerHTML = '<div class="text-danger fw-bold w-100">Помилка з\'єднання.</div>'; }
        });
    }

    const container = document.getElementById('achievements-container');
    const addBtn = document.getElementById('add-more-btn');
    let count = 1;
    if(addBtn) addBtn.addEventListener('click', function() {
        count++;
        const newGroup = document.createElement('div');
        newGroup.className = 'achievement-input-group border border-secondary rounded p-3 mb-3 bg-dark';
        newGroup.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3"><h5 class="text-white mb-0">Досягнення #${count}</h5><button type="button" class="btn btn-sm btn-danger remove-btn" onclick="this.parentElement.parentElement.remove()">x</button></div><div class="row"><div class="col-md-4 mb-3"><input type="text" name="ach_title[]" class="form-control" placeholder="Назва" required></div><div class="col-md-4 mb-3"><input type="text" name="ach_desc[]" class="form-control" placeholder="Опис" required></div><div class="col-md-4 mb-3"><input type="file" name="ach_icon[]" class="form-control" accept="image/*" required></div></div>`;
        container.appendChild(newGroup);
    });
});
