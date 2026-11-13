<div class="nk-gap-2"></div>
<h2 class="mk-section-title"><span>پرفروش‌ترین‌ها</span></h2>
<div class="mk-products-grid">
    @foreach($products as $product)
        @include('home.sections.product', ['product' => $product])
    @endforeach
</div>
