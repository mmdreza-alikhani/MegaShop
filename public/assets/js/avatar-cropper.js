/**
 * برش‌دهنده‌ی تصویر پروفایل
 * -------------------------------------------------
 * انتخاب فایل → برش و بزرگ‌نمایی دقیق → خروجی مربع با اندازه‌ی دلخواه
 * خروجی به‌صورت WebP و همیشه زیر سقف حجم مجاز نگه داشته می‌شود.
 */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function notify(message, type) {
        if (typeof window.mkNotify === 'function') {
            window.mkNotify(message, type || 'error');
        }
    }

    function humanSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(1).replace('.0', '') + ' مگابایت';
        }

        return Math.max(1, Math.round(bytes / 1024)) + ' کیلوبایت';
    }

    ready(function () {
        var cfg = window.MK_AVATAR;

        if (!cfg) {
            return;
        }

        var input = document.querySelector(cfg.input);
        var trigger = document.querySelector(cfg.trigger);
        var preview = document.querySelector(cfg.preview);
        var status = document.querySelector(cfg.status);
        var modalEl = document.querySelector(cfg.modal);

        if (!input || !modalEl) {
            return;
        }

        var canvas = modalEl.querySelector('[data-crop-canvas]');
        var miniCanvas = modalEl.querySelector('[data-crop-preview]');
        var zoomInput = modalEl.querySelector('[data-crop-zoom]');
        var zoomLabel = modalEl.querySelector('[data-crop-zoom-label]');
        var rotateBtn = modalEl.querySelector('[data-crop-rotate]');
        var resetBtn = modalEl.querySelector('[data-crop-reset]');
        var saveBtn = modalEl.querySelector('[data-crop-save]');
        var sizeInput = modalEl.querySelector('[data-crop-size]');
        var stage = modalEl.querySelector('[data-crop-stage]');

        if (!canvas || !canvas.getContext) {
            return;
        }

        var ctx = canvas.getContext('2d');
        var miniCtx = miniCanvas ? miniCanvas.getContext('2d') : null;
        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        var maxKb = cfg.maxKb || 500;
        var objectUrl = null;

        var state = {
            img: null,
            scale: 1,
            panX: 0,
            panY: 0,
            rot: 0,
            view: 320,
            dragging: false,
            pointerId: null,
            startX: 0,
            startY: 0,
            startPanX: 0,
            startPanY: 0,
            pendingReset: false
        };

        /* ---------------- اندازه‌گیری ---------------- */

        function measure() {
            var rect = canvas.getBoundingClientRect();
            var size = Math.round(rect.width) || 320;

            state.view = size;
            canvas.width = Math.round(size * dpr);
            canvas.height = Math.round(size * dpr);

            if (miniCanvas) {
                var mrect = miniCanvas.getBoundingClientRect();
                var msize = Math.round(mrect.width) || 96;
                miniCanvas.width = Math.round(msize * dpr);
                miniCanvas.height = Math.round(msize * dpr);
            }
        }

        function rotatedBox() {
            var rot = ((state.rot % 360) + 360) % 360;
            var swap = rot === 90 || rot === 270;

            return {
                w: swap ? state.img.height : state.img.width,
                h: swap ? state.img.width : state.img.height
            };
        }

        function minScale() {
            var box = rotatedBox();

            return Math.max(state.view / box.w, state.view / box.h);
        }

        function maxScale() {
            return minScale() * 4;
        }

        function clampScale() {
            var min = minScale();

            if (!isFinite(state.scale) || state.scale < min) {
                state.scale = min;
            }

            if (state.scale > min * 4) {
                state.scale = min * 4;
            }
        }

        function clampPan() {
            var box = rotatedBox();
            var maxX = Math.max(0, (box.w * state.scale - state.view) / 2);
            var maxY = Math.max(0, (box.h * state.scale - state.view) / 2);

            state.panX = Math.min(maxX, Math.max(-maxX, state.panX));
            state.panY = Math.min(maxY, Math.max(-maxY, state.panY));
        }

        /* ---------------- رندر ---------------- */

        function paint(context, size, dprRatio, scaleFactor, offsetX, offsetY) {
            context.setTransform(dprRatio, 0, 0, dprRatio, 0, 0);
            context.clearRect(0, 0, size, size);
            context.save();
            context.translate(size / 2 + offsetX, size / 2 + offsetY);
            context.rotate(state.rot * Math.PI / 180);
            context.scale(state.scale * scaleFactor, state.scale * scaleFactor);
            context.drawImage(state.img, -state.img.width / 2, -state.img.height / 2);
            context.restore();
        }

        function draw() {
            if (!state.img) {
                return;
            }

            paint(ctx, state.view, dpr, 1, state.panX, state.panY);

            if (miniCtx && miniCanvas) {
                var msize = miniCanvas.width / dpr;
                var k = msize / state.view;

                miniCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
                miniCtx.clearRect(0, 0, msize, msize);
                miniCtx.save();
                miniCtx.beginPath();
                miniCtx.arc(msize / 2, msize / 2, msize / 2, 0, Math.PI * 2);
                miniCtx.closePath();
                miniCtx.clip();
                miniCtx.fillStyle = '#ffffff';
                miniCtx.fillRect(0, 0, msize, msize);
                miniCtx.translate(msize / 2 + state.panX * k, msize / 2 + state.panY * k);
                miniCtx.rotate(state.rot * Math.PI / 180);
                miniCtx.scale(state.scale * k, state.scale * k);
                miniCtx.drawImage(state.img, -state.img.width / 2, -state.img.height / 2);
                miniCtx.restore();
            }

            updateZoomUi();
        }

        function updateZoomUi() {
            if (!zoomInput) {
                return;
            }

            var min = minScale();
            var percent = min > 0 ? Math.round((state.scale / min) * 100) : 100;

            zoomInput.value = String(Math.max(100, Math.min(400, percent)));

            if (zoomLabel) {
                zoomLabel.textContent = percent.toLocaleString('fa-IR') + '٪';
            }
        }

        /* ---------------- تعامل ---------------- */

        function zoomBy(factor) {
            state.scale *= factor;
            clampScale();
            clampPan();
            draw();
        }

        if (zoomInput) {
            zoomInput.addEventListener('input', function () {
                var value = Number(zoomInput.value) || 100;
                state.scale = minScale() * (value / 100);
                clampScale();
                clampPan();
                draw();
            });
        }

        canvas.addEventListener('wheel', function (event) {
            if (!state.img) {
                return;
            }

            event.preventDefault();
            zoomBy(event.deltaY < 0 ? 1.08 : 1 / 1.08);
        }, { passive: false });

        canvas.addEventListener('pointerdown', function (event) {
            if (!state.img) {
                return;
            }

            state.dragging = true;
            state.pointerId = event.pointerId;
            state.startX = event.clientX;
            state.startY = event.clientY;
            state.startPanX = state.panX;
            state.startPanY = state.panY;

            canvas.classList.add('is-dragging');

            if (canvas.setPointerCapture) {
                canvas.setPointerCapture(event.pointerId);
            }
        });

        canvas.addEventListener('pointermove', function (event) {
            if (!state.dragging || event.pointerId !== state.pointerId) {
                return;
            }

            state.panX = state.startPanX + (event.clientX - state.startX);
            state.panY = state.startPanY + (event.clientY - state.startY);
            clampPan();
            draw();
        });

        function endDrag(event) {
            if (!state.dragging) {
                return;
            }

            state.dragging = false;
            canvas.classList.remove('is-dragging');

            if (canvas.releasePointerCapture && state.pointerId !== null) {
                try {
                    canvas.releasePointerCapture(state.pointerId);
                } catch (e) {
                    /* pointer قبلا آزاد شده است */
                }
            }

            state.pointerId = null;
        }

        canvas.addEventListener('pointerup', endDrag);
        canvas.addEventListener('pointercancel', endDrag);
        canvas.addEventListener('pointerleave', function (event) {
            if (state.dragging && event.buttons === 0) {
                endDrag(event);
            }
        });

        if (rotateBtn) {
            rotateBtn.addEventListener('click', function () {
                if (!state.img) {
                    return;
                }

                state.rot = (state.rot + 90) % 360;
                state.scale = Math.max(state.scale, minScale());
                clampScale();
                clampPan();
                draw();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (!state.img) {
                    return;
                }

                state.rot = 0;
                state.panX = 0;
                state.panY = 0;
                state.scale = minScale();
                draw();
            });
        }

        /* ---------------- خروجی ---------------- */

        function renderTo(size, quality, type) {
            return new Promise(function (resolve) {
                var target = document.createElement('canvas');
                target.width = size;
                target.height = size;

                var tctx = target.getContext('2d');
                var k = size / state.view;

                tctx.fillStyle = '#ffffff';
                tctx.fillRect(0, 0, size, size);
                tctx.translate(size / 2 + state.panX * k, size / 2 + state.panY * k);
                tctx.rotate(state.rot * Math.PI / 180);
                tctx.scale(state.scale * k, state.scale * k);
                tctx.drawImage(state.img, -state.img.width / 2, -state.img.height / 2);

                target.toBlob(function (blob) {
                    resolve(blob);
                }, type, quality);
            });
        }

        function buildBlob(size) {
            var qualities = [0.9, 0.82, 0.74, 0.66, 0.58, 0.5];
            var types = ['image/webp', 'image/jpeg'];
            var limit = maxKb * 1024;
            var attempt = 0;
            var best = null;

            function next() {
                if (attempt >= qualities.length * types.length) {
                    return Promise.resolve(best);
                }

                var quality = qualities[attempt % qualities.length];
                var type = types[Math.floor(attempt / qualities.length)] || 'image/webp';
                attempt += 1;

                return renderTo(size, quality, type).then(function (blob) {
                    if (!blob) {
                        return next();
                    }

                    if (!best || blob.size < best.size) {
                        best = blob;
                    }

                    if (blob.size <= limit) {
                        return blob;
                    }

                    return next();
                });
            }

            return next();
        }

        function setBusy(busy) {
            if (saveBtn) {
                saveBtn.disabled = busy;
                saveBtn.textContent = busy ? 'در حال ذخیره…' : 'ذخیره تصویر';
            }

            if (stage) {
                stage.classList.toggle('is-busy', busy);
            }
        }

        function save() {
            if (!state.img) {
                return;
            }

            var size = sizeInput ? Number(sizeInput.value) || 512 : 512;

            setBusy(true);

            buildBlob(size).then(function (blob) {
                if (!blob) {
                    throw new Error('ساخت تصویر ممکن نشد.');
                }

                if (blob.size > maxKb * 1024) {
                    throw new Error('حجم تصویر بیش از ' + maxKb + ' کیلوبایت شد؛ کادر برش را بزرگ‌تر کنید.');
                }

                var extension = blob.type === 'image/jpeg' ? 'jpg' : 'webp';
                var form = new FormData();
                form.append('avatar', new File([blob], 'avatar.' + extension, { type: blob.type }));

                return fetch(cfg.endpoint, {
                    method: 'POST',
                    body: form,
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': cfg.csrf
                    }
                }).then(function (response) {
                    return response.json().catch(function () {
                        return {};
                    }).then(function (payload) {
                        if (!response.ok) {
                            var firstError = payload && payload.errors
                                ? Object.values(payload.errors)[0][0]
                                : (payload && payload.message) || 'ذخیره تصویر انجام نشد.';

                            throw new Error(firstError);
                        }

                        return payload;
                    });
                });
            }).then(function (payload) {
                var url = (payload && payload.url ? payload.url : '') + '?t=' + Date.now();

                if (preview && payload && payload.url) {
                    preview.src = url;
                }

                if (status) {
                    status.hidden = false;
                    status.textContent = 'تصویر پروفایل ذخیره شد.';
                }

                notify((payload && payload.message) || 'تصویر پروفایل به‌روزرسانی شد.', 'success');

                if (window.jQuery) {
                    window.jQuery(modalEl).modal('hide');
                }
            }).catch(function (error) {
                notify(error.message || 'ذخیره تصویر انجام نشد.', 'error');
            }).then(function () {
                setBusy(false);
            });
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', save);
        }

        /* ---------------- انتخاب فایل ---------------- */

        function loadFile(file) {
            if (!file) {
                return;
            }

            var allowed = ['image/jpeg', 'image/png', 'image/webp'];

            if (allowed.indexOf(file.type) === -1) {
                notify('فقط تصویر jpg، png یا webp قابل انتخاب است.', 'error');

                return;
            }

            if (file.size > 12 * 1048576) {
                notify('حجم فایل اصلی بیش از ۱۲ مگابایت است؛ تصویر کوچک‌تری انتخاب کنید.', 'error');

                return;
            }

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
            }

            objectUrl = URL.createObjectURL(file);

            var image = new Image();

            image.onload = function () {
                state.img = image;
                state.rot = 0;
                state.panX = 0;
                state.panY = 0;
                state.scale = 1;

                measure();
                state.scale = minScale();
                state.pendingReset = true;
                draw();

                if (window.jQuery) {
                    window.jQuery(modalEl).modal('show');
                }

                if (status) {
                    status.hidden = false;
                    status.textContent = 'تصویر انتخاب‌شده: ' + file.name + ' (' + humanSize(file.size) + ')';
                }
            };

            image.onerror = function () {
                notify('خواندن تصویر ممکن نشد؛ فایل دیگری انتخاب کنید.', 'error');
            };

            image.src = objectUrl;
        }

        if (trigger && input) {
            trigger.addEventListener('click', function () {
                input.click();
            });
        }

        if (preview && input) {
            preview.style.cursor = 'pointer';
            preview.addEventListener('click', function () {
                input.click();
            });
        }

        input.addEventListener('change', function () {
            var file = input.files && input.files[0];

            input.value = '';
            loadFile(file);
        });

        window.addEventListener('resize', function () {
            if (!state.img || !modalEl.classList.contains('show')) {
                return;
            }

            measure();
            clampScale();
            clampPan();
            draw();
        });

        if (window.jQuery) {
            window.jQuery(modalEl).on('shown.bs.modal', function () {
                measure();

                if (state.pendingReset) {
                    state.panX = 0;
                    state.panY = 0;
                    state.scale = minScale();
                    state.pendingReset = false;
                }

                clampScale();
                clampPan();
                draw();
            });
        }
    });
})();
