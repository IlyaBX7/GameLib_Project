document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ajax-filter-form');
    const searchInput = document.getElementById('filter-search');
    const sortSelect = document.getElementById('filter-sort');
    const checkboxes = document.querySelectorAll('.filter-checkbox');
    const pageInput = document.getElementById('filter-page');
    const gridContainer = document.getElementById('games-grid-container');
    const loader = document.getElementById('filter-loader');

    function fetchGames() {
        gridContainer.style.opacity = '0.4'; 
        loader.classList.remove('d-none');

        const formData = new FormData(form);

        fetch('genres.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            gridContainer.innerHTML = html;
            loader.classList.add('d-none');
            gridContainer.style.opacity = '1';
            attachPaginationListeners();
        })
        .catch(error => {
            console.error('Помилка AJAX фільтрації:', error);
            loader.classList.add('d-none');
            gridContainer.style.opacity = '1';
        });
    }

    function attachPaginationListeners() {
        document.querySelectorAll('.ajax-page').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parentLi = this.parentElement;
                if (parentLi.classList.contains('disabled') || parentLi.classList.contains('active')) return;

                const targetPage = this.getAttribute('data-page');
                pageInput.value = targetPage;
                fetchGames();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        pageInput.value = 1; 
        searchTimeout = setTimeout(fetchGames, 500); 
    });

    sortSelect.addEventListener('change', function() {
        pageInput.value = 1;
        fetchGames();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            pageInput.value = 1; 
            fetchGames();
        });
    });

    fetchGames();
});
