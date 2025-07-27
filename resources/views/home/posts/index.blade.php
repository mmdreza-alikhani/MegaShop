@extends('home.layout.master')

@section('title')
    تمامی مقالات
@endsection

@section('content')
@include('home.sections.backtotop')
    <div class="nk-gap-1"></div>
    <div class="container">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><span>تمامی مقالات</span></li>
        </ul>
    </div>
    <div class="nk-gap-1"></div>

    <div class="container">
        <div class="nk-blog-fullwidth">
            @foreach($posts as $post)
                <div class="nk-blog-post">
                    <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="nk-post-img">
                        <img src="{{ env('POST_IMAGE_UPLOAD_PATH') . '/' . $post->image }}" alt="{{ $post->title }}-image">
                        <span class="nk-post-comments-count">{{ $post->comments()->count() }}</span>
                    </a>
                    <div class="nk-gap-2"></div>
                    <div class="row vertical-gap" style="direction: rtl">
                        <div class="col-md-8 col-lg-9">
                            <h2 class="nk-post-title h4 text-right"><a href="{{ route('home.posts.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></h2>
                            <div class="nk-gap"></div>
                            <div class="nk-post-text text-right">
                                <p style="direction: rtl">{{ substr(strip_tags($post->text), 0 , 500) . '...' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="nk-post-by">
                                <img src="{{ Str::contains($post->author->avatar, 'https://') ? $post->author->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $post->author->avatar }}" alt="{{ $post->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                                نوشته
                                <a href="#">{{ $post->author->username }}</a>
                                در تاریخ
                                <br>  {{ removeTimeFromDate(verta($post->updated_at)) }}
                            </div>
                            <div class="nk-gap-3"></div>
                            <div class="text-right">
                                <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1 w-100">ادامه مطلب</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $posts->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
