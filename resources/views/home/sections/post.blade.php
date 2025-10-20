<div class="col-md-6">
    <div class="nk-blog-post">
        <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="nk-post-img">
            <img style="height: 350px" src="{{ Storage::url(config('upload.user_avatar_path') . '/') . $post->image }}" alt="{{ $post->title }}-image">
            <span class="nk-post-comments-count">{{ $post->comments->count() }}</span>
        </a>
        <div class="nk-gap"></div>
        <h2 class="nk-post-title h4 text-right" style="direction: rtl"><a href="{{ route('home.posts.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></h2>
        <div class="nk-post-by text-right">
            نوشته:
            <span class="text-left" style="direction: ltr">
                            {{ '(' . $post->updated_at->diffForHumans() . ')' }}
                        </span>
            <a href="#">{{ $post->author->username }}</a>
            <img src="{{ Storage::url(config('upload.user_avatar_path') . '/') . $post->author->avatar }}" alt="{{ $post->author->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
        </div>
        <div class="nk-gap"></div>
        <div class="nk-post-text text-right">
            <p style="direction: rtl">{{ substr(strip_tags($post->text), 0 , 250) . '...' }}</p>
        </div>
        <div class="nk-gap"></div>
        <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">ادامه مطلب</a>
    </div>
</div>
