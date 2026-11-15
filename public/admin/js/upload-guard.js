/**
 * محافظ سراسری حجم فایل‌های آپلودی
 * -------------------------------------------------
 * هر input[type=file] در کل سیستم (فروشگاه و پنل) پیش از ارسال بررسی می‌شود
 * و فایل‌های بزرگ‌تر از سقف تعیین‌شده با پیام فارسی رد می‌شوند.
 */
(function () {
    'use strict';

    var LIMIT_KB = Number(window.MK_UPLOAD_LIMIT_KB) || 500;
    var LIMIT_BYTES = LIMIT_KB * 1024;

    function humanSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(1).replace('.0', '') + ' مگابایت';
        }

        return Math.max(1, Math.round(bytes / 1024)) + ' کیلوبایت';
    }

    function ensureContainer() {
        var container = document.querySelector('.mk-toast-float');

        if (!container) {
            container = document.createElement('div');
            container.className = 'mk-toast-float';
            container.setAttribute('role', 'status');
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }

        return container;
    }

    function dismiss(item) {
        if (!item || item.classList.contains('is-hiding')) {
            return;
        }

        item.classList.add('is-hiding');
        window.setTimeout(function () {
            if (item.parentNode) {
                item.parentNode.removeChild(item);
            }
        }, 220);
    }

    window.mkNotify = function (message, type) {
        var container = ensureContainer();
        var variant = type === 'success' ? 'success' : (type === 'info' ? 'info' : 'error');
        var icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };

        var item = document.createElement('div');
        item.className = 'mk-toast-float-item is-' + variant;
        item.innerHTML =
            '<span class="mk-toast-float-icon fa ' + (icons[variant] || icons.info) + '"></span>' +
            '<p class="mk-toast-float-text"></p>' +
            '<button type="button" class="mk-toast-float-close" aria-label="بستن"><span class="fa fa-times"></span></button>';

        item.querySelector('.mk-toast-float-text').textContent = message;
        item.querySelector('.mk-toast-float-close').addEventListener('click', function () {
            dismiss(item);
        });

        container.appendChild(item);

        window.setTimeout(function () {
            dismiss(item);
        }, variant === 'error' ? 7000 : 4500);
    };

    function showInlineHint(input, message) {
        var holder = input.closest('.mk-field, .form-group, .bmd-form-group, .mk-field-control');

        if (!holder) {
            return;
        }

        var hint = holder.querySelector('.mk-upload-error');

        if (!hint) {
            hint = document.createElement('small');
            hint.className = 'mk-upload-error';
            holder.appendChild(hint);
        }

        hint.textContent = message;
        hint.hidden = false;
    }

    function clearInlineHint(input) {
        var holder = input.closest('.mk-field, .form-group, .bmd-form-group, .mk-field-control');

        if (!holder) {
            return;
        }

        var hint = holder.querySelector('.mk-upload-error');

        if (hint) {
            hint.hidden = true;
        }
    }

    document.addEventListener('change', function (event) {
        var input = event.target;

        if (!input || input.type !== 'file' || input.hasAttribute('data-skip-upload-limit')) {
            return;
        }

        var files = input.files;

        if (!files || !files.length) {
            clearInlineHint(input);
            return;
        }

        var oversized = [];

        for (var i = 0; i < files.length; i += 1) {
            if (files[i].size > LIMIT_BYTES) {
                oversized.push(files[i]);
            }
        }

        if (!oversized.length) {
            clearInlineHint(input);
            return;
        }

        input.value = '';

        var names = oversized.map(function (file) {
            return file.name + ' (' + humanSize(file.size) + ')';
        }).join('، ');

        var message = 'حجم فایل نباید بیشتر از ' + LIMIT_KB + ' کیلوبایت باشد: ' + names;

        showInlineHint(input, 'حداکثر حجم مجاز ' + LIMIT_KB + ' کیلوبایت است.');
        window.mkNotify(message, 'error');
    }, true);
})();
