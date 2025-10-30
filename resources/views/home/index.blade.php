@extends('home.layout.master')

@section('title')
    صفحه اصلی
@endsection

@section('content')
    <div class="nk-gap-2"></div>
    <div class="container">
        @include('home.sections.top-header-slider')
        <div class="nk-gap-2"></div>
{{--        @include('home.sections.platforms')--}}
        <div class="nk-gap-2"></div>
        @include('home.sections.latest-news')
        <div class="nk-gap-2"></div>
        @include('home.sections.latest-articles')
        <div class="nk-gap-2"></div>
        @include('home.sections.best-selling')
    </div>
    <div class="nk-gap-2"></div>
@endsection
