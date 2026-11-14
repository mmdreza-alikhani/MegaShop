/* =========================================================
   پوسته پنل مدیریت: تم روشن/تاریک، فیلتر منو و کشوی موبایل
   ========================================================= */
(function () {
    'use strict';

    var body = document.body;

    /* ---------- تم ---------- */
    var themeButtons = document.querySelectorAll('[data-adm-theme-toggle]');

    var syncThemeButtons = function () {
        var isDark = body.classList.contains('dark');

        themeButtons.forEach(function (button) {
            button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        });
    };

    var setTheme = function (mode) {
        body.classList.toggle('dark', mode === 'dark');

        try {
            localStorage.setItem('adm-theme', mode);
            localStorage.setItem('theme', mode);
        } catch (error) {
            /* localStorage در دسترس نیست */
        }

        syncThemeButtons();
    };

    themeButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            setTheme(body.classList.contains('dark') ? 'light' : 'dark');
        });
    });

    syncThemeButtons();

    /* ---------- فیلتر منو ---------- */
    var filter = document.querySelector('[data-adm-side-filter]');

    if (filter) {
        filter.addEventListener('input', function () {
            var term = filter.value.trim().toLowerCase();
            var drawer = document.getElementById('dw-s1');

            if (!drawer) {
                return;
            }

            drawer.querySelectorAll('.side-item, .ul-text, .side-comment').forEach(function (item) {
                if (!term) {
                    item.style.display = '';
                    return;
                }

                var text = (item.textContent || '').toLowerCase();
                item.style.display = text.indexOf(term) === -1 ? 'none' : '';
            });
        });
    }

    /* ---------- کشوی موبایل ---------- */
    var syncDrawer = function () {
        var container = document.querySelector('.bmd-layout-container');

        if (!container) {
            return;
        }

        if (window.innerWidth < 992) {
            if (container.classList.contains('bmd-drawer-in')) {
                container.classList.remove('bmd-drawer-in');
                container.classList.add('bmd-drawer-out');
            }
        } else if (!container.classList.contains('bmd-drawer-in')) {
            container.classList.remove('bmd-drawer-out');
            container.classList.add('bmd-drawer-in');
        }
    };

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-toggle="drawer"]');

        if (toggle) {
            event.preventDefault();
            event.stopPropagation();
            body.classList.toggle('mk-admin-drawer-open');
            return;
        }

        if (body.classList.contains('mk-admin-drawer-open') && !event.target.closest('#dw-s1')) {
            body.classList.remove('mk-admin-drawer-open');
        }
    });

    window.addEventListener('resize', function () {
        syncDrawer();

        if (window.innerWidth > 991) {
            body.classList.remove('mk-admin-drawer-open');
        }
    });

    window.addEventListener('load', function () {
        window.setTimeout(syncDrawer, 60);
    });
})();
/* ---------- منوهای کشویی هدر (بدون وابستگی به بوت‌استرپ) ---------- */
(function () {
    var drops = Array.prototype.slice.call(document.querySelectorAll('[data-adm-drop]'));

    if (!drops.length) {
        return;
    }

    var closeDrop = function (drop) {
        drop.classList.remove('is-open');

        var trigger = drop.querySelector('[data-adm-drop-trigger]');

        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
        }
    };

    var closeAll = function (except) {
        drops.forEach(function (drop) {
            if (drop !== except) {
                closeDrop(drop);
            }
        });
    };

    drops.forEach(function (drop) {
        var trigger = drop.querySelector('[data-adm-drop-trigger]');

        if (!trigger) {
            return;
        }

        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            var willOpen = !drop.classList.contains('is-open');

            closeAll(drop);
            drop.classList.toggle('is-open', willOpen);
            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-adm-drop]')) {
            closeAll(null);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAll(null);
        }
    });

    window.addEventListener('resize', function () {
        closeAll(null);
    });
})();