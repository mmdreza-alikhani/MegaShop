@extends('home.layout.master')

@section('title')
    {{ $news->title }}
@endsection

@section('content')

    @include('home.sections.backtotop')

    <!-- START: Breadcrumbs -->
    <div class="nk-gap-1"></div>

    <div class="container">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><a href="{{ route('home.news.index') }}">تمامی اخبار</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><a href="{{ route('home.index') }}">{{ $news->title }}</a></li>
        </ul>
    </div>

    <div class="nk-gap-1"></div>
    <!-- END: Breadcrumbs -->

    <div class="container">
        <div class="row vertical-gap">
            <div class="col-lg-12">
                <!-- START: Post -->
                <div class="nk-blog-post nk-blog-post-single">
                    <!-- START: Post Text -->
                    <div class="nk-post-text mt-0">
                        <div class="nk-post-img">
                            <img src="{{ env('NEWS_IMAGE_UPLOAD_PATH') . '/' . $news->primary_image }}" alt="{{ $news->title }}-image">
                        </div>
                        <div class="nk-gap-1"></div>
                        <h1 class="nk-post-title h4 text-right" style="direction: rtl">{{ $news->title }}</h1>
                        <div class="nk-post-by text-right" style="direction: rtl">
                            <img src="{{ Str::contains($news->author->avatar, 'https://') ? $news->author->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $news->author->avatar }}" alt="{{ $news->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                            <a href="#">{{ $news->author->username }}</a>
                            نوشته:
                            <span class="text-left" style="direction: ltr">
                                {{ '(' . $news->updated_at->diffForHumans() . ')' }}
                            </span>
                            @can('manage-news')
                            <a href="{{ route('admin.news.edit' , ['news' => $news->id]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-info">
                                <span class="fa fa-edit"></span>
                                ویرایش
                            </a>
                            @endcan
                        </div>
                        <div class="nk-gap"></div>
                        <div class="text-right" style="direction: rtl;">
                            <p>
                                {!! $news->text !!}
                            </p>
                        </div>
                        <div class="nk-post-share" style="direction: rtl">
                            <span class="h5">اشتراک گذاری مقاله:</span>
                            <ul class="nk-social-links-2">
                                <li><span class="nk-social-facebook" title="Share page on Facebook" data-share="facebook"><span class="fab fa-facebook"></span></span></li>
                                <li><span class="nk-social-google-plus" title="Share page on Google+" data-share="google-plus"><span class="fab fa-google-plus"></span></span></li>
                                <li><span class="nk-social-twitter" title="Share page on Twitter" data-share="twitter"><span class="fab fa-twitter"></span></span></li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Post Text -->
                    <div class="nk-gap"></div>
                    <!-- START: Reply -->
                    @if(auth()->check())
                        <h3 class="h4 text-right" id="addComment">!نظر بده</h3>

                        <div class="nk-reply text-right" style="direction: rtl">
                            <form action="{{ route('home.comments.store', ['model' => 'news', 'id' => $news->id]) }}" method="post" class="nk-form">
                                @csrf
                                <div class="nk-gap-1"></div>
                                @include('home.sections.errors')
                                <textarea class="form-control required" name="text" rows="5" placeholder="نظر شما *" aria-required="true"></textarea>
                                <input type="hidden" name="replyOf" id="replyOf" value="0">
                                <div class="nk-gap-1"></div>
                                <button class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">ارسال</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-danger text-right"> برای نظر دادن ابتدا وارد شوید! </div>
                    @endif
                    <!-- END: Reply -->

                    <div class="nk-gap-3"></div>

                    <!-- START: Comments -->
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">{{ $news->comments->count() }}</span> نظر</span></h3>

                    <div class="nk-gap"></div>

                    <div class="nk-comments">
                        <!-- START: Comment -->
                            @include('home.sections.comments' , ['comments' => $news->comments->where('reply_of', 0)])
                        <!-- END: Comment -->
                    </div>
                    <!-- END: Comments -->

                    <!-- START: Similar News -->
                    <div class="nk-gap-2"></div>
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">اخبار</span> مشابه</span></h3>
                    <div class="nk-gap"></div>
                    <div class="row">
                        @foreach($related as $news)
                            <div class="col-md-6">
                                <div class="nk-blog-post">
                                    <a href="{{ route('home.news.show', ['news' => $news->slug]) }}" class="nk-post-img">
                                        <img src="{{ env('NEWS_IMAGE_UPLOAD_PATH') . '/' . $news->primary_image }}" alt="{{ $news->name }}-image">
                                        <span class="nk-post-comments-count">4</span>
                                    </a>
                                    <div class="nk-gap-2"></div>
                                    <div class="row vertical-gap" style="direction: rtl">
                                        <div class="col-md-8 col-lg-9">
                                            <h2 class="nk-post-title h4 text-right"><a href="{{ route('home.news.show', ['news' => $news->slug]) }}">{{ $news->title }}</a></h2>
                                            <div class="nk-gap"></div>
                                            <div class="nk-post-text text-right">
                                                <p style="direction: rtl">{{ substr(strip_tags($news->text), 0 , 500) . '...' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-3 text-right" style="direction: rtl">
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
                            </div>
                        @endforeach
                    </div>
                    <!-- END: Similar News -->
                </div>
                <!-- END: Post -->
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.replyBtn').on('click', function (){
            $('#replyOf').val($(this).data('id'))
        })
    </script>
@endsection
