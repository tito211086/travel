(function () {
    'use strict';

    // Mobile navigation
    var toggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-nav]');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!open));
            nav.classList.toggle('is-open', !open);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                toggle.setAttribute('aria-expanded', 'false');
                nav.classList.remove('is-open');
                toggle.focus();
            }
        });
    }

    // Header border on scroll
    var header = document.querySelector('[data-header]');
    if (header) {
        var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // Lightweight client-side validation (server validation is authoritative)
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var firstInvalid = null;
            form.querySelectorAll('input, select, textarea').forEach(function (el) {
                var label = el.closest('.field');
                if (!label || el.type === 'hidden') { return; }
                var old = label.querySelector('.field-error[data-js]');
                if (old) { old.remove(); }
                label.classList.remove('has-error');
                if (!el.checkValidity()) {
                    label.classList.add('has-error');
                    var msg = document.createElement('span');
                    msg.className = 'field-error';
                    msg.setAttribute('data-js', '');
                    msg.textContent = el.validationMessage;
                    label.appendChild(msg);
                    if (!firstInvalid) { firstInvalid = el; }
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
            }
        });
    });
})();
