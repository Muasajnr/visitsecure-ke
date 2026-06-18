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

// Simple client-side toggler used by mobile menu and dropdowns
function toggleEl(id) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}
