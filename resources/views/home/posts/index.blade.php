@extends('home.layout.master')

@section('title', 'مقالات')

@section('content')
    <div class="mk-page-head">
        <div class="container">
            <ul class="mk-breadcrumb">
                <li><a href="{{ route('home.index') }}">خانه</a></li>
                <li>مقالات</li>
            </ul>
            <h1 class="mk-page-title">مقالات و راهنماها</h1>
        </div>
    </div>

    <div class="container">
        @if($posts->isEmpty())
            <div class="mk-empty">هنوز مقاله‌ای منتشر نشده است.</div>
        @else
            <div class="row vertical-gap">
                @foreach($posts as $post)
                    @include('home.sections.post', ['post' => $post])
                @endforeach
            </div>

            {{ $posts->links('vendor.pagination.mk') }}
        @endif
    </div>

    <div class="nk-gap-3"></div>
@endsection
