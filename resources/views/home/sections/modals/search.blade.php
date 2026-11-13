<div class="nk-modal modal fade mk-search-modal" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content mk-search">
            <button type="button" class="mk-search-close" data-dismiss="modal" aria-label="بستن">&times;</button>

            <h3>جستجو در {{ config('brand.name') }}</h3>
            <p class="mk-search-sub">نام محصول، برند یا دسته‌بندی را وارد کنید.</p>

            <form action="{{ route('home.search') }}" method="GET" class="mk-search-form">
                <span class="fa fa-search mk-search-icon"></span>
                <input type="text" name="q" class="mk-input" placeholder="مثلاً: گوشی سامسونگ"
                       value="{{ request('q') }}" minlength="2" required autocomplete="off">
                <button type="submit" class="mk-btn mk-btn-primary">جستجو</button>
            </form>

            <div class="mk-search-hints">
                <span>جستجوهای پرطرفدار:</span>
                <a href="{{ route('home.search', ['q' => 'iPhone']) }}">iPhone</a>
                <a href="{{ route('home.search', ['q' => 'سامسونگ']) }}">سامسونگ</a>
                <a href="{{ route('home.search', ['q' => 'شیائومی']) }}">شیائومی</a>
                <a href="{{ route('home.search', ['q' => 'لپ‌تاپ']) }}">لپ‌تاپ</a>
                <a href="{{ route('home.search', ['q' => 'هدفون']) }}">هدفون</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.jQuery) {
                return;
            }

            var $ = window.jQuery;

            $('#modalSearch').on('shown.bs.modal', function () {
                $(this).find('input[name="q"]').trigger('focus').select();
            });
        });
    </script>
@endpush
