<div class="nk-image-slider mk-hero" data-autoplay="7000">
    @foreach($banners as $banner)
        <div class="nk-image-slider-item">
            <img src="{{ Storage::url(config('upload.banner_path') . '/') . $banner->image }}"
                 alt="{{ $banner->title }}" class="nk-image-slider-img"
                 data-thumb="{{ Storage::url(config('upload.banner_path') . '/') . $banner->image }}">

            <div class="nk-image-slider-content mk-hero-content">
                <span class="mk-hero-chip">
                    <span class="fa fa-bolt"></span>
                    پیشنهاد ویژه
                </span>

                <h3 class="mk-hero-title">{{ $banner->title }}</h3>

                <p class="mk-hero-text">{{ $banner->text }}</p>

                <a href="{{ $banner->button_link }}" class="mk-hero-cta">
                    {{ $banner->button_text ?: 'مشاهده محصولات' }}
                    <span class="fa fa-angle-left"></span>
                </a>
            </div>
        </div>
    @endforeach
</div>

@can('manage-general')
    <div class="mk-hero-admin">
        <a href="{{ route('admin.banners.index') }}" class="mk-btn mk-btn-outline mk-btn-sm">
            <span class="fa fa-pencil"></span>
            ویرایش بنرها
        </a>
    </div>
@endcan
