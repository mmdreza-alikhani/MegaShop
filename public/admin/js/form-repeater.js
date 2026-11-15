/* =========================================================
   دکمه‌های ردیف‌های تکرارشونده (پلاگین czMore)
   czMore فقط یک div خالی با آیکن پس‌زمینه می‌سازد؛ این فایل آن را
   به یک دکمه خوانا و قابل‌دسترس با متن و آیکن تبدیل می‌کند.
   ========================================================= */
(function (window, document) {
    'use strict';

    var LABELS = {
        add: 'افزودن ردیف',
        remove: 'حذف این ردیف'
    };

    function decorate(element, type) {
        if (!element || element.getAttribute('data-mk-repeater') === type) {
            return;
        }

        var label = type === 'add' ? LABELS.add : LABELS.remove;

        element.setAttribute('data-mk-repeater', type);
        element.setAttribute('role', 'button');
        element.setAttribute('tabindex', '0');
        element.setAttribute('title', label);
        element.setAttribute('aria-label', label);
        element.classList.add('mk-repeater-btn');
        element.classList.add(type === 'add' ? 'mk-repeater-add' : 'mk-repeater-remove');
        element.innerHTML = '<i class="fas ' + (type === 'add' ? 'fa-plus' : 'fa-trash') + '" aria-hidden="true"></i>'
            + '<span>' + label + '</span>';

        element.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                element.click();
            }
        });
    }

    /**
     * همه دکمه‌های افزودن/حذف داخل scope را تزئین می‌کند.
     *
     * @param {HTMLElement|Document} scope
     */
    function decorateScope(scope) {
        var root = scope || document;

        root.querySelectorAll('.btnPlus').forEach(function (button) {
            decorate(button, 'add');
        });

        root.querySelectorAll('.btnMinus').forEach(function (button) {
            decorate(button, 'remove');
        });
    }

    window.MkRepeater = {
        decorate: decorateScope
    };

    document.addEventListener('DOMContentLoaded', function () {
        decorateScope(document);
    });
})(window, document);
