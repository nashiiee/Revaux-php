// JS for category sidebar dropdown ( Headwear, Tops, etc.)
export function setupCategoryDropdown() {
    const arrows = document.querySelectorAll('.category-dropdown .arrow');

    arrows.forEach(arrow => {
        arrow.addEventListener('click', function (e) {
        e.stopPropagation();
        const parent = this.closest('.category-dropdown');
        parent.classList.toggle('active');
        });
    });
}
