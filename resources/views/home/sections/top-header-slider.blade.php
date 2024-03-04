<div class="nk-image-slider" data-autoplay="6000">
    @foreach($banners as $banner)
        <div class="nk-image-slider-item">
            <img src="{{ env('BANNER_IMAGES_UPLOAD_PATH') . '/' . $banner->image }}" alt="" class="nk-image-slider-img" data-thumb="{{ env('BANNER_IMAGES_UPLOAD_PATH') . '/' . $banner->image }}">
            <div class="nk-image-slider-content">
                <h3 class="h4 text-center">{{ $banner->title }}</h3>
                <span class="text-right">
                    <p class="text-white">{{ print_r($banner->text) }}</p>
                </span>
                <a href="{{ $banner->button_link }}" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1" style="display: flex;align-items: center;justify-content: center">{{ $banner->button_text }}</a>
            </div>
        </div>
    @endforeach
</div>
@can('manage-general')
    <div class="text-right mt-2">
        <a href="{{ route('admin.banners.index') }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-info">
        <span class="fa fa-edit"></span>
        ویرایش بنر ها
        </a>
    </div>
@endcan
