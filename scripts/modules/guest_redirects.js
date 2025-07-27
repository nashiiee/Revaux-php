export function setupGuestRedirects() {
    const guestButtons = document.querySelectorAll('.guest-only');

    guestButtons.forEach(button => {
        button.addEventListener('click', (e) => {
        e.preventDefault();
        window.location.href = '../../pages/authentication/login.html';
        });
    });
}
