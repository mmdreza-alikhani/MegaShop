<div class="nk-gap-3"></div>
<h3 class="nk-decorated-h-2"><span><span class="text-main-1">پرفروش</span> ترینها</span></h3>
<div class="nk-gap"></div>
<div class="row vertical-gap">
@foreach($products as $product)
    @include('home.sections.product', ['product' => $product])
@endforeach
</div>
