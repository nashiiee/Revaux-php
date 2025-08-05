// cart_page.js
export function setupCartPage() {
    // Exit if not on the cart page (checking for the main cart container)
    if (!document.querySelector('.cart-category-box')) return;

    let currentHoldTimeout = null;
    let currentHoldInterval = null;

    // --- Global Hold Control Functions ---
    function stopHold() {
        clearTimeout(currentHoldTimeout);
        clearInterval(currentHoldInterval);
        currentHoldTimeout = null;
        currentHoldInterval = null;
    }

    // --- Get references for global elements ---
    const selectAllCheckbox = document.getElementById('select-all');
    const removeSelectedBtn = document.getElementById('remove-selected-btn');
    const cartEmptyMessage = document.querySelector('.cart-empty-message');

    // --- Helper function to update the state of the "Remove Selected Items" button ---
    function updateRemoveSelectedBtnState() {
        const checkedCount = document.querySelectorAll('.cart-item-checkbox:checked').length;
        if (removeSelectedBtn) {
            removeSelectedBtn.style.display = checkedCount > 0 ? 'flex' : 'none';
        }
    }

    // --- Shared function for updating order summary ---
    function updateSummary() {
        let total = 0, checkedCount = 0; // Use a new variable for the checked items
        // Select only checked cart items for summary calculation
        const checkedCartItems = document.querySelectorAll('.cart-item-checkbox:checked');
        
        // Get references to summary elements ONCE at the top of the function
        const subtotalTextElement = document.querySelector('.summary-item:first-of-type span:first-of-type');
        const subtotalValueElement = document.querySelector('.summary-item:first-of-type span:nth-of-type(2)');
        const shippingFeeValueElement = document.querySelector('.summary-item:nth-of-type(2) span:nth-of-type(2)');
        const finalTotalTextElement = document.querySelector('.summary-item.total span:first-of-type');
        const finalTotalValueElement = document.querySelector('.summary-item.total span:nth-of-type(2)');
        const checkoutBtn = document.querySelector('.checkout-btn');

        // Get references for the select all label and the cart count span
        const selectAllLabel = document.getElementById('select-all-label');
        const cartItemCountSpan = document.getElementById('cart-item-count');

        // Check if there are ANY cart items at all (checked or unchecked) to display "Your cart is empty."
        const allExistingCartItems = document.querySelectorAll('.cart-item');

        if (allExistingCartItems.length === 0) { // If absolutely no items are in the DOM
            if (subtotalValueElement) subtotalValueElement.textContent = '₱0.00';
            if (subtotalTextElement) subtotalTextElement.textContent = `Order total (0 Items):`;
            if (shippingFeeValueElement) shippingFeeValueElement.textContent = '₱0.00';
            if (finalTotalValueElement) finalTotalValueElement.textContent = '₱0.00';
            if (finalTotalTextElement) finalTotalTextElement.textContent = 'Order total:';
            
            if (removeSelectedBtn) removeSelectedBtn.style.display = 'none';
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            if (checkoutBtn) checkoutBtn.disabled = true;

            if (cartEmptyMessage) {
                cartEmptyMessage.style.display = 'flex';
            }
            
            // Also update the Select All count to 0
            if (cartItemCountSpan) cartItemCountSpan.textContent = '0';
            if (selectAllLabel) selectAllLabel.textContent = 'Select All (0 items)';
            
            return;
        } else {
            // Hide the "Your cart is empty" message if there are items
            if (cartEmptyMessage) {
                cartEmptyMessage.style.display = 'none';
            }
        }

        // Loop through only the CHECKED items for calculation
        checkedCartItems.forEach(checkbox => {
            const it = checkbox.closest('.cart-item');
            if (it) {
                const q = +it.querySelector('.qty-input').value;
                const p = parseFloat(it.querySelector('.item-price').textContent.replace(/[^\d.]/g, ''));
                total += q * p;
                checkedCount += q; // Use checkedCount for the summary
            }
        });

        const shippingFee = total > 0 ? 138.00 : 0.00;

        // Update elements only if they exist
        if (subtotalValueElement) {
            subtotalValueElement.textContent = '₱' + total.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }
        if (subtotalTextElement) { // Update the label to reflect the current item count
            subtotalTextElement.textContent = `Order total (${checkedCount} Items):`;
        }
        if (shippingFeeValueElement) {
            shippingFeeValueElement.textContent = '₱' + shippingFee.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }
        if (finalTotalValueElement) {
            finalTotalValueElement.textContent = '₱' + (total + shippingFee).toLocaleString(undefined, { minimumFractionDigits: 2 });
        }
        
        // Enable/disable checkout button based on whether *any* items are checked
        if (checkoutBtn) {
            checkoutBtn.disabled = checkedCartItems.length === 0;
        }

        // Get the total number of ALL items and update the 'Select All' label
        const totalItemsInCart = document.querySelectorAll('.cart-item').length;
        if (cartItemCountSpan) {
            cartItemCountSpan.textContent = totalItemsInCart;
        }
        if (selectAllLabel) {
            selectAllLabel.textContent = `Select All (${totalItemsInCart} items)`;
        }
    }


    // --- Iterate over each cart item to set up specific functionality ---
    document.querySelectorAll('.cart-item').forEach(item => {
        const cartItemId = item.dataset.cartItemId;
        const minusBtn = item.querySelector('.qty-btn-minus');
        const plusBtn = item.querySelector('.qty-btn-plus');
        const qtyInput = item.querySelector('.qty-input');
        const totalPrice = item.querySelector('.total-price');
        const price = parseFloat(
            item.querySelector('.item-price')
                .textContent.replace(/[^\d.]/g, '')
        );
        const stockWarningMessage = item.querySelector('.stock-warning-message');
        const itemCheckbox = item.querySelector('.cart-item-checkbox');
        const deleteButton = item.querySelector('.delete-btn');

        const minQty = 1;
        let maxQty = parseInt(item.dataset.stock) || 99;

        // --- ALL ITEM-SPECIFIC FUNCTIONS MUST BE DEFINED HERE, INSIDE THE FOREACH LOOP ---

        // Function to update the disabled state of buttons and show/hide stock warnings
        function updateBtnState() {
            const q = +qtyInput.value;

            minusBtn.disabled = q <= minQty;
            plusBtn.disabled = q >= maxQty;

            if (stockWarningMessage) {
                if (maxQty === 0) {
                    stockWarningMessage.textContent = `Out of stock!`;
                    stockWarningMessage.style.display = 'inline';
                    plusBtn.disabled = true;
                    minusBtn.disabled = true;
                    qtyInput.value = 0;
                } else if (maxQty <= 10 && q >= maxQty && maxQty > 0) {
                    stockWarningMessage.textContent = `Only ${maxQty} left!`;
                    stockWarningMessage.style.display = 'inline';
                } else if (maxQty > 10 && q >= maxQty) {
                    stockWarningMessage.textContent = `Max quantity reached!`;
                    stockWarningMessage.style.display = 'inline';
                } else if (maxQty <= 20 && q >= maxQty - 5 && q < maxQty && maxQty > 0) {
                    stockWarningMessage.textContent = `Low stock: ${maxQty} left!`;
                    stockWarningMessage.style.display = 'inline';
                } else {
                    stockWarningMessage.style.display = 'none';
                }
            }
        }

        // Function to send AJAX request to update item quantity
        function ajaxUpdate(newQty) {
            return fetch('../../data/update_cart_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_item_id=${cartItemId}&quantity=${newQty}`
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.msg || 'Update failed');
                }
                qtyInput.value = data.quantity;
                totalPrice.textContent = '₱' + (price * data.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 });
                updateSummary(); // Recalculate global summary
                updateBtnState(); // Update button states and stock warning for this item
            })
            .catch(error => {
                console.error("Cart update error for item", cartItemId, ":", error);
                alert(error.message);
            });
        }

        // Function to change quantity and trigger AJAX update
        function changeQty(delta) {
            let q = +qtyInput.value;
            let next = q + delta;

            if (next < minQty) next = minQty;
            if (next > maxQty) next = maxQty;
            if (maxQty === 0) next = 0;

            if (next !== q) {
                qtyInput.value = next;
                updateBtnState();
                ajaxUpdate(next);
            }
        }

        // Function to handle continuous quantity change on hold
        function startHold(delta, e = null) {
            stopHold();

            changeQty(delta);

            const currentQty = +qtyInput.value;
            if ((delta < 0 && currentQty <= minQty) || (delta > 0 && currentQty >= maxQty) || maxQty === 0) {
                return;
            }

            let speed = 400;
            const speedStep = 30;
            const minSpeed = 80;
            const holdDelay = 500;

            currentHoldTimeout = setTimeout(function repeater() {
                const q = +qtyInput.value;
                if ((delta < 0 && q <= minQty) || (delta > 0 && q >= maxQty) || maxQty === 0) {
                    stopHold();
                    return;
                }

                changeQty(delta);

                const qAfterChange = +qtyInput.value;
                if ((delta < 0 && qAfterChange <= minQty) || (delta > 0 && qAfterChange >= maxQty) || maxQty === 0) {
                    stopHold();
                    return;
                }

                speed = Math.max(minSpeed, speed - speedStep);
                currentHoldInterval = setTimeout(repeater, speed);
            }, holdDelay);
        }

        // --- Event Listeners for the current item's buttons and checkbox ---
        minusBtn.addEventListener('mousedown', (e) => startHold(-1, e));
        plusBtn.addEventListener('mousedown', (e) => startHold(1, e));
        minusBtn.addEventListener('touchstart', e => { e.preventDefault(); startHold(-1, e); }, { passive: false });
        plusBtn.addEventListener('touchstart', e => { e.preventDefault(); startHold(1, e); }, { passive: false });

        // Blur event for manual input
        qtyInput.addEventListener('blur', () => {
            let v = parseInt(qtyInput.value) || minQty;
            if (isNaN(v)) v = minQty;
            if (v < minQty) v = minQty;
            if (v > maxQty) v = maxQty;
            if (maxQty === 0) v = 0;

            ajaxUpdate(v);
        });

        // Event listener for individual item checkboxes
        if (itemCheckbox) {
            itemCheckbox.addEventListener('change', () => {
                updateSelectAllCheckboxState();
                updateRemoveSelectedBtnState();
                updateSummary(); // <--- ADD THIS LINE HERE
            });
        }

        // --- Event Listener for individual "Remove" button ---
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const productName = item.querySelector('.item-name').textContent.trim();
                if (confirm(`Are you sure you want to remove "${productName}" from your cart?`)) {
                    fetch('../../data/delete_cart_items.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ cart_item_ids: [cartItemId] })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            item.remove();
                            updateSummary();
                            updateSelectAllCheckboxState();
                            updateRemoveSelectedBtnState();
                        } else {
                            alert(data.msg || 'Failed to remove item.');
                        }
                    })
                    .catch(error => {
                        console.error("Error removing cart item:", error);
                        alert('An error occurred while trying to remove the item.');
                    });
                }
            });
        }

        updateBtnState(); // Initial state update for this item (buttons, stock warning)
    }); // --- END of forEach loop for cart items ---


    // --- Add global document-level event listeners *ONLY ONCE* here (outside the forEach loop) ---
    document.addEventListener('mouseup', () => {
        stopHold();
    });
    document.addEventListener('mouseleave', () => {
        stopHold();
    });
    document.addEventListener('touchend', () => {
        stopHold();
    });

    // --- Checkbox Select All functionality ---
    function updateSelectAllCheckboxState() {
        if (!selectAllCheckbox) return;
        const currentItemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
        const allChecked = Array.from(currentItemCheckboxes).every(c => c.checked);
        const anyExist = currentItemCheckboxes.length > 0;
        selectAllCheckbox.checked = allChecked && anyExist;
        selectAllCheckbox.disabled = !anyExist;
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            // Re-query itemCheckboxes here to ensure we get all *current* items
            document.querySelectorAll('.cart-item-checkbox').forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateRemoveSelectedBtnState();
            updateSummary(); // <--- ADD THIS LINE HERE
        });
        updateSelectAllCheckboxState();
        updateRemoveSelectedBtnState();
    }


    // --- Remove Selected Items Functionality (Header Button) ---
    if (removeSelectedBtn) {
        removeSelectedBtn.addEventListener('click', () => {
            const selectedItemIds = [];
            document.querySelectorAll('.cart-item-checkbox:checked').forEach(cb => {
                selectedItemIds.push(cb.dataset.cartItemId);
            });

            if (selectedItemIds.length === 0) {
                alert('Please select at least one item to remove.');
                return;
            }

            if (!confirm(`Are you sure you want to remove ${selectedItemIds.length} selected item(s) from your cart?`)) {
                return;
            }

            fetch('../../data/delete_cart_items.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_item_ids: selectedItemIds })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    selectedItemIds.forEach(id => {
                        const itemToRemove = document.querySelector(`.cart-item[data-cart-item-id="${id}"]`);
                        if (itemToRemove) {
                            itemToRemove.remove();
                        }
                    });
                    updateSummary();
                    updateSelectAllCheckboxState();
                    updateRemoveSelectedBtnState();
                } else {
                    alert(data.msg || 'Failed to remove selected items.');
                }
            })
            .catch(error => {
                console.error("Error removing selected cart items:", error);
                alert('An error occurred while trying to remove items.');
            });
        });
    }

    // Initial summary update on page load
    updateSummary();
}