<div class="row vertical-gap">
@foreach($platforms as $platform)
    <div class="col-lg-3">
        <div class="nk-feature-1">
            <div class="nk-feature-icon">
                <img src="{{ $platform->image ? Storage::url(config('upload.platform_path') . '/') . $platform->image : asset('assets/images/icon-gamepad.png') }}" alt="{{ $platform->title }}-image">
            </div>
            <div class="nk-feature-cont text-center w-100">
                <h3 class="nk-feature-title"><a>{{ $platform->title }}</a></h3>
                <h4 class="nk-feature-title text-main-1"><a href="{{ route('home.platforms.products.show', ['platform' => $platform->slug]) }}">تمام محصولات</a></h4>
            </div>
        </div>
    </div>
@endforeach
</div>
