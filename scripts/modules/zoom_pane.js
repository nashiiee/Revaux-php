export function setupZoomPane() {
    const mainImg    = document.querySelector('.pd-main-image img');
    const zoomWindow = document.querySelector('.pd-zoom-window');
    const detailPane = document.querySelector('.product-info-page');

    // Safeguard: exit if any of the required elements are missing
    if (!mainImg || !zoomWindow || !detailPane) return;

    detailPane.style.position = 'relative';

    mainImg.addEventListener('mouseenter', () => {
        zoomWindow.style.display = 'block';
        zoomWindow.style.backgroundImage = `url("${mainImg.src}")`;
    });

    mainImg.addEventListener('mousemove', e => {
        const rect = mainImg.getBoundingClientRect();
        const xPct = (e.clientX - rect.left) / rect.width * 100;
        const yPct = (e.clientY - rect.top) / rect.height * 100;

        zoomWindow.style.backgroundPosition = `${xPct}% ${yPct}%`;
    });

    mainImg.addEventListener('mouseleave', () => {
        zoomWindow.style.display = 'none';
    });
}
