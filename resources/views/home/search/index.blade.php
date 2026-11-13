@extends('home.layout.master')

@section('title', 'جستجو: '.$keyword)

@section('content')
    <div class="mk-page-head">
        <div class="container">
            <ul class="mk-breadcrumb">
                <li><a href="{{ route('home.index') }}">خانه</a></li>
                <li>جستجو</li>
            </ul>
            <h1 class="mk-page-title">نتایج جستجو برای «{{ $keyword }}»</h1>
        </div>
    </div>

    <div class="container">
        <form method="GET" action="{{ route('home.search') }}" class="mk-toolbar">
            <div class="mk-search">
                <input type="text" class="mk-input" name="q" value="{{ $keyword }}" placeholder="عبارت جستجو...">
                <button class="mk-btn mk-btn-primary" type="submit">
                    <span class="fa fa-search"></span>
                    جستجو
                </button>
            </div>
        </form>

        <h2 class="mk-section-title">محصولات ({{ $products->count() }})</h2>

        @if($products->isEmpty())
            <div class="mk-empty">محصولی با این عبارت پیدا نشد.</div>
        @else
            <div class="mk-products-grid">
                @foreach($products as $product)
                    @include('home.sections.product', ['product' => $product])
                @endforeach
            </div>
        @endif

        <div class="nk-gap-2"></div>

        <h2 class="mk-section-title">مقالات ({{ $posts->count() }})</h2>

        @if($posts->isEmpty())
            <div class="mk-empty">مقاله‌ای با این عبارت پیدا نشد.</div>
        @else
            <div class="row vertical-gap">
                @foreach($posts as $post)
                    @include('home.sections.post', ['post' => $post])
                @endforeach
            </div>
        @endif
    </div>

    <div class="nk-gap-3"></div>
@endsection
