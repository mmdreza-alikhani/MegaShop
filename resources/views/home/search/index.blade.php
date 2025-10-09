@extends('home.layout.master')

@section('title')
    نتایج:  {{ $keyword }}
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
                <li><span>محصولات مربوطه به: {{ $keyword }}</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <div class="col-lg-12">
            <div class="row vertical-gap">
                @foreach($products as $product)
                    @include('home.sections.product', ['product' => $product])
                @endforeach
            </div>
            <div class="nk-gap-3"></div>
        </div>
        <div class="nk-gap-1"></div>
        <div class="container my-3">
            <ul class="nk-breadcrumbs text-right" style="direction: rtl">
                <li><span>مقالات مربوطه به: {{ $keyword }}</span></li>
            </ul>
        </div>
        <div class="nk-gap-1"></div>
        <div class="col-lg-12">
            <div class="nk-blog-grid">
                <div class="row">
                    @foreach($posts as $post)
                        @include('home.sections.post', ['post' => $post])
                    @endforeach
                </div>
            </div>
            <div class="nk-gap-3"></div>
        </div>
        <div class="nk-gap-1"></div>
    </div>
    <div class="nk-gap-2"></div>
</div>
@endsection
