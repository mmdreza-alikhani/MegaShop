<div class="nk-gap-2"></div>
<h2 class="nk-decorated-h-2"><span><span class="text-main-1">پرفروش</span>ترین‌ها</span></h2>
<div class="mk-products-grid">
    @foreach($products as $product)
        @include('home.sections.product', ['product' => $product])
    @endforeach
</div>
