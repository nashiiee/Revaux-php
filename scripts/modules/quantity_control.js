// JS for the quantity input
// setupQuantityControl: supports single or multiple quantity controls
// selectorOrElement: a selector string or an Element or NodeList (default: product_info page)
// options: { maxQty, minQty, onChange }
// Original single-control quantity logic for product_info page only
export function setupQuantityControl() {
    if (document.querySelector('.cart-category-box')) {
        console.log('quantity_control.js: Cart page detected. Skipping setupQuantityControl to avoid conflict.');
        return; // Exit the function if on the cart page
    }
    const minusBtn = document.querySelector('.qty-btn-minus');
    const plusBtn = document.querySelector('.qty-btn-plus');
    const qtyInput = document.querySelector('.qty-input');
    const minQty = 1;
    const maxQty = typeof MAX_QUANTITY !== 'undefined' ? MAX_QUANTITY : 99;
    let holdTimeout;
    let holdInterval;
    let holdSpeed = 300;
    let speedStep = 25;
    let minSpeed = 50;
    let hasHeld = false;

    function updateBtnState() {
        const currentValue = parseInt(qtyInput.value);
        minusBtn.disabled = currentValue <= minQty;
        plusBtn.disabled = currentValue >= maxQty;
        // Stock warning
        const stockWarning = document.getElementById('stock-warning');
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
        if (newValue !== currentValue) {
            qtyInput.value = newValue;
            const hidden = document.getElementById('hidden-quantity');
            if (hidden) hidden.value = newValue;
            updateBtnState();
        }
    }

    function startHold(amount) {
        changeQty(amount);
        hasHeld = false;
        holdTimeout = setTimeout(() => {
            hasHeld = true;
            let speed = holdSpeed;
            function repeat() {
                const currentValue = parseInt(qtyInput.value);
                if ((amount < 0 && currentValue <= minQty) || (amount > 0 && currentValue >= maxQty)) {
                    return;
                }
                changeQty(amount);
                speed = Math.max(minSpeed, speed - speedStep);
                holdInterval = setTimeout(repeat, speed);
            }
            repeat();
        }, 500);
    }
    function stopHold() {
        clearTimeout(holdTimeout);
        clearTimeout(holdInterval);
    }

    minusBtn.addEventListener('mousedown', () => startHold(-1));
    plusBtn.addEventListener('mousedown', () => startHold(1));
    minusBtn.addEventListener('touchstart', (e) => { e.preventDefault(); startHold(-1); }, { passive: false });
    plusBtn.addEventListener('touchstart', (e) => { e.preventDefault(); startHold(1); }, { passive: false });
    document.addEventListener('mouseup', stopHold);
    document.addEventListener('mouseleave', stopHold);
    document.addEventListener('touchend', stopHold);

    qtyInput.addEventListener('blur', () => {
        let value = parseInt(qtyInput.value);
        if (isNaN(value) || value < minQty) {
            value = minQty;
        } else if (value > maxQty) {
            value = maxQty;
        }
        qtyInput.value = value;
        const hidden = document.getElementById('hidden-quantity');
        if (hidden) hidden.value = value;
        updateBtnState();
    });

    updateBtnState();
}
