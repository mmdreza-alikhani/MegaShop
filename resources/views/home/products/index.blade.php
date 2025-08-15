@extends('home.layout.master')

@section('title')
    همه محصولات
@endsection

@section('content')
    <div class="container">

        <div class="nk-gap-2"></div>
        <div class="row vertical-gap">
            <div class="nk-gap-1"></div>
            <div class="container my-3">
                <ul class="nk-breadcrumbs text-right" style="direction: rtl">
                    <li><a href="{{ route('home.index') }}">خانه</a></li>

                    <li><span class="fa fa-angle-left"></span></li>

                    <li><a href="#">دسته بندی ها</a></li>

                    <li><span class="fa fa-angle-left"></span></li>

                    <li><span>{{ $category->title }}</span></li>
                </ul>
            </div>
            <div class="nk-gap-1"></div>

            <div class="col-lg-8">
                <div class="row vertical-gap">
                    <div class="col-lg-6 col-12">
                        <div class="form-check form-switch text-right" style="direction: rtl">
                            <label class="form-check-label" for="discountedProducts">محصولات تخفیف دار</label>
                            <input type="checkbox" id="discountedProducts" onchange="filter()" {{ request()->has('discount') && request()->discount == 'true'  ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <select class="form-control" id="sortBy" onchange="filter()" style="direction: rtl">
                            <option value="" disabled selected>مرتب سازی</option>
                            <option value="highest" {{ request()->has('sortBy') && request()->sortBy == 'highest'  ? 'selected' : '' }}>بیشترین قیمت</option>
                            <option value="lowest" {{ request()->has('sortBy') && request()->sortBy == 'lowest'  ? 'selected' : '' }}>کمترین قیمت</option>
                            <option value="latest" {{ request()->has('sortBy') && request()->sortBy == 'latest'  ? 'selected' : '' }}>جدیدترین</option>
                            <option value="oldest" {{ request()->has('sortBy') && request()->sortBy == 'oldest'  ? 'selected' : '' }}>قدیمیترین</option>
                        </select>
                    </div>
                    @foreach($products as $product)
                        @include('home.sections.product', ['product' => $product])
                    @endforeach
                </div>

                <div style="display: flex;justify-content: center">
                    {{ $products->withQueryString()->links() }}
                </div>
                <div class="nk-gap-3"></div>
            </div>

            <div class="col-lg-4">
                <form id="filterForm" method="GET">
                    <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">

                        <div class="nk-widget">
                            <div class="nk-widget-content">
                                <div class="input-group">
                                    <button onclick="filter()" type="button" class="nk-btn nk-btn-color-main-1"><span class="ion-search"></span></button>
                                    <input id="searchInput" type="text" class="form-control" value="{{ request()->has('search') ? request()->search : '' }}" placeholder="جستجو در دسته بندی {{ $category->name }}">
                                </div>
                            </div>
                        </div>

                        <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                            <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl;background-color: #293139">
                                <li><span style="font-size: 24px">دسته بندی: {{ $category->parent ? $category->parent->title : $category->title }}</span></li>
                            </ul>
                            <div class="nk-widget-content">
                                <ul class="nk-widget-categories text-right" style="direction: rtl">
                                    @foreach($category->parent ? $category->parent->children : $category->children as $childCategory)
                                        <li><a class="{{ $childCategory->slug == $category->slug ? 'active-link' : '' }}" href="{{ route('home.categories.index', ['category' => $childCategory->slug]) }}"> {{ $childCategory->name }} </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                            <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl;background-color: #293139">
                                <li><span style="font-size: 24px">پلتفرم</span></li>
                            </ul>
                            <div class="nk-widget-content">
                                <ul class="nk-widget-categories text-right" style="direction: rtl">
                                    @foreach($platforms as $key => $value)
                                        <li class="my-2">
                                            <label class="form-check-label" for="platform-{{ $value }}">
                                                {{ $value }}
                                            </label>
                                            <input class="form-check-input m-2 platform" type="checkbox" onchange="filter()" value="{{ $value }}" id="platform-{{ $value }}" {{ request()->platform && in_array( $value, explode('-', request('platform')) ) ? 'checked' : '' }}>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @foreach($filters as $filter)
                            <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                                <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl;background-color: #293139">
                                    <li><span style="font-size: 24px">{{ $filter->title }}</span></li>
                                </ul>
                                <div class="nk-widget-content">
                                    <ul class="nk-widget-categories text-right" style="direction: rtl">
                                        @foreach($filter->filterValues as $value)
                                            <li class="my-2">
                                                <label class="form-check-label" for="attribute-{{ $value->value }}">
                                                    {{ $value->value }}
                                                </label>
                                                <input class="form-check-input m-2 attribute-{{ $filter->id }}" type="checkbox" onchange="filter()" value="{{ $value->value }}" id="attribute-{{ $value->value }}" {{ request()->has('attribute.' . $filter->id) && in_array( $value->value, explode('-', request()->filter[$filter->id] ) ) ? 'checked' : '' }}>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                        {{--  End Filters  --}}

                        {{--  Variation  --}}
                        <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                            <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl;background-color: #293139">
                                <li><span style="font-size: 24px">{{ $variation->title }}</span></li>
                            </ul>
                            <div class="nk-widget-content">
                                <ul class="nk-widget-categories text-right" style="direction: rtl">
                                    @foreach($variation->variationValues as $value)
                                        <li class="my-2">
                                            <label class="form-check-label" for="variation-{{ $value->value }}">
                                                {{ $value->value }}
                                            </label>
                                            <input class="form-check-input m-2 variation" type="checkbox" onchange="filter()" value="{{ $value->value }}" id="variation-{{ $value->value }}" {{ request()->variation && in_array( $value->value, explode('-', request('variation')) ) ? 'checked' : '' }}>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        {{--  End Variation  --}}
                    </aside>

                    @foreach($filters as $filter)
                        <input id="filter-{{ $filter->id }}" type="hidden" name="filter[{{ $filter->id }}]">
                    @endforeach
{{--                    <input id="filteredPlatform" type="hidden" name="platform">--}}
                    <input id="filteredVariation" type="hidden" name="variation">
                    <input id="filteredSortBy" type="hidden" name="sortBy">
                    <input id="filteredSearch" type="hidden" name="search">
                    <input id="filteredDiscount" type="hidden" name="discount">
                </form>
            </div>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
@section('scripts')
    <script>
        function filter(){

            let variation = $('.variation:checked').map(function(){
                return this.value;
            }).get().join('-')
            if(variation === ""){
                $("#filteredVariation").prop('disabled', 'true')
            }else{
                $("#filteredVariation").val(variation)
            }

            // let platform = $('.platform:checked').map(function(){
            //     return this.value;
            // }).get().join('-')
            // if(platform === ""){
            //     $("#filteredPlatform").prop('disabled', 'true')
            // }else{
            //     $("#filteredPlatform").val(platform)
            // }

            let attributes = @json($filters);
            attributes.map(attribute => {
                let valueAttribute = $(`.attribute-${attribute.id}:checked`).map(function(){
                    return this.value;
                }).get().join('-')
                if(valueAttribute === ""){
                    $(`#filteredAttribute-${attribute.id}`).prop('disabled', 'true')
                }else{
                    $(`#filteredAttribute-${attribute.id}`).val(valueAttribute)
                }
            })

            let discount = $('#discountedProducts').prop('checked')
            if(discount === false){
                $("#filteredDiscount").prop('disabled', 'true')
            }else{
                $("#filteredDiscount").val(discount)
            }

            let search = $('#searchInput').val();
            if(search === ""){
                $('#filteredSearch').prop('disabled', 'true')
            }else{
                $('#filteredSearch').val(search)
            }

            let sortBy = $('#sortBy').val();
            console.log(sortBy)
            if(sortBy === null){
                $("#filteredSortBy").prop('disabled', 'true')
            }else{
                $("#filteredSortBy").val(sortBy)
            }

             $('#filterForm').submit();
        }

        $('#filterForm').on('submit', function (event){
             event.preventDefault();
             let url = '{{ url()->current() }}'
             let newURL = url + '?' + decodeURIComponent($(this).serialize())
             console.log(newURL)
             $(location).attr('href' , newURL)
        })

    </script>
@endsection
