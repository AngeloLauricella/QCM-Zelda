// Simple menu bindings (vanilla JS)
// - Profile dropdown toggle
// - Mobile menu toggle + overlay

(function () {
    // Profile menu
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        const trigger = userMenu.querySelector('.user-menu-trigger');
        const dropdown = userMenu.querySelector('.user-menu-dropdown');

        const show = () => {
            if (!dropdown) return;
            dropdown.removeAttribute('hidden');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        };
        const hide = () => {
            if (!dropdown) return;
            dropdown.setAttribute('hidden', '');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        };
        const toggle = (e) => {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            if (!dropdown) return;
            if (dropdown.hasAttribute('hidden')) show(); else hide();
        };

        if (trigger) {
            trigger.addEventListener('click', toggle);
        }

        // Close on outside click
        document.addEventListener('click', function (ev) {
            if (!userMenu.contains(ev.target)) {
                hide();
            }
        });

        // Close on escape
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') hide();
        });
    }

    // Mobile menu
    const mobileRoot = document.querySelector('[data-controller="mobile-menu"]') || document.querySelector('.header');
    const toggleBtn = document.querySelector('.header-toggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    const openMobile = () => {
        if (mobileMenu) mobileMenu.classList.add('active');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
        if (mobileMenu) mobileMenu.setAttribute('aria-hidden', 'false');
        document.body.classList.add('mobile-menu-open');
    };

    const closeMobile = () => {
        if (mobileMenu) mobileMenu.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
        if (mobileMenu) mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('mobile-menu-open');
        if (toggleBtn) toggleBtn.focus();
    };

    const toggleMobile = (e) => {
        if (e) e.preventDefault();
        if (!mobileMenu) return;
        if (mobileMenu.classList.contains('active')) closeMobile(); else openMobile();
    };

    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleMobile);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function (e) {
            e.preventDefault();
            closeMobile();
        });
    }

    // Close on link click inside mobile
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function (ev) {
            const a = ev.target.closest('a');
            if (a) {
                closeMobile();
            }
        });
    }

    // Close on ESC
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') closeMobile();
    });

    // Responsive: close if viewport > 768px
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeMobile();
        }
    });
})();
