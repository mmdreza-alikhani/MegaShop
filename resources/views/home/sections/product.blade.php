@php
    $cardPrice = $product->best_selling_price;
    $isDiscounted = (bool) ($cardPrice?->is_discounted);
    $inWishlist = (bool) ($product->in_wishlist ?? false);
    $ratingValue = (int) round($product->rates_avg_rate ?? 0);
    $productUrl = route('home.products.show', ['product' => $product->slug]);
@endphp
<div class="col-md-6">
    <div class="nk-product-cat">
        <div class="nk-product-cont col-8">
            <h3 class="nk-product-title h5 text-right" style="direction: rtl">
                <a href="{{ $productUrl }}">{{ $product->title }}</a>
                @auth
                    <form class="d-inline"
                          action="{{ $inWishlist ? route('home.products.wishlist.remove', ['product' => $product->slug]) : route('home.products.wishlist.add', ['product' => $product->slug]) }}"
                          method="POST">
                        @csrf
                        @if($inWishlist)
                            @method('DELETE')
                        @endif
                        <button type="submit" class="btn bg-transparent" title="افزودن به علاقه‌مندی‌ها">
                            <i class="fa fa-heart fa-lg {{ $inWishlist ? 'text-danger' : 'text-light' }}"></i>
                        </button>
                    </form>
                @else
                    <a href="#" data-toggle="modal" data-target="#modalLogin" class="btn bg-transparent"
                       title="برای افزودن به علاقه‌مندی‌ها وارد شوید">
                        <i class="fa fa-heart fa-lg text-light"></i>
                    </a>
                @endauth
            </h3>
            <div class="nk-gap-1"></div>

            <div class="nk-product-rating"
                 data-rating-stars="5"
                 data-rating-value="{{ $ratingValue }}"
                 data-rating-color="#dd163b"
                 data-rating-readonly="true">
            </div>

            <div class="nk-product-price text-right" style="direction: rtl">
                @if($cardPrice === null)
                    <div class="nk-product-price text-right"><span>ناموجود</span></div>
                @elseif($isDiscounted)
                    <div class="nk-product-price text-right">
                        <span style="text-decoration: line-through;opacity: .5">{{ number_format($cardPrice->price) }}</span>
                        <span class="ms-3">تومان</span>
                    </div>
                    <div class="nk-product-price text-right" style="margin-right: 20px;margin-top: 5px">
                        <span style="color: #dd163b">{{ number_format($cardPrice->sale_price) }}</span>
                        <span class="ms-3">تومان</span>
                    </div>
                @else
                    <div class="nk-product-price text-right">
                        <span>{{ number_format($cardPrice->price) }}</span>
                        <span class="ms-3">تومان</span>
                    </div>
                @endif
            </div>
            <a href="{{ $productUrl }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">مشخصات</a>
        </div>
        <a class="nk-product-image col-4" style="height: 160px;" href="{{ $productUrl }}">
            <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $product->primary_image) }}"
                 alt="{{ $product->title }}">
        </a>
    </div>
</div>
