@extends('home.layout.master')

@section('title', $category->title)

@section('content')
    <div class="mk-page-head">
        <div class="container">
            <ul class="mk-breadcrumb">
                <li><a href="{{ route('home.index') }}">خانه</a></li>
                <li><a href="{{ route('home.products.index') }}">محصولات</a></li>
                @if($category->parent)
                    <li>
                        <a href="{{ route('home.categories.show', ['category' => $category->parent->slug]) }}">
                            {{ $category->parent->title }}
                        </a>
                    </li>
                @endif
                <li>{{ $category->title }}</li>
            </ul>
            <h1 class="mk-page-title">{{ $category->title }}</h1>
            @if($category->description)
                <p class="text-muted mt-2">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="row vertical-gap">
            <div class="col-lg-9">
                <form method="GET" action="{{ route('home.categories.show', ['category' => $category->slug]) }}"
                      class="mk-toolbar">
                    <div class="mk-search">
                        <input type="text" class="mk-input" name="q" value="{{ request('q') }}"
                               placeholder="جستجو در {{ $category->title }}...">
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
                        در این دسته محصولی با این مشخصات پیدا نشد.
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
                    @php
                        $siblings = $category->parent
                            ? $category->parent->children
                            : $category->children;
                    @endphp

                    @if($siblings->isNotEmpty())
                        <div class="mk-side-card">
                            <h3>{{ $category->parent?->title ?? 'زیردسته‌ها' }}</h3>
                            <ul class="mk-side-list">
                                @foreach($siblings as $sibling)
                                    <li>
                                        <a class="{{ $sibling->slug === $category->slug ? 'active' : '' }}"
                                           href="{{ route('home.categories.show', ['category' => $sibling->slug]) }}">
                                            <span>{{ $sibling->title }}</span>
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($platforms->isNotEmpty())
                        <div class="mk-side-card">
                            <h3>پلتفرم</h3>
                            <ul class="mk-side-list">
                                @foreach($platforms as $platform)
                                    <li>
                                        <a href="{{ route('home.categories.show', ['category' => $category->slug, 'platform' => $platform]) }}">
                                            <span>{{ $platform }}</span>
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($brands->isNotEmpty())
                        <div class="mk-side-card">
                            <h3>برندها</h3>
                            <ul class="mk-side-list">
                                @foreach($brands as $brand)
                                    <li>
                                        <a href="{{ route('home.categories.show', ['category' => $category->slug, 'brand' => $brand]) }}">
                                            <span>{{ $brand }}</span>
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>

    <div class="nk-gap-3"></div>
@endsection
