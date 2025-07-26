export function setupImageGallery() {
    const mainImg = document.querySelector('.pd-main-image img');
    const thumbs = document.querySelectorAll('.pd-thumbnails .thumb');
    const colorEl = document.querySelector('.pd-color');

    if (!mainImg || thumbs.length === 0) return;

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            // Ignore if already active
            const img = thumb.querySelector('img');
            if (!img) return;

            // Update main image
            mainImg.src = img.src;

            // Set active thumbnail
            thumbs.forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');

            // Update color label
            const newColor = thumb.dataset.color;
            if (newColor && colorEl) {
                colorEl.textContent = newColor;
            }
        });
    });
}
