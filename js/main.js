document.addEventListener('DOMContentLoaded', function() {

    const gameItems = document.querySelectorAll('.release-item');

    const previewTitle = document.getElementById('preview-title');
    const previewImg1 = document.getElementById('preview-img1');
    const previewDescription = document.getElementById('preview-description');
    const previewButton = document.getElementById('preview-button');

    gameItems.forEach(item => {

        item.addEventListener('click', function(event) {
            event.preventDefault();

            const title = item.dataset.title;
            const img1 = item.dataset.img1;
            const description = item.dataset.description;
            const url = item.dataset.url;

            previewTitle.textContent = title;
            previewImg1.src = img1;
            previewImg1.alt = title;
            previewDescription.textContent = description;

            previewButton.href = url;
            previewButton.classList.remove('d-none');

            gameItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });

});

document.addEventListener('DOMContentLoaded', function() {
    const carouselElement = document.getElementById('heroNewsCarousel');
    if (!carouselElement) {
        return;
    }
    const carousel = new bootstrap.Carousel(carouselElement, {
      interval: false,
      pause: false
    });
    const animationEndCallback = function(event) {
        if (event.animationName === 'timer-bar-animation') {
            carousel.next();
        }
    };
    carouselElement.addEventListener('animationend', animationEndCallback);
    carouselElement.addEventListener('slide.bs.carousel', function () {
        carouselElement.removeEventListener('animationend', animationEndCallback);
    });
    carouselElement.addEventListener('slid.bs.carousel', function () {
        carouselElement.addEventListener('animationend', animationEndCallback);
    });

});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search-input');
    const searchResults = document.getElementById('live-search-results');
    const bp = window.AppConfig ? window.AppConfig.basePath : '';

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.trim();
            if (q.length < 2) { searchResults.classList.add('d-none'); return; }

            fetch(bp + 'api/api.php?action=live_search&q=' + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(game => {
                            searchResults.innerHTML += `
                                <a href="${bp}game_details.php?id=${game.id}" class="d-flex align-items-center p-2 text-decoration-none border-bottom border-secondary text-white hover-game-item">
                                    <img src="${game.cover_url}" style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px;" class="me-2 border border-secondary">
                                    <span class="small text-truncate fw-bold">${game.title}</span>
                                </a>`;
                        });
                        searchResults.innerHTML += `<a href="${bp}search.php?query=${encodeURIComponent(q)}" class="d-block p-2 text-center text-accent small fw-bold text-decoration-none" style="background: rgba(0,255,100,0.1);">Переглянути всі результати</a>`;
                        searchResults.classList.remove('d-none');
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-white-50 small text-center">Нічого не знайдено</div>';
                        searchResults.classList.remove('d-none');
                    }
                });
        });
        document.addEventListener('click', e => { if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) searchResults.classList.add('d-none'); });
        searchInput.addEventListener('keypress', e => { if(e.key === 'Enter') window.location.href = bp + 'search.php?query=' + encodeURIComponent(searchInput.value); });
    }

    if (document.getElementById('notif-badge')) {
        fetchNotifications();
        setInterval(fetchNotifications, 30000); 
    }
});

function fetchNotifications() {
    const bp = window.AppConfig ? window.AppConfig.basePath : '';
    fetch(bp + 'api/api.php?action=get_notifications')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notif-badge');
            const list = document.getElementById('notif-list');
            if(!badge || !list) return;

            if (data.unread > 0) {
                badge.textContent = data.unread;
                badge.classList.remove('d-none');
            } else { badge.classList.add('d-none'); }

            if (data.items.length > 0) {
                list.innerHTML = '';
                data.items.forEach(item => {
                    const bgClass = item.is_read == 0 ? 'bg-dark' : 'bg-dark-green';
                    list.innerHTML += `
                        <li>
                            <a class="dropdown-item ${bgClass} border-bottom border-secondary text-wrap p-3 d-block" href="${item.link ? bp + item.link : '#'}">
                                <small class="text-white d-block mb-1" style="line-height: 1.4;">${item.message}</small>
                                <small class="text-accent" style="font-size: 0.75rem;"><i class="far fa-clock"></i> ${item.created_at}</small>
                            </a>
                        </li>`;
                });
            } else {
                list.innerHTML = '<li class="p-3 text-center text-white-50 small">Немає нових сповіщень</li>';
            }
        });
}

function markNotificationsRead() {
    const bp = window.AppConfig ? window.AppConfig.basePath : '';
    fetch(bp + 'api/api.php?action=mark_read').then(() => {
        const badge = document.getElementById('notif-badge');
        if(badge) badge.classList.add('d-none');
    });
}