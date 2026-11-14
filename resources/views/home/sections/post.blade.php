<div class="col-md-6 col-lg-4">
<article class="mk-post-card">
    <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="mk-post-media">
        <img src="{{ Storage::url(config('upload.post_path') . '/' . $post->image) }}"
             onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
             alt="{{ $post->title }}" loading="lazy">
        @if(($post->comments_count ?? 0) > 0)
            <span class="mk-post-comments">
                <span class="fa fa-comments"></span>
                {{ $post->comments_count }}
            </span>
        @endif
    </a>

    <div class="mk-post-body">
        <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="mk-post-title">
            {{ $post->title }}
        </a>

        <div class="mk-post-meta">
            <img src="{{ Storage::url(config('upload.user_avatar_path') . '/' . ($post->author?->avatar ?? 'avatar.png')) }}"
             onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                 alt="{{ $post->author?->username }}" class="mk-post-avatar" loading="lazy">
            <span>{{ $post->author?->username ?? 'مگاشاپ' }}</span>
            <span class="mk-post-dot">•</span>
            <span>{{ verta($post->updated_at)->format('%d %B %Y') }}</span>
        </div>

        <p class="mk-post-excerpt">{{ Str::limit(strip_tags($post->text), 220) }}</p>

        <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" class="mk-post-more">
            ادامه مطلب
            <span class="fa fa-angle-left"></span>
        </a>
    </div>
</article>
</div>
