export function setupAboutToggle() {
    const wrapper = document.querySelector('.pd-about-wrapper');
    const toggle  = document.querySelector('.pd-about-toggle');

    if (!wrapper || !toggle) return;
    // Only show toggle if content is taller than limit(10 lines)
    const lineHeight = parseFloat(getComputedStyle(wrapper).lineHeight);
    const maxH = lineHeight * 10;
    if (wrapper.scrollHeight <= maxH) {
        toggle.style.display = 'none';
        return;
    }

    // On click, toggle expanded class + change button text
    toggle.addEventListener('click', () => {
        const expanded = wrapper.classList.toggle('expanded');
        toggle.textContent = expanded ? '▲ See less' : '▼ See more';
    });
}