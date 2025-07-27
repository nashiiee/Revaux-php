// JS for category sidebar dropdown (Headwear, Tops, etc.)
export function setupCategoryDropdown() {
    const arrows = document.querySelectorAll('.category-dropdown .arrow');
    const dropdowns = document.querySelectorAll('.category-dropdown');

    // Toggle dropdown on arrow click
    arrows.forEach(arrow => {
        arrow.addEventListener('click', function (e) {
            e.stopPropagation();
            const parent = this.closest('.category-dropdown');
            parent.classList.toggle('active');
        });
    });

    // Automatically open the category based on URL
    const params = new URLSearchParams(window.location.search);
    const currentCategory = params.get('category');

    if (currentCategory) {
        dropdowns.forEach(dropdown => {
            if (
                dropdown.dataset.category &&
                dropdown.dataset.category.toLowerCase() === currentCategory.toLowerCase()
            ) {
                dropdown.classList.add('active');
            } else {
                dropdown.classList.remove('active'); // Optional: collapse others
            }
        });
    }
}

