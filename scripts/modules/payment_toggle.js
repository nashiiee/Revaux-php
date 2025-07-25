export function setupPaymentToggle() {
    const options = document.querySelectorAll('.payment-option');
    if (!options.length) return; // Exit if no options found

    options.forEach(option => {
        option.addEventListener('click', () => {
        options.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');

        const input = option.querySelector('input');
        if (input) input.checked = true;
        });
    });
}
