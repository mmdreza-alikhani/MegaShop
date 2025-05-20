@extends('home.layout.master')

@section('title')
    تمامی مقالات
@endsection

@section('content')

@include('home.sections.backtotop')

<!-- START: Breadcrumbs -->
<div class="nk-gap-1"></div>

<div class="container">
    <ul class="nk-breadcrumbs text-right" style="direction: rtl">
        <li><a href="{{ route('home.index') }}">خانه</a></li>

        <li><span class="fa fa-angle-left"></span></li>

        <li><span>تمامی مقالات</span></li>
    </ul>
</div>

<div class="nk-gap-1"></div>
<!-- END: Breadcrumbs -->

<div class="container">
    <!-- START: Posts FullWidth -->
    <div class="nk-blog-fullwidth">

        <!-- START: Post -->
        @foreach($articles as $article)
            <div class="nk-blog-post">
                <a href="{{ route('home.articles.show', ['article' => $article->slug]) }}" class="nk-post-img">
                    <img src="{{ env('ARTICLE_IMAGE_UPLOAD_PATH') . '/' . $article->primary_image }}" alt="{{ $article->name }}-image">
                    <span class="nk-post-comments-count">4</span>
                </a>
                <div class="nk-gap-2"></div>
                <div class="row vertical-gap" style="direction: rtl">
                    <div class="col-md-8 col-lg-9">
                        <h2 class="nk-post-title h4 text-right"><a href="{{ route('home.articles.show', ['article' => $article->slug]) }}">{{ $article->title }}</a></h2>
                        <div class="nk-gap"></div>
                        <div class="nk-post-text text-right">
                            <p style="direction: rtl">{{ substr(strip_tags($article->text), 0 , 500) . '...' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="nk-post-by">
                            <img src="{{ Str::contains($article->author->avatar, 'https://') ? $article->author->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $article->author->avatar }}" alt="{{ $article->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                            نوشته
                            <a href="#">{{ $article->author->username }}</a>
                            در تاریخ
                            <br>  {{ removeTimeFromDate(verta($article->updated_at)) }}
                        </div>
                        <div class="nk-gap-3"></div>
                        <div class="text-right">
                            <a href="{{ route('home.articles.show', ['article' => $article->slug]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1 w-100">ادامه مطلب</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- END: Post -->

        <!-- START: Pagination -->
        {{ $articles->links('vendor.pagination.bootstrap-5') }}
        <!-- END: Pagination -->
    </div>
    <!-- END: Posts FullWidth -->
</div>

@endsection
