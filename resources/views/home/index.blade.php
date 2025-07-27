@extends('home.layout.master')

@section('title')
    صفحه اصلی
@endsection

@section('content')
    <div class="nk-gap-2"></div>

    <div class="container">

        @include('home.sections.top-header-slider')

        <div class="nk-gap-2"></div>

        @include('home.sections.platforms')

        <!-- START: Latest News -->
        @include('home.sections.latest-news')
        <!-- END: Latest News -->

        <div class="nk-gap-2"></div>

        <!-- START: Latest Posts -->
        @include('home.sections.latest-articles')
        <!-- END: Latest Posts -->

        <!-- START: Best Selling -->
        @include('home.sections.best-selling')
        <!-- END: Best Selling -->

    </div>

    <div class="nk-gap-4"></div>
@endsection
