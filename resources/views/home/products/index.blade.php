@extends('home.layout.master')

@section('title', 'همه محصولات')

@section('content')
    <div class="mk-page-head">
        <div class="container">
            <ul class="mk-breadcrumb">
                <li><a href="{{ route('home.index') }}">خانه</a></li>
                <li>همه محصولات</li>
            </ul>
            <h1 class="mk-page-title">همه محصولات</h1>
        </div>
    </div>

    <div class="container">
        <div class="row vertical-gap">
            <div class="col-lg-9">
                <form method="GET" action="{{ route('home.products.index') }}" class="mk-toolbar">
                    <div class="mk-search">
                        <input type="text" class="mk-input" name="q" value="{{ request('q') }}"
                               placeholder="جستجو در محصولات...">
                        <button class="mk-btn mk-btn-primary" type="submit">
                            <span class="fa fa-search"></span>
                            جستجو
                        </button>
                    </div>

                    <select class="mk-select" name="sortBy">
                        <option value="">مرتب‌سازی</option>
                        <option value="highest" @selected(request('sortBy') === 'highest')>بیشترین قیمت</option>
                        <option value="lowest" @selected(request('sortBy') === 'lowest')>کمترین قیمت</option>
                        <option value="latest" @selected(request('sortBy') === 'latest')>جدیدترین</option>
                        <option value="oldest" @selected(request('sortBy') === 'oldest')>قدیمی‌ترین</option>
                    </select>

                    <label class="mk-check">
                        <input type="checkbox" name="discount" value="true" @checked(request()->boolean('discount'))>
                        فقط تخفیف‌دار
                    </label>

                    <button class="mk-btn" type="submit">اعمال</button>
                </form>

                @if($products->isEmpty())
                    <div class="mk-empty">
                        محصولی با این مشخصات پیدا نشد. فیلترها را تغییر دهید یا
                        <a href="{{ route('home.products.index') }}">همه محصولات</a> را ببینید.
                    </div>
                @else
                    <div class="mk-products-grid">
                        @foreach($products as $product)
                            @include('home.sections.product', ['product' => $product])
                        @endforeach
                    </div>

                    {{ $products->links('vendor.pagination.mk') }}
                @endif
            </div>

            <div class="col-lg-3">
                <aside>
                    <div class="mk-side-card">
                        <h3>دسته‌بندی‌ها</h3>
                        <ul class="mk-side-list">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('home.categories.show', ['category' => $category->slug]) }}">
                                        <span>{{ $category->title }}</span>
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mk-side-card">
                        <h3>پلتفرم</h3>
                        <ul class="mk-side-list">
                            @foreach($platforms as $platform)
                                <li>
                                    <a href="{{ route('home.products.index', ['platform' => $platform]) }}">
                                        <span>{{ $platform }}</span>
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="nk-gap-3"></div>
@endsection
