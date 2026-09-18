/**
 * public/assets/js/app.js
 * Small global JS helpers shared across views.
 */

// Auto-hide flash messages after a few seconds
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-dismiss').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });
});

// Generic confirm-before-submit for destructive actions
function confirmAction(message) {
    return window.confirm(message || 'Are you sure you want to do this?');
}

var checkoutForm = null;

function normalizeName(value) {
    return value.trim().replace(/\s+/g, ' ').toLowerCase();
}

function openCheckoutConfirmation(form) {
    checkoutForm = form;
    document.getElementById('checkout-visitor-name').textContent = form.dataset.visitorName || '';
    document.getElementById('checkout-name-input').value = '';
    document.getElementById('checkout-name-error').classList.add('hidden');

    var modal = document.getElementById('checkout-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('checkout-name-input').focus();
    return false;
}

function closeCheckoutConfirmation() {
    var modal = document.getElementById('checkout-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    checkoutForm = null;
}

function submitCheckoutConfirmation() {
    if (!checkoutForm) return;

    var visitorName = checkoutForm.dataset.visitorName || '';
    var input = document.getElementById('checkout-name-input');
    var error = document.getElementById('checkout-name-error');

    if (normalizeName(input.value) !== normalizeName(visitorName)) {
        error.classList.remove('hidden');
        input.focus();
        return;
    }

    checkoutForm.elements.checkout_confirmation.value = input.value;
    checkoutForm.submit();
}

// Simple client-side toggler used by mobile menu and dropdowns
function toggleEl(id) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}
