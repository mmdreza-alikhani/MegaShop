@extends('home.layout.master')

@section('title')
    {{ $post->title }}
@endsection

@section('content')
    @include('home.sections.backtotop')

    <!-- START: Breadcrumbs -->
    <div class="nk-gap-1"></div>

    <div class="container">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><a href="{{ route('home.posts.index') }}">تمامی مقالات</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><a href="{{ route('home.index') }}">{{ $post->title }}</a></li>
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
                            <img src="{{ Storage::url(config('upload.post_path') . '/') . $post->image }}" alt="{{ $post->title }}-image">
                        </div>
                        <div class="nk-gap-1"></div>
                        <h1 class="nk-post-title h4 text-right" style="direction: rtl">{{ $post->title }}</h1>
                        <div class="nk-post-by text-right" style="direction: rtl">
                            <img src="{{ Storage::url(config('upload.user_avatar_path') . '/') . $post->author->avatar }}" alt="{{ $post->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                            <a href="#">{{ $post->author->username }}</a>
                            نوشته:
                            <span class="text-right" style="direction: ltr">
                                {{ '(' . $post->updated_at->diffForHumans() . ')' }}
                            </span>
                            @can('manage-posts')
                            <a href="{{ route('admin.posts.edit' , ['post' => $post->id]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-info">
                                <span class="fa fa-edit"></span>
                                ویرایش
                            </a>
                            @endcan
                        </div>
                        <div class="nk-gap"></div>
                        <div class="text-right" style="direction: rtl;">
                            <p>
                                {!! $post->text !!}
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
                        <div class="nk-gap-1"></div>
                        <div class="nk-post-share" style="direction: rtl">
                            <span class="h5">لینک کوتاه:</span>
                            <ul style="list-style: none; padding: 0; margin-top: 10px;">
                                <li style="display: flex; align-items: center; gap: 10px; direction: ltr;">
                                    <span id="shortLinkText">{{ config('app.url') . '/q/' . $shortLink }}</span>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyShortLink()">
                                        <span class="fa fa-copy"></span>
                                    </button>
                                </li>
                            </ul>
                            <small id="copyFeedback" class="text-success d-none">✅ لینک کپی شد!</small>
                        </div>

                    </div>
                    <!-- END: Post Text -->

                    <div class="nk-gap-3"></div>

                    <!-- START: Reply -->
                    @if(auth()->check())
                        <h3 class="h4 text-right" id="addComment">!نظر بده</h3>

                        <div class="nk-reply text-right" style="direction: rtl">
                            <form action="{{ route('home.comments.store', ['model' => 'post', 'id' => $post->id]) }}" method="post" class="nk-form">
                                @csrf
                                <div class="nk-gap-1"></div>
                                @include('home.sections.errors')

                                <div class="mk-reply-chip d-none" id="replyToChip">
                                        <span>در حال پاسخ به <strong id="replyToUser"></strong></span>
                                        <button type="button" class="mk-reply-chip-cancel" id="replyChipCancel" aria-label="لغو پاسخ">&times;</button>
                                    </div>
                                <textarea class="form-control required" name="text" rows="5" placeholder="نظر شما *" aria-required="true"></textarea>
                                <input type="hidden" name="replyOf" id="replyOf" value="0">
                                <div class="nk-gap-1"></div>
                                <button class="nk-btn nk-btn-rounded nk-btn-color-dark-3 float-right">ارسال</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-danger text-right mk-login-hint">
                                <span>برای نظر دادن ابتدا وارد شوید!</span>
                                <button type="button" class="mk-btn mk-btn-primary mk-btn-sm"
                                        data-toggle="modal" data-target="#modalLogin">ورود / ثبت‌نام</button>
                            </div>
                    @endif
                    <!-- END: Reply -->

                    <div class="nk-gap-3"></div>

                    <!-- START: Comments -->
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">{{ $post->comments->count() }}</span> نظر</span></h3>

                    <div class="nk-gap"></div>

                    <div class="nk-comments">
                        <!-- START: Comment -->
                        @include('home.sections.comments' , ['comments' => $post->comments->where('reply_of', 0)])
                        <!-- END: Comment -->
                    </div>
                    <!-- END: Comments -->

                    <!-- START: Similar Posts -->
                    <div class="nk-gap-2"></div>
                    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">مقالات</span> مشابه</span></h3>
                    <div class="nk-gap"></div>
                    <div class="mk-blog-grid">
                        <div class="row">
                            @foreach($related as $post)
                                @include('home.sections.post', ['post' => $post])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copyShortLink() {
            const text = document.getElementById('shortLinkText').innerText;

            // ساخت یک input موقت برای کپی
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            // نمایش بازخورد
            const feedback = document.getElementById('copyFeedback');
            feedback.classList.remove('d-none');
            setTimeout(() => feedback.classList.add('d-none'), 3000);
        }
    </script>
@endsection
