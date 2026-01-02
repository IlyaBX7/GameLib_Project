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