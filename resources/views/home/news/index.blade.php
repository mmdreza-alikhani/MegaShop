@extends('home.layout.master')

@section('title')
    تمامی اخبار
@endsection

@section('content')

@include('home.sections.backtotop')

<!-- START: Breadcrumbs -->
<div class="nk-gap-1"></div>

<div class="container">
    <ul class="nk-breadcrumbs text-right" style="direction: rtl">
        <li><a href="{{ route('home.index') }}">خانه</a></li>

        <li><span class="fa fa-angle-left"></span></li>

        <li><span>تمامی اخبار</span></li>
    </ul>
</div>

<div class="nk-gap-1"></div>
<!-- END: Breadcrumbs -->

<div class="container my-2">
    <!-- START: Posts FullWidth -->
    <div class="nk-blog-fullwidth">

        <!-- START: Post -->
        @foreach($allNews as $news)
            <div class="nk-blog-post">
                <a href="{{ route('home.news.show', ['news' => $news->slug]) }}" class="nk-post-img">
                    <img src="{{ env('NEWS_IMAGE_UPLOAD_PATH') . '/' . $news->primary_image }}" alt="{{ $news->name }}-image">
                    <span class="nk-post-comments-count">4</span>
                </a>
                <div class="nk-gap-2"></div>
                <div class="row vertical-gap" style="direction: rtl">
                    <div class="col-md-8 col-lg-9">
                        <h2 class="nk-post-title h4 text-right"><a href="{{ route('home.news.show', ['news' => $news->slug]) }}">{{ $news->name }}</a></h2>
                        <div class="nk-gap"></div>
                        <div class="nk-post-text text-right">
                            <p style="direction: rtl">{{ substr(strip_tags($news->text), 0 , 500) . '...' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="nk-post-by">
                            <img src="{{ Str::contains($news->author->avatar, 'https://') ? $news->author->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $news->author->avatar }}" alt="{{ $news->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                            نوشته
                            <a href="#">{{ $news->author->username }}</a>
                            در تاریخ
                            <br>  {{ removeTimeFromDate(verta($news->updated_at)) }}
                        </div>
                        <div class="nk-gap-3"></div>
                        <div class="text-right">
                            <a href="{{ route('home.news.show', ['news' => $news->slug]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1 w-100">ادامه مطلب</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- END: Post -->

        <!-- START: Pagination -->
        {{ $allNews->links('vendor.pagination.bootstrap-5') }}
        <!-- END: Pagination -->
    </div>
    <!-- END: Posts FullWidth -->
</div>

@endsection
