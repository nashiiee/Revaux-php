// JS for the quantity input
export function setupQuantityControl() {
    const minusBtn = document.querySelector('.qty-btn-minus');
    const plusBtn = document.querySelector('.qty-btn-plus');
    const qtyInput = document.querySelector('.qty-input');
    const minQty = 1;

    if (!minusBtn || !plusBtn || !qtyInput) return;

    let holdTimeout;
    let holdInterval;
    let holdSpeed = 300; // initial repeat speed in ms
    let speedStep = 25;  // how much faster each step gets
    let minSpeed = 50;   // fastest allowed speed

    function updateMinusBtnState() {
        const currentValue = parseInt(qtyInput.value);
        minusBtn.disabled = currentValue <= minQty;
    }

    function changeQty(amount) {
        let currentValue = parseInt(qtyInput.value);
        if (isNaN(currentValue)) currentValue = minQty;
        let newValue = currentValue + amount;
        if (newValue < minQty) newValue = minQty;
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
        const value = parseInt(qtyInput.value);
        if (isNaN(value) || value < minQty) {
        qtyInput.value = minQty;
        }
        updateMinusBtnState();
    });

    updateMinusBtnState();
}
