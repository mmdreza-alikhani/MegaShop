@extends('home.profile.master')

@section('section')
    @php
        $wishlistProducts = $wishlist
            ->map(fn ($item) => $item->product)
            ->filter()
            ->each(fn ($product) => $product->in_wishlist = true)
            ->values();
    @endphp

    <div class="mk-panel">
        <header class="mk-panel-head"><div><h2>علاقه‌مندی‌ها</h2><p>محصولاتی که برای بعد ذخیره کرده‌اید.</p></div></header>
        @if($wishlistProducts->isEmpty())
            <div class="mk-empty w-100">
                شما محصول مورد علاقه‌ای ندارید!
                <a href="{{ route('home.products.index') }}">مشاهده محصولات</a>
            </div>
        @else
            <div class="mk-products-grid w-100">
                @foreach($wishlistProducts as $product)
                    @include('home.sections.product', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
@endsection
