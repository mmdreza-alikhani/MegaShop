/* =========================================================
   مگاشاپ — اسکریپت‌های رابط کاربری
   ========================================================= */
(function () {
    'use strict';

    /* نوار پیشرفت اسکرول */
    var progressBar = document.querySelector('.progress-bar');

    if (progressBar) {
        window.addEventListener('scroll', function () {
            var scrollable = document.documentElement.scrollHeight - window.innerHeight;
            var percent = scrollable > 0 ? (window.pageYOffset / scrollable) * 100 : 0;
            progressBar.style.width = percent + '%';
        });
    }

    /* منوی موبایل و پنل سبد خرید */
    var body = document.body;
    var drawer = document.getElementById('mkDrawer');
    var cartPanel = document.getElementById('mkCartPanel');
    var overlay = document.getElementById('mkOverlay');

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

    if (overlay) {
        overlay.addEventListener('click', closePanels);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePanels();
        }
    });

    /* تب‌های مودال ورود/ثبت‌نام */
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

    /* پیش‌نمایش آواتار */
    window.loadFile = function (event) {
        var image = document.getElementById('output');

        if (image && event.target.files && event.target.files[0]) {
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    };
})();
