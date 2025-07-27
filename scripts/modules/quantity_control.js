// JS for the quantity input
export function setupQuantityControl() {
    const minusBtn = document.querySelector('.qty-btn-minus');
    const plusBtn = document.querySelector('.qty-btn-plus');
    const qtyInput = document.querySelector('.qty-input');
    const stockWarning = document.getElementById('stock-warning');
    const minQty = 1;

    // Get max from JS variable
    const maxQty = typeof MAX_QUANTITY !== 'undefined' ? MAX_QUANTITY : 99;

    if (!minusBtn || !plusBtn || !qtyInput) return;

    let holdTimeout;
    let holdInterval;
    let holdSpeed = 300; // initial repeat speed in ms
    let speedStep = 25;  // how much faster each step gets
    let minSpeed = 50;   // fastest allowed speed

    function updateMinusBtnState() {
        const currentValue = parseInt(qtyInput.value);
        minusBtn.disabled = currentValue <= minQty;
        plusBtn.disabled = currentValue >= maxQty;

        // Show warning only when stock is 20 or less
        if (stockWarning) {
            if (maxQty <= 20) {
                stockWarning.textContent = `Only ${maxQty} left!`;
                stockWarning.style.display = 'inline';
            } else {
                stockWarning.style.display = 'none';
            }
        }
    }

    function changeQty(amount) {
        let currentValue = parseInt(qtyInput.value);
        if (isNaN(currentValue)) currentValue = minQty;
        let newValue = currentValue + amount;
        if (newValue < minQty) newValue = minQty;
        if (newValue > maxQty) newValue = maxQty;
        qtyInput.value = newValue;
        updateMinusBtnState();
    }

    function startHold(amount) {
        // First click
        changeQty(amount);

        // Wait 0.5s before repeating
        holdTimeout = setTimeout(() => {
            let speed = holdSpeed;
            function repeat() {
                changeQty(amount);
                speed = Math.max(minSpeed, speed - speedStep);
                holdInterval = setTimeout(repeat, speed);
            }
            repeat();
        }, 500); // <-- this is the delay before auto increment starts(adjustable)
    }

    function stopHold() {
        clearTimeout(holdTimeout);
        clearTimeout(holdInterval);
    }

    // Button events
    minusBtn.addEventListener('mousedown', () => startHold(-1));
    plusBtn.addEventListener('mousedown', () => startHold(1));
    document.addEventListener('mouseup', stopHold);
    document.addEventListener('mouseleave', stopHold);

    // Touch support
    minusBtn.addEventListener('touchstart', (e) => {
        e.preventDefault();
        startHold(-1);
    }, { passive: false });

    plusBtn.addEventListener('touchstart', (e) => {
        e.preventDefault();
        startHold(1);
    }, { passive: false });

    document.addEventListener('touchend', stopHold);

    // Manual input validation
    qtyInput.addEventListener('blur', () => {
        let value = parseInt(qtyInput.value);
        if (isNaN(value) || value < minQty) {
            value = minQty;
        } else if (value > maxQty) {
            value = maxQty;
        }
        qtyInput.value = value;
        updateMinusBtnState();
    });

    updateMinusBtnState();

    // Disable Buy Now and Add to Cart if stock is 0
    const buyNowBtn = document.querySelector('.buy-now');
    const addCartBtn = document.querySelector('.add-cart');

    if (maxQty <= 0) {
        if (buyNowBtn) {
            buyNowBtn.disabled = true;
            buyNowBtn.classList.add('disabled');
            buyNowBtn.textContent = 'Out of Stock';
        }

        if (addCartBtn) {
            addCartBtn.disabled = true;
            addCartBtn.classList.add('disabled');
            addCartBtn.textContent = 'Out of Stock';
        }
    }

    // Prevent Enter from submitting unless stock is available and size is selected(if available from the database)
    const form = document.querySelector('.pd-actions-form');
    const sizeInput = document.getElementById('selected-size');
    const sizeButtons = document.querySelectorAll('.size-btn');

    if (form) {
        form.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const isSubmitBtn = e.target.closest('button[type="submit"]');
                const stockAvailable = maxQty > 0;
                const sizeRequired = sizeButtons.length > 0;
                const sizeSelected = sizeInput && sizeInput.value.trim() !== '';

                const allowSubmit = stockAvailable && (!sizeRequired || sizeSelected);

                if (!isSubmitBtn && !allowSubmit) {
                    e.preventDefault();
                }
            }
        });
    }

    // Block Buy/Add if size not selected or stock is 0
    function preventInvalidSubmission(e) {
        const stockAvailable = maxQty > 0;
        const sizeRequired = sizeButtons.length > 0;
        const sizeSelected = sizeInput && sizeInput.value.trim() !== '';

        const allowSubmit = stockAvailable && (!sizeRequired || sizeSelected);

        if (!allowSubmit) {
            e.preventDefault();

            // Flash warning class on button
            e.target.classList.add('shake-error');
            setTimeout(() => e.target.classList.remove('shake-error'), 600);

            // Optional: show native tooltip
            e.target.title = !stockAvailable
                ? 'Out of Stock'
                : !sizeSelected
                    ? 'Please select a size'
                    : '';
        }
    }

    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', preventInvalidSubmission);
    }
    if (addCartBtn) {
        addCartBtn.addEventListener('click', preventInvalidSubmission);
    }
}
