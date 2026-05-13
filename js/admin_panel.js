

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
        const tagsArray = game.tags ? game.tags.split(',').map(t => t.trim()) : [];
        document.querySelectorAll('.edit-tag-cb').forEach(cb => { cb.checked = tagsArray.includes(cb.value); });
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

        const featuresArray = game.features ? game.features.split(',').map(f => f.trim()) : [];
        document.querySelectorAll('.edit-feature-cb').forEach(cb => { cb.checked = featuresArray.includes(cb.value); });

        const langsArray = game.languages ? game.languages.split(',') : [];
        document.querySelectorAll('.edit-lang-cb').forEach(cb => { cb.checked = langsArray.includes(cb.value); });

        const platArray = game.platforms ? game.platforms.split(',') : [];
        document.querySelectorAll('.edit-plat-cb').forEach(cb => { cb.checked = platArray.includes(cb.value); });
    }
});

const RAWG_API_KEY = '288baf617f204b65ba0a773a0ed39ef0'; 

const genreDictionary = { "Action": "Екшен", "Indie": "Інді", "Adventure": "Пригоди", "RPG": "РПГ", "Role-playing (RPG)": "РПГ", "Strategy": "Стратегія", "Shooter": "Шутер", "Racing": "Гонки", "Simulation": "Симулятор", "Sports": "Спорт", "Puzzle": "Головоломка", "Platformer": "Платформер", "Fighting": "Файтинг", "Family": "Сімейна бібліотека" };
const tagDictionary = { "horror": "Хоррор", "survival": "Виживання", "open world": "Відкритий світ", "sandbox": "Пісочниця", "singleplayer": "Однокористувацька гра", "multiplayer": "Багатокористувацька гра", "co-op": "Кооперативна гра", "achievements": "Досягнення", "full controller support": "Повна підтримка контролерів", "steam trading cards": "Колекційні картки Steam", "split screen": "Спільний/розділений екран", "cross-platform multiplayer": "Міжплатформна багатокористувацька гра" };
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
            document.querySelectorAll('.tag-cb, .feat-cb').forEach(cb => cb.checked = false);
            let rawgGenres = (gameData.genres || []).map(g => genreDictionary[g.name] || g.name);
            let rawgTags = (gameData.tags || []).map(t => tagDictionary[t.slug] || tagDictionary[t.name.toLowerCase()] || t.name);
            let combined = [...rawgGenres, ...rawgTags];
            document.querySelectorAll('.tag-cb').forEach(cb => { if (combined.includes(cb.value)) cb.checked = true; });
            document.querySelectorAll('.feat-cb').forEach(cb => { if (combined.includes(cb.value)) cb.checked = true; });

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

    const steamSearchBtn = document.getElementById('steam-search-btn');
    if (steamSearchBtn) {
        steamSearchBtn.addEventListener('click', async function() {
            const query = document.getElementById('steam-search-input').value;
            if (!query) return;
            const loader = document.getElementById('steam-loader');
            const resultsContainer = document.getElementById('steam-results');
            loader.classList.remove('d-none');
            resultsContainer.innerHTML = '';
            try {
                const response = await fetch(`../api/api_search.php?q=${encodeURIComponent(query)}`);
                const games = await response.json();
                loader.classList.add('d-none');
                if (!games || games.length === 0) {
                    resultsContainer.innerHTML = '<div class="list-group-item bg-dark-green text-warning">Нічого не знайдено.</div>';
                    return;
                }
                games.forEach(game => {
                    const btn = document.createElement('button');
                    btn.className = 'list-group-item list-group-item-action bg-dark-green text-white border-secondary d-flex align-items-center';
                    const bgImage = game.cover ? game.cover : `https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/${game.appid}/header.jpg`;
                    btn.innerHTML = `<img src="${bgImage}" style="width:40px;height:40px;object-fit:cover;margin-right:10px;border-radius:4px;"> <strong>${game.name}</strong>`;
                    btn.onclick = (e) => { e.preventDefault(); fetchSteamGameDetails(game.appid); };
                    resultsContainer.appendChild(btn);
                });
            } catch (error) {
                console.error(error);
                loader.classList.add('d-none');
                resultsContainer.innerHTML = '<div class="list-group-item bg-dark-green text-danger">Помилка пошуку</div>';
            }
        });

        async function fetchSteamGameDetails(appid) {
            const loader = document.getElementById('steam-loader');
            const resultsContainer = document.getElementById('steam-results');
            loader.classList.remove('d-none');
            resultsContainer.innerHTML = '<div class="text-warning my-2 fw-bold text-center">Завантаження деталей зі Steam...</div>';
            
            try {
                const response = await fetch(`../api/get_steam_details.php?appid=${appid}`);
                const data = await response.json();
                
                if (data.error) {
                    resultsContainer.innerHTML = `<div class="alert alert-danger mt-2">${data.error}</div>`;
                    loader.classList.add('d-none');
                    return;
                }

                document.getElementById('steam-form-title').value = data.title || '';
                document.getElementById('steam-form-cover').value = data.cover_url || '';
                if (data.release_date) document.getElementById('steam-form-release').value = data.release_date;
                if (data.developer) document.getElementById('steam-form-developer').value = data.developer;
                if (data.publisher) document.getElementById('steam-form-publisher').value = data.publisher;
                if (data.sys_min) document.getElementById('steam-form-sys-min').value = data.sys_min;
                if (data.sys_rec) document.getElementById('steam-form-sys-rec').value = data.sys_rec;

                if (data.description) {
                    const cleanDesc = data.description.replace(/<[^>]*>?/gm, ''); 
                    document.getElementById('steam-form-desc').value = await translateText(cleanDesc);
                }

                data.screenshots.forEach((url, i) => {
                    const id = `steam-form-screen${i+1}`;
                    const el = document.getElementById(id);
                    if (el) el.value = url;
                });

                document.querySelectorAll('.steam-tag-cb').forEach(cb => cb.checked = false);
                if (data.tags) {
                    data.tags.forEach(t => {
                        const tName = t.toLowerCase();
                        document.querySelectorAll('.steam-tag-cb').forEach(cb => {
                            if (tName.includes(cb.value.toLowerCase())) cb.checked = true;
                        });
                    });
                }

                document.querySelectorAll('.steam-feat-cb').forEach(cb => cb.checked = false);
                if (data.features) {
                    data.features.forEach(f => {
                        const fName = f.toLowerCase();
                        document.querySelectorAll('.steam-feat-cb').forEach(cb => {
                            if (fName.includes('single') || fName.includes('одиночна')) document.getElementById('saf0').checked = true;
                            if (fName.includes('multi') || fName.includes('багатокор')) document.getElementById('saf1').checked = true;
                            if (fName.includes('co-op') || fName.includes('кооп')) document.getElementById('saf2').checked = true;
                            if (fName.includes('achieve') || fName.includes('досяг')) document.getElementById('saf3').checked = true;
                            if (fName.includes('controller') || fName.includes('контрол')) document.getElementById('saf4').checked = true;
                            if (fName.includes('cards') || fName.includes('картки')) document.getElementById('saf5').checked = true;
                        });
                    });
                }

                document.querySelectorAll('.steam-lang-cb').forEach(cb => cb.checked = false);
                if (data.languages) {
                    const langs = data.languages.toLowerCase();
                    if (langs.includes('ukrainian') || langs.includes('українська')) document.getElementById('sal1').checked = true;
                    if (langs.includes('english') || langs.includes('англійська')) document.getElementById('sal2').checked = true;
                    if (langs.includes('french') || langs.includes('французька')) document.getElementById('sal3').checked = true;
                    if (langs.includes('german') || langs.includes('німецька')) document.getElementById('sal4').checked = true;
                    if (langs.includes('spanish') || langs.includes('іспанська')) document.getElementById('sal5').checked = true;
                }

                document.querySelectorAll('.steam-plat-cb').forEach(cb => cb.checked = false);
                document.getElementById('spl1').checked = true; 

                const achContainer = document.getElementById('steam-achievements-container'); 
                achContainer.innerHTML = '';
                if (data.achievements && data.achievements.length > 0) {
                    let achHTML = '<h5 class="text-warning mb-3">Знайдені досягнення (' + data.achievements.length + ' шт.)</h5>';
                    const topAchievements = data.achievements.slice(0, 10);
                    
                    for (const ach of topAchievements) {
                        const transTitle = await translateText(ach.title); 
                        const transDesc = await translateText(ach.description);
                        achHTML += `<div class="d-flex align-items-center mb-2 bg-dark p-2 rounded border border-secondary shadow-sm"><img src="${ach.icon}" style="width: 40px; height: 40px; margin-right: 10px;"><div><input type="hidden" name="rawg_ach_title[]" value="${transTitle.replace(/"/g, '&quot;')}"><input type="hidden" name="rawg_ach_desc[]" value="${transDesc.replace(/"/g, '&quot;')}"><input type="hidden" name="rawg_ach_image[]" value="${ach.icon}"><strong class="text-white">${transTitle}</strong></div></div>`;
                    }
                    achContainer.innerHTML = achHTML; 
                    document.getElementById('saf3').checked = true; 
                }

                loader.classList.add('d-none');
                resultsContainer.innerHTML = '<div class="alert alert-success mt-2 fw-bold shadow-sm">Дані зі Steam успішно завантажено! Перевірте форму.</div>';
            } catch (err) {
                console.error(err);
                loader.classList.add('d-none');
                resultsContainer.innerHTML = '<div class="alert alert-danger mt-2">Помилка завантаження даних зі Steam.</div>';
            }
        }
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
