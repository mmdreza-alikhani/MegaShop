<div class="modal fade mk-modal mkcrop-modal" id="avatarCropperModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <header class="mk-modal-head">
                <div>
                    <h3>برش تصویر پروفایل</h3>
                    <p>تصویر را جابه‌جا و بزرگ‌نمایی کنید تا کادر دقیق دلخواهتان ساخته شود.</p>
                </div>
                <button type="button" class="mk-modal-close" data-dismiss="modal" aria-label="بستن">
                    <span class="fa fa-times"></span>
                </button>
            </header>

            <div class="mk-modal-body">
                <div class="mkcrop-layout">
                    <div class="mkcrop-stage" data-crop-stage>
                        <canvas class="mkcrop-canvas" data-crop-canvas></canvas>
                        <div class="mkcrop-mask" aria-hidden="true"></div>
                        <span class="mkcrop-badge">کادر برش</span>
                        <span class="mkcrop-busy" aria-hidden="true">
                            <span class="fa fa-circle-o-notch fa-spin"></span>
                        </span>
                    </div>

                    <aside class="mkcrop-side">
                        <span class="mkcrop-side-label">پیش‌نمایش آواتار</span>
                        <canvas class="mkcrop-mini" data-crop-preview></canvas>

                        <label class="mkcrop-zoom">
                            <span class="mkcrop-zoom-title">
                                <span class="fa fa-search-plus"></span>
                                بزرگ‌نمایی
                                <b data-crop-zoom-label>۱۰۰٪</b>
                            </span>
                            <input type="range" min="100" max="400" step="1" value="100" data-crop-zoom>
                        </label>

                        <div class="mkcrop-tools">
                            <button type="button" class="mk-btn mk-btn-outline mk-btn-sm" data-crop-rotate>
                                <span class="fa fa-repeat"></span> چرخش
                            </button>
                            <button type="button" class="mk-btn mk-btn-outline mk-btn-sm" data-crop-reset>
                                <span class="fa fa-undo"></span> بازنشانی
                            </button>
                        </div>

                        <label class="mkcrop-size">
                            <span>اندازه خروجی</span>
                            <select data-crop-size>
                                <option value="256">۲۵۶ × ۲۵۶</option>
                                <option value="512" selected>۵۱۲ × ۵۱۲</option>
                                <option value="1024">۱۰۲۴ × ۱۰۲۴</option>
                            </select>
                        </label>
                    </aside>
                </div>

                <p class="mkcrop-hint">
                    <span class="fa fa-info-circle"></span>
                    خروجی همیشه مربع است و حجمش زیر {{ number_format((int) config('upload.max_file_kb')) }} کیلوبایت نگه داشته می‌شود.
                </p>
            </div>

            <footer class="mk-modal-foot">
                <button type="button" class="mk-btn mk-btn-ghost" data-dismiss="modal">انصراف</button>
                <button type="button" class="mk-btn mk-btn-primary" data-crop-save>ذخیره تصویر</button>
            </footer>
        </div>
    </div>
</div>

<script>
    window.MK_AVATAR = {
        input: '#avatarInput',
        trigger: '#avatarPickBtn',
        preview: '#profileAvatarPreview',
        status: '#avatarStatus',
        modal: '#avatarCropperModal',
        endpoint: @json(route('home.profile.avatar')),
        csrf: @json(csrf_token()),
        maxKb: {{ (int) config('upload.max_file_kb') }}
    };
</script>
<script src="{{ asset('assets/js/avatar-cropper.js') }}?v={{ filemtime(public_path('assets/js/avatar-cropper.js')) }}"></script>
