export function setupSizeToggle() {
    
    const sizeButtons = document.querySelectorAll('.size-btn');
    const sizeInput = document.getElementById('selected-size');

    if (!sizeButtons.length || !sizeInput) return;
    

    sizeButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            sizeButtons.forEach(btn => btn.classList.remove('selected'));

            // Add active class to clicked button
            button.classList.add('selected');

            // Set the hidden input value
            sizeInput.value = button.textContent.trim();
        });
    });
}
