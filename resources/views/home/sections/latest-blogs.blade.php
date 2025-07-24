<h3 class="nk-decorated-h-2"><span><span class="text-main-1">آخرین</span> پست ها</span></h3>
<div class="nk-gap"></div>
<div class="nk-blog-grid">
    <div class="row">
        @foreach($articles as $article)
            <div class="col-md-6">
                <!-- START: Post -->
                <div class="nk-blog-post">
                    <a href="{{ route('home.posts.show', ['article' => $article->slug]) }}" class="nk-post-img">
                        <img style="height: 350px" src="{{ env('ARTICLE_IMAGE_UPLOAD_PATH') . '/' . $article->primary_image }}" alt="{{ $article->name }}-image">
                        <span class="nk-post-comments-count">{{ $article->comments->count() }}</span>
                    </a>
                    <div class="nk-gap"></div>
                    <h2 class="nk-post-title h4 text-right" style="direction: rtl"><a href="{{ route('home.posts.show', ['article' => $article->slug]) }}">{{ $article->title }}</a></h2>
                    <div class="nk-post-by text-right">
                        نوشته:
                        <span class="text-left" style="direction: ltr">
                            {{ '(' . $article->updated_at->diffForHumans() . ')' }}
                        </span>
                        <a href="#">{{ $article->author->username }}</a>
                        <img src="{{ Str::contains($article->author->avatar, 'https://') ? $article->author->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $article->author->avatar }}" alt="{{ $article->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                    </div>
                    <div class="nk-gap"></div>
                    <div class="nk-post-text text-right">
                        <p style="direction: rtl">{{ substr(strip_tags($article->text), 0 , 250) . '...' }}</p>
                    </div>
                    <div class="nk-gap"></div>
                    <a href="{{ route('home.posts.show', ['article' => $article->slug]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ادامه مطلب</a>
                </div>
                <!-- END: Post -->
            </div>
        @endforeach
    </div>
</div>
