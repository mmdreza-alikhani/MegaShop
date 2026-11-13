@extends('home.layout.master')

@section('title')
    همه محصولات
@endsection

@section('content')
    <div class="nk-gap-1"></div>
    <div class="container my-3">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>
            <li><span class="fa fa-angle-left"></span></li>
            <li><span>همه محصولات</span></li>
        </ul>
    </div>
    <div class="nk-gap-1"></div>

    <div class="container">
        <div class="row vertical-gap">
            <div class="col-lg-8">
                <form method="GET" action="{{ route('home.products.index') }}" class="mb-3">
                    <div class="row vertical-gap">
                        <div class="col-lg-5 col-12">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                       placeholder="جستجو در محصولات...">
                                <div class="input-group-append">
                                    <button class="nk-btn nk-btn-color-main-1" type="submit">
                                        <span class="ion-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <select class="form-control" name="sortBy" style="direction: rtl">
                                <option value="">مرتب سازی</option>
                                <option value="highest" @selected(request('sortBy') === 'highest')>بیشترین قیمت</option>
                                <option value="lowest" @selected(request('sortBy') === 'lowest')>کمترین قیمت</option>
                                <option value="latest" @selected(request('sortBy') === 'latest')>جدیدترین</option>
                                <option value="oldest" @selected(request('sortBy') === 'oldest')>قدیمی‌ترین</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-6 text-right" style="direction: rtl">
                            <label class="form-check-label" for="discountedProducts">فقط تخفیف‌دار</label>
                            <input type="checkbox" id="discountedProducts" name="discount" value="true"
                                   @checked(request()->boolean('discount'))>
                            <button class="nk-btn nk-btn-rounded nk-btn-color-white float-left" type="submit">اعمال</button>
                        </div>
                    </div>
                </form>

                @if($products->isEmpty())
                    <div class="alert alert-info text-right" style="direction: rtl">
                        محصولی با این مشخصات پیدا نشد!
                    </div>
                @else
                    <div class="row vertical-gap">
                        @foreach($products as $product)
                            @include('home.sections.product', ['product' => $product])
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
                <div class="nk-gap-3"></div>
            </div>

            <div class="col-lg-4">
                <aside class="nk-sidebar nk-sidebar-right">
                    <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                        <h4 class="nk-widget-title"><span><span class="text-main-1">دسته‌بندی</span> محصولات</span></h4>
                        <div class="nk-widget-content">
                            <ul class="nk-widget-categories text-right" style="direction: rtl">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('home.categories.show', ['category' => $category->slug]) }}">
                                            {{ $category->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                        <h4 class="nk-widget-title"><span><span class="text-main-1">پلتفرم</span></span></h4>
                        <div class="nk-widget-content">
                            <ul class="nk-widget-categories text-right" style="direction: rtl">
                                @foreach($platforms as $platform)
                                    <li>
                                        <a href="{{ route('home.products.index', ['platform' => $platform]) }}">
                                            {{ $platform }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
