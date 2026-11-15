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
/* ---------- بستن منوها هنگام باز شدن مودال ---------- */
(function () {
    var closeEveryDrop = function () {
        document.querySelectorAll('[data-adm-drop].is-open').forEach(function (drop) {
            drop.classList.remove('is-open');

            var trigger = drop.querySelector('[data-adm-drop-trigger]');

            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    };

    document.addEventListener('click', function (event) {
        if (event.target.closest('[data-toggle="modal"]')) {
            closeEveryDrop();
        }
    }, true);

    if (window.jQuery) {
        window.jQuery(document).on('show.bs.modal', closeEveryDrop);
    }
})();
/* ---------- جهت باز شدن منو: اگر پایین جا نبود، بالا باز شود ---------- */
(function () {
    var positionDrop = function (drop) {
        var menu = drop.querySelector('[data-adm-drop-menu]');

        if (!menu) {
            return;
        }

        drop.classList.remove('is-up');

        if (window.innerWidth < 992) {
            return;
        }

        var trigger = drop.getBoundingClientRect();
        var menuHeight = menu.offsetHeight;
        var spaceBelow = window.innerHeight - trigger.bottom;
        var spaceAbove = trigger.top;

        if (spaceBelow < menuHeight + 16 && spaceAbove > spaceBelow) {
            drop.classList.add('is-up');
        }
    };

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-adm-drop-trigger]');

        if (!trigger) {
            return;
        }

        var drop = trigger.closest('[data-adm-drop]');

        if (drop) {
            window.setTimeout(function () {
                positionDrop(drop);
            }, 0);
        }
    }, true);

    window.addEventListener('resize', function () {
        document.querySelectorAll('[data-adm-drop].is-open').forEach(positionDrop);
    });
})();
/* ---------- آپلودر و select جستجوپذیر ---------- */
(function () {
    var initPickers = function () {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
            window.jQuery('.selectpicker').selectpicker();
        }
    };

    var initUploads = function () {
        document.querySelectorAll('[data-mk-upload]').forEach(function (box) {
            var input = box.querySelector('input[type="file"]');
            var preview = box.querySelector('[data-mk-upload-preview]');
            var title = box.querySelector('[data-mk-upload-title]');
            var clear = box.querySelector('[data-mk-upload-clear]');
            var placeholder = preview ? preview.getAttribute('src') : '';

            if (!input) {
                return;
            }

            var showFile = function (file) {
                if (!file || !file.type || file.type.indexOf('image/') !== 0) {
                    return;
                }

                var reader = new FileReader();

                reader.onload = function (event) {
                    if (preview) {
                        preview.src = event.target.result;
                    }

                    if (title) {
                        title.textContent = file.name;
                    }
                };

                reader.readAsDataURL(file);
            };

            input.addEventListener('change', function () {
                if (input.files && input.files[0]) {
                    showFile(input.files[0]);
                }
            });

            ['dragenter', 'dragover'].forEach(function (type) {
                box.addEventListener(type, function (event) {
                    event.preventDefault();
                    box.classList.add('is-dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function (type) {
                box.addEventListener(type, function (event) {
                    event.preventDefault();
                    box.classList.remove('is-dragover');
                });
            });

            box.addEventListener('drop', function (event) {
                var files = event.dataTransfer ? event.dataTransfer.files : null;

                if (files && files[0]) {
                    input.files = files;
                    showFile(files[0]);
                }
            });

            if (clear) {
                clear.addEventListener('click', function () {
                    input.value = '';

                    if (preview) {
                        preview.src = placeholder;
                    }

                    if (title) {
                        title.textContent = 'انتخاب یا رها کردن تصویر';
                    }
                });
            }
        });
    };

    var boot = function () {
        initPickers();
        initUploads();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        window.setTimeout(boot, 0);
    }
})();
/* =========================================================
   باز کردن قطعی منوی انتخابگرها (bootstrap-select)
   دو نسخه dropdown.js در صفحه بارگذاری شده و هر کلیک را
   دو بار toggle می‌کند؛ نتیجه این بود که منو باز نمی‌شد.
   ========================================================= */
(function () {
    if (!window.jQuery) {
        return;
    }

    var $ = window.jQuery;

    // متن‌های فارسی انتخابگرها
    if ($.fn.selectpicker && $.fn.selectpicker.Constructor && $.fn.selectpicker.Constructor.DEFAULTS) {
        var pickerDefaults = $.fn.selectpicker.Constructor.DEFAULTS;

        pickerDefaults.selectAllText = 'انتخاب همه';
        pickerDefaults.deselectAllText = 'لغو انتخاب';
        pickerDefaults.noneSelectedText = 'انتخابی نشده';
        pickerDefaults.noneResultsText = 'نتیجه‌ای پیدا نشد';
        pickerDefaults.liveSearchPlaceholder = 'جستجو…';
    }

    // دو نسخه از dropdown.js در صفحه بارگذاری شده و هر کلیک را دو بار toggle می‌کند.
    // هندلرهای تکراری را کنار می‌گذاریم و یک هندلر واحد می‌گذاریم.
    $(document).off('click.bs.dropdown.data-api');

    $(document).on('click.bs.dropdown.data-api', '[data-toggle="dropdown"]', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var button = $(this);

        // بقیه منوهای باز بسته شوند.
        $('.bootstrap-select.show > .dropdown-toggle').not(button).each(function () {
            $(this).dropdown('toggle');
        });

        button.dropdown('toggle');
    });

    // در انتخابگر تک‌گزینه‌ای، بعد از انتخاب یک مورد منو بسته شود.
    $(document).on('click.bs.picker-close', '.bootstrap-select .dropdown-menu .dropdown-item', function () {
        var wrap = $(this).closest('.bootstrap-select');

        if (wrap.find('select').prop('multiple')) {
            return;
        }

        window.setTimeout(function () {
            var toggle = wrap.children('.dropdown-toggle');

            if (wrap.hasClass('show')) {
                toggle.dropdown('toggle');
            }
        }, 0);
    });

    // اگر پایین صفحه جا نبود، منو به سمت بالا باز شود.
    $(document).on('shown.bs.dropdown', '.bootstrap-select', function () {
        var wrap = this;
        var menu = wrap.querySelector('.dropdown-menu');

        if (!menu) {
            return;
        }

        wrap.classList.remove('dropup');

        var rect = menu.getBoundingClientRect();
        var spaceBelow = window.innerHeight - rect.top;

        if (rect.height > spaceBelow && rect.top > rect.height) {
            wrap.classList.add('dropup');
        }
    });

    // بستن منو با کلیک بیرون از انتخابگر
    $(document).on('click.bs.picker-close', function (event) {
        if ($(event.target).closest('.bootstrap-select').length) {
            return;
        }

        $('.bootstrap-select.show > .dropdown-toggle').each(function () {
            $(this).dropdown('toggle');
        });
    });
})();

/* ---------- یکسان‌سازی تنظیمات توست‌ها ---------- */
(function () {
    if (!window.toastr) {
        return;
    }

    window.toastr.options = Object.assign({}, window.toastr.options, {
        positionClass: 'toast-top-center',
        closeButton: true,
        progressBar: true,
        newestOnTop: true,
        preventDuplicates: true,
        timeOut: 5000,
        extendedTimeOut: 2000,
        showDuration: 200,
        hideDuration: 200,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    });
})();
