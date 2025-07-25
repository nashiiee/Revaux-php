// JS for custom "Sort by" dropdown in product grid
export function setupSortDropdown() {
    const dropdown = document.getElementById('sort-dropdown');
    if (!dropdown) return;

    const selected = dropdown.querySelector('.custom-dropdown-selected');
    const list = dropdown.querySelector('.custom-dropdown-list');
    const options = dropdown.querySelectorAll('.custom-dropdown-list li');
    const selectedText = document.getElementById('sort-selected-text');

    // Toggle dropdown open/close
    selected.addEventListener('click', function (e) {
        dropdown.classList.toggle('open');
    });

    // Option click
    options.forEach(option => {
        option.addEventListener('click', function () {
        options.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        selectedText.textContent = this.textContent;
        dropdown.classList.remove('open');
        // You can trigger your sort logic here using this.dataset.value
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
        }
    });

    // Keyboard accessibility
    selected.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        dropdown.classList.toggle('open');
        }
        if (e.key === 'Escape') {
        dropdown.classList.remove('open');
        }
    });
}
