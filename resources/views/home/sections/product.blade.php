@php
    $cardPrice = $product->best_selling_price;
    $isDiscounted = (bool) ($cardPrice?->is_discounted);
    $inWishlist = (bool) ($product->in_wishlist ?? false);
    $ratingValue = (int) round($product->rates_avg_rate ?? 0);
    $productUrl = route('home.products.show', ['product' => $product->slug]);
    $isAvailable = $cardPrice !== null && (int) $cardPrice->quantity > 0;
    $discountPercent = $isDiscounted && $cardPrice->price > 0
        ? (int) round((($cardPrice->price - $cardPrice->sale_price) / $cardPrice->price) * 100)
        : 0;
@endphp

<article class="mk-product-card">
    <a href="{{ $productUrl }}" class="mk-card-media">
        <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $product->primary_image) }}"
             onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
             alt="{{ $product->title }}" loading="lazy">

        @if($discountPercent > 0)
            <span class="mk-card-badge">{{ $discountPercent }}٪ تخفیف</span>
        @elseif(! $isAvailable)
            <span class="mk-card-badge">ناموجود</span>
        @endif
    </a>

    @auth
        <form action="{{ $inWishlist ? route('home.products.wishlist.remove', ['product' => $product->slug]) : route('home.products.wishlist.add', ['product' => $product->slug]) }}"
              method="POST" class="mk-card-wish-form">
            @csrf
            @if($inWishlist)
                @method('DELETE')
            @endif
            <button type="submit" class="mk-card-wish {{ $inWishlist ? 'is-active' : '' }}"
                    title="{{ $inWishlist ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}">
                <span class="fa fa-heart"></span>
            </button>
        </form>
    @else
        <button type="button" class="mk-card-wish" data-toggle="modal" data-target="#modalLogin"
                title="برای افزودن به علاقه‌مندی‌ها وارد شوید">
            <span class="fa fa-heart"></span>
        </button>
    @endauth

    <div class="mk-card-body">
        <a href="{{ $productUrl }}" class="mk-card-title" title="{{ $product->title }}">{{ $product->title }}</a>

        <div class="mk-card-meta">
            @if($product->brand)
                <span>{{ $product->brand->title }}</span>
            @endif
            <span class="mk-card-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="fa fa-star{{ $i <= $ratingValue ? '' : '-o' }}"></span>
                @endfor
            </span>
        </div>

        <div class="mk-card-prices">
            @if($cardPrice === null)
                <span class="mk-card-out">ناموجود</span>
            @elseif($isDiscounted)
                <span class="mk-card-old-price">{{ number_format($cardPrice->price) }}</span>
                <span class="mk-card-price">
                    {{ number_format($cardPrice->sale_price) }}
                    <small>تومان</small>
                </span>
            @else
                <span class="mk-card-price">
                    {{ number_format($cardPrice->price) }}
                    <small>تومان</small>
                </span>
            @endif
        </div>

        <a href="{{ $productUrl }}" class="mk-card-cta">مشخصات و خرید</a>
    </div>
</article>
