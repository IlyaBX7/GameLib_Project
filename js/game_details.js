document.addEventListener('DOMContentLoaded', function() {

    const ratingForm = document.getElementById('ajax-rating-form');
    if (ratingForm) {
        ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('ajax_action', 'manage_library');

            fetch('game_details.php?id=' + window.GameDetailsData.gameId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('rating-msg').innerHTML = `<div class="alert alert-success mb-3 p-2 text-center small shadow-sm"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
                    const btn = document.getElementById('rating-btn');
                    btn.innerHTML = data.btn_text;
                    btn.className = 'btn w-100 fw-bold ' + data.btn_class;

                    if (data.rating_count > 0) {
                        document.getElementById('comm-rating-val').innerHTML = `<i class="fas fa-star"></i> ${data.avg_rating}<span class="fs-5 text-white-50">/10</span>`;
                        document.getElementById('comm-rating-count').textContent = `На основі ${data.rating_count} оцінок`;
                        document.getElementById('comm-rating-count').classList.remove('d-none');
                    }
                }
            })
            .catch(error => console.error('Помилка AJAX:', error));
        });
    }

    const rateButtons = document.querySelectorAll('.ajax-rate-btn');
    rateButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const reviewId = this.dataset.reviewId;
            const isHelpful = this.dataset.helpful;

            const formData = new FormData();
            formData.append('ajax_action', 'rate_review');
            formData.append('review_id', reviewId);
            formData.append('is_helpful', isHelpful);

            fetch('game_details.php?id=' + window.GameDetailsData.gameId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const container = this.closest('.d-flex');
                    const likeBtn = container.querySelector('.btn-like');
                    const dislikeBtn = container.querySelector('.btn-dislike');

                    likeBtn.querySelector('.like-count').textContent = data.likes;
                    dislikeBtn.querySelector('.dislike-count').textContent = data.dislikes;

                    if (isHelpful == '1') {
                        likeBtn.classList.replace('btn-outline-success', 'btn-success');
                        dislikeBtn.classList.replace('btn-danger', 'btn-outline-danger');
                    } else {
                        dislikeBtn.classList.replace('btn-outline-danger', 'btn-danger');
                        likeBtn.classList.replace('btn-success', 'btn-outline-success');
                    }
                }
            })
            .catch(error => console.error('Помилка AJAX:', error));
        });
    });

    const gameTitle = window.GameDetailsData.gameTitle;
    const trackerContainer = document.getElementById('cheapshark-tracker');

    const storeData = {
        "1": { name: "Steam", icon: "fab fa-steam", color: "#66c0f4" },
        "2": { name: "GamersGate", icon: "fas fa-gamepad", color: "#fff" },
        "3": { name: "GreenManGaming", icon: "fas fa-leaf", color: "#a2c423" },
        "7": { name: "GOG", icon: "fas fa-ghost", color: "#bf00ff" },
        "8": { name: "Origin/EA", icon: "fab fa-ea", color: "#ff5f00" },
        "11": { name: "Humble Store", icon: "fas fa-gift", color: "#cc292b" },
        "25": { name: "Epic Games", icon: "fas fa-infinity", color: "#ffffff" }
    };

    if (gameTitle) {
        fetch(`https://www.cheapshark.com/api/1.0/deals?title=${encodeURIComponent(gameTitle)}&sortBy=Price&exact=0`)
            .then(response => response.json())
            .then(deals => {
                trackerContainer.innerHTML = ''; 

                let exactDeals = deals.filter(d => d.title.toLowerCase() === gameTitle.toLowerCase());
                if (exactDeals.length === 0) {
                    const sanitize = str => str.toLowerCase().replace(/[^a-z0-9]/g, '');
                    exactDeals = deals.filter(d => sanitize(d.title) === sanitize(gameTitle));
                }
                if (exactDeals.length === 0) {
                    exactDeals = deals.filter(d => d.title.toLowerCase().includes(gameTitle.toLowerCase()));
                }
                exactDeals = exactDeals.slice(0, 4);

                if (exactDeals.length === 0) {
                    trackerContainer.innerHTML = '<div class="alert alert-dark border-secondary text-white-50 shadow-sm"><i class="fas fa-info-circle me-2"></i> На жаль, пропозицій не знайдено. Можливо, гра є ексклюзивом для консолей (PlayStation, Nintendo тощо) або не продається в популярних цифрових магазинах для ПК.</div>';
                    return;
                }

                let html = '<div class="list-group list-group-flush rounded border border-secondary shadow-sm">';
                exactDeals.forEach(deal => {
                    const store = storeData[deal.storeID] || { name: "Інший магазин", icon: "fas fa-store", color: "#ccc" };
                    const isDiscounted = parseFloat(deal.savings) > 0;

                    html += `
                        <a href="https://www.cheapshark.com/redirect?dealID=${deal.dealID}" target="_blank" class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center p-3 transition-hover">
                            <div class="d-flex align-items-center">
                                <i class="${store.icon} fs-2 me-3" style="color: ${store.color}; width: 35px; text-align: center;"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">${store.name}</h6>
                                    <small class="text-white-50">${deal.title}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                ${isDiscounted ? `<span class="badge bg-danger me-2 mb-1">- ${Math.round(deal.savings)}%</span><br>` : ''}
                                ${isDiscounted ? `<small class="text-decoration-line-through text-white-50 me-2">$${deal.normalPrice}</small>` : ''}
                                <strong class="text-success fs-5">$${deal.salePrice}</strong>
                            </div>
                        </a>
                    `;
                });
                html += '</div><div class="text-end mt-2"><small class="text-white-50">Дані надано CheapShark API</small></div>';
                trackerContainer.innerHTML = html;
            })
            .catch(err => {
                trackerContainer.innerHTML = '<div class="text-danger fw-bold"><i class="fas fa-times-circle me-2"></i> Помилка завантаження цін.</div>';
            });
    }

});
