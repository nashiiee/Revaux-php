export function setupCartSubmission() {
    const form = document.querySelector('.pd-actions-form');
    const addToCartBtn = form?.querySelector('.add-cart');

    // Get the existing notification element from the page.
    // We are no longer creating a new one.
    const feedbackBox = document.querySelector('.cart-feedback');

    if (!form || !addToCartBtn || !feedbackBox) return;

    addToCartBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            // Show success
            feedbackBox.textContent = '✅ Added to cart!';
            feedbackBox.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => feedbackBox.classList.remove('show'), 3000);
        })
        .catch(err => {
            feedbackBox.textContent = '❌ Failed to add to cart.';
            feedbackBox.classList.add('show');
            setTimeout(() => feedbackBox.classList.remove('show'), 3000);
        });
    });
}
