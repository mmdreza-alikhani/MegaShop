/* =========================================================
   مگاشاپ — اسکریپت‌های رابط کاربری
   ========================================================= */
(function () {
    'use strict';

    /* ---------- تم روشن / تاریک ---------- */
    var root = document.documentElement;
    var themeButtons = document.querySelectorAll('[data-theme-toggle]');

    function applyTheme(theme, persist) {
        root.setAttribute('data-theme', theme);

        themeButtons.forEach(function (button) {
            button.setAttribute('aria-pressed', String(theme === 'light'));
            button.title = theme === 'light' ? 'حالت تاریک' : 'حالت روشن';
        });

        if (persist) {
            try {
                localStorage.setItem('mk-theme', theme);
            } catch (error) {
                /* حالت خصوصی مرورگر */
            }
        }
    }

    applyTheme(root.getAttribute('data-theme') === 'light' ? 'light' : 'dark', false);

    themeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            applyTheme(root.getAttribute('data-theme') === 'light' ? 'dark' : 'light', true);
        });
    });

    if (window.matchMedia) {
        var media = window.matchMedia('(prefers-color-scheme: light)');

        media.addEventListener('change', function (event) {
            var saved = null;

            try {
                saved = localStorage.getItem('mk-theme');
            } catch (error) {
                saved = null;
            }

            if (!saved) {
                applyTheme(event.matches ? 'light' : 'dark', false);
            }
        });
    }

    /* ---------- مگا منوی دسته‌بندی‌ها ---------- */
    document.querySelectorAll('[data-mega-root]').forEach(function (megaRoot) {
        var parents = megaRoot.querySelectorAll('[data-mega-parent]');
        var panels = megaRoot.querySelectorAll('[data-mega-panel]');
        var trigger = megaRoot.querySelector('.mk-nav-trigger');
        var closeTimer = null;

        function setExpanded(state) {
            if (trigger) {
                trigger.setAttribute('aria-expanded', state ? 'true' : 'false');
            }
        }

        function openMenu() {
            window.clearTimeout(closeTimer);
            megaRoot.classList.add('is-open');
            setExpanded(true);
        }

        function scheduleClose() {
            window.clearTimeout(closeTimer);
            closeTimer = window.setTimeout(function () {
                megaRoot.classList.remove('is-open');
                setExpanded(false);
            }, 280);
        }

        // باز ماندن هنگام جابه‌جایی بین دکمه و پنل
        megaRoot.addEventListener('mouseenter', openMenu);
        megaRoot.addEventListener('mouseleave', scheduleClose);
        megaRoot.addEventListener('focusin', openMenu);
        megaRoot.addEventListener('focusout', function (event) {
            if (!megaRoot.contains(event.relatedTarget)) {
                scheduleClose();
            }
        });

        document.addEventListener('click', function (event) {
            if (!megaRoot.contains(event.target)) {
                window.clearTimeout(closeTimer);
                megaRoot.classList.remove('is-open');
                setExpanded(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                window.clearTimeout(closeTimer);
                megaRoot.classList.remove('is-open');
                setExpanded(false);
            }
        });

        if (!parents.length || !panels.length) {
            return;
        }

        function activate(key) {
            parents.forEach(function (parent) {
                var isActive = parent.getAttribute('data-mega-parent') === key;
                parent.classList.toggle('is-active', isActive);
                parent.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-mega-panel') === key);
            });
        }

        parents.forEach(function (parent) {
            var key = parent.getAttribute('data-mega-parent');

            parent.addEventListener('mouseenter', function () {
                activate(key);
            });

            parent.addEventListener('focus', function () {
                activate(key);
            });

            parent.addEventListener('click', function () {
                activate(key);
            });
        });

        activate(parents[0].getAttribute('data-mega-parent'));
    });
    /* ---------- نوار پیشرفت اسکرول ---------- */
    var progressBar = document.querySelector('.progress-bar');

    if (progressBar) {
        window.addEventListener('scroll', function () {
            var scrollable = document.documentElement.scrollHeight - window.innerHeight;
            var percent = scrollable > 0 ? (window.pageYOffset / scrollable) * 100 : 0;
            progressBar.style.width = percent + '%';
        });
    }

    /* ---------- منوی موبایل و پنل سبد خرید ---------- */
    var body = document.body;

    function closePanels() {
        body.classList.remove('mk-drawer-open', 'mk-cart-open');
    }

    var burger = document.querySelector('.mk-burger');
    if (burger) {
        burger.addEventListener('click', function () {
            body.classList.toggle('mk-drawer-open');
            body.classList.remove('mk-cart-open');
        });
    }

    var cartToggle = document.querySelector('.mk-cart-toggle');
    if (cartToggle) {
        cartToggle.addEventListener('click', function () {
            body.classList.toggle('mk-cart-open');
            body.classList.remove('mk-drawer-open');
        });
    }

    document.querySelectorAll('.mk-drawer-close, .mk-cart-close').forEach(function (button) {
        button.addEventListener('click', closePanels);
    });

    var overlay = document.getElementById('mkOverlay');
    if (overlay) {
        overlay.addEventListener('click', closePanels);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePanels();
        }
    });

    /* ---------- تب‌های مودال ورود/ثبت‌نام ---------- */
    if (window.jQuery) {
        var $ = window.jQuery;
        var loginModal = $('.login-modal');
        var registerModal = $('.register-modal');
        var forgetModal = $('.forget-password-modal');

        $('.forget_password').on('click', function () {
            registerModal.hide();
            loginModal.hide();
            forgetModal.show();
        });

        $('.back_to_login').on('click', function () {
            forgetModal.hide();
            registerModal.hide();
            loginModal.show();
        });

        $('.register').on('click', function () {
            forgetModal.hide();
            loginModal.hide();
            registerModal.show();
        });
    }

    /* ---------- پیش‌نمایش آواتار ---------- */
    window.loadFile = function (event) {
        var image = document.getElementById('output');

        if (image && event.target.files && event.target.files[0]) {
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    };
})();
