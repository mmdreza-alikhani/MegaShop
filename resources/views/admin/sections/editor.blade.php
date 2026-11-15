{{--
    ادیتور متن غنی پنل مدیریت
    --------------------------------------------------------------
    TinyMCE 8 (نسخه آزاد/GPL) به‌صورت محلی و بدون CDN بارگذاری می‌شود:
        public/admin/vendor/tinymce
    استایل محتوای ادیتور:
        public/admin/css/tinymce-content.css

    روش استفاده در هر صفحه:
        @section('scripts')
            @include('admin.sections.editor')
            <script>
                AdminEditor.create('#description');
            </script>
        @endsection
--}}
<script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    window.AdminEditor = (function () {
        'use strict';

        var uploadUrl = @json(route('admin.ckeditor.upload'));
        var contentCss = @json(asset('admin/css/tinymce-content.css'));

        function isDark() {
            return document.body.classList.contains('dark');
        }

        function csrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) {
                return meta.getAttribute('content');
            }

            var input = document.querySelector('input[name="_token"]');

            return input ? input.value : '';
        }

        function uploadImage(blobInfo) {
            return new Promise(function (resolve, reject) {
                var data = new FormData();
                data.append('upload', blobInfo.blob(), blobInfo.filename());

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: data
                })
                    .then(function (response) {
                        return response.json()
                            .catch(function () {
                                return {};
                            })
                            .then(function (json) {
                                return { ok: response.ok, json: json };
                            });
                    })
                    .then(function (result) {
                        if (result.ok && result.json && result.json.url) {
                            resolve(result.json.url);

                            return;
                        }

                        var message = result.json && result.json.error && result.json.error.message
                            ? result.json.error.message
                            : 'آپلود تصویر انجام نشد.';

                        reject(message);
                    })
                    .catch(function () {
                        reject('ارتباط با سرور برقرار نشد.');
                    });
            });
        }

        function baseConfig() {
            var dark = isDark();

            return {
                license_key: 'gpl',
                language: 'fa',
                directionality: 'rtl',
                height: 400,
                min_height: 240,
                resize: true,
                menubar: 'file edit view insert format tools table help',
                branding: false,
                promotion: false,
                skin: dark ? 'oxide-dark' : 'oxide',
                content_css: contentCss,
                body_class: dark ? 'mk-editor-dark' : '',
                plugins: [
                    'accordion', 'advlist', 'anchor', 'autolink', 'autosave', 'charmap', 'code',
                    'codesample', 'directionality', 'emoticons', 'fullscreen', 'help', 'image',
                    'importcss', 'insertdatetime', 'link', 'lists', 'media', 'nonbreaking',
                    'pagebreak', 'preview', 'quickbars', 'searchreplace', 'table',
                    'visualblocks', 'visualchars', 'wordcount'
                ].join(' '),
                toolbar: [
                    'undo redo | blocks | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor',
                    'alignleft aligncenter alignright alignjustify | rtl ltr | bullist numlist outdent indent | link image media table',
                    'charmap emoticons codesample | searchreplace visualblocks code fullscreen preview | removeformat help'
                ],
                toolbar_mode: 'sliding',
                quickbars_selection_toolbar: 'bold italic underline | link blockquote h2 h3',
                quickbars_insert_toolbar: 'quickimage quicktable',
                contextmenu: 'link image table',
                block_formats: 'پاراگراف=p; عنوان ۱=h1; عنوان ۲=h2; عنوان ۳=h3; عنوان ۴=h4; عنوان ۵=h5; عنوان ۶=h6; نقل‌قول=blockquote; کد=code',
                font_family_formats: 'ایران‌یکان=yekan,Tahoma,sans-serif; وزیرمتن=Vazirmatn,Tahoma,sans-serif; تاهوما=Tahoma,sans-serif; ایران‌سنس=IRANSans,Tahoma,sans-serif; Arial=Arial,Helvetica,sans-serif; Times New Roman=times new roman,times,serif; Courier New=courier new,courier,monospace',
                font_size_formats: '11px 12px 13px 14px 15px 16px 18px 20px 24px 28px 32px 36px 48px',
                image_advtab: true,
                image_caption: true,
                image_title: true,
                image_uploadtab: true,
                automatic_uploads: true,
                images_upload_handler: uploadImage,
                paste_data_images: true,
                convert_urls: false,
                entity_encoding: 'raw',
                link_default_target: '_blank',
                link_assume_external_targets: 'https',
                object_resizing: true,
                browser_spellcheck: false,
                placeholder: 'متن را اینجا بنویسید…',
                setup: function (editor) {
                    editor.on('change keyup undo redo', function () {
                        editor.save();
                    });
                }
            };
        }

        return {
            /**
             * ساخت ادیتور روی یک textarea.
             *
             * @param {string|HTMLElement} target
             * @param {Object} options تنظیمات دلخواه که روی پیش‌فرض‌ها اعمال می‌شود.
             * @returns {Promise|null}
             */
            create: function (target, options) {
                var element = typeof target === 'string' ? document.querySelector(target) : target;

                if (!element || typeof tinymce === 'undefined') {
                    return null;
                }

                var config = Object.assign(baseConfig(), options || {});
                config.target = element;

                return tinymce.init(config);
            },

            /** محتوای ادیتورها را داخل textareaها ذخیره می‌کند (قبل از submit). */
            save: function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            },

            /** آیا متن ادیتور خالی است؟ */
            isEmpty: function (target) {
                var element = typeof target === 'string' ? document.querySelector(target) : target;

                if (typeof tinymce !== 'undefined' && element) {
                    var editor = tinymce.get(element.id);

                    if (editor) {
                        return editor.getContent({ format: 'text' }).trim() === '';
                    }
                }

                return ! element || element.value.trim() === '';
            },

            /** فوکوس روی ادیتور. */
            focus: function (target) {
                if (typeof tinymce !== 'undefined') {
                    var editor = tinymce.get(typeof target === 'string' ? target.replace('#', '') : target.id);

                    if (editor) {
                        editor.focus();
                    }
                }
            }
        };
    })();
</script>
