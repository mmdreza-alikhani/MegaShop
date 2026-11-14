@php
    $featuredNews = $news->first();
    $restNews = $news->slice(1);
@endphp

<section class="mk-news-section">
    <header class="mk-news-head">
        <h3 class="nk-decorated-h-2"><span><span class="text-main-1">آخرین</span> اخبار</span></h3>
        <a href="{{ route('home.posts.index') }}" class="mk-news-all">
            همه اخبار
            <span class="fa fa-angle-left"></span>
        </a>
    </header>

    @if($featuredNews)
        <div class="mk-news-grid">
            <article class="mk-news-featured">
                <a href="{{ route('home.posts.show', ['post' => $featuredNews->slug]) }}" class="mk-news-media">
                    <img src="{{ Storage::url(config('upload.post_path') . '/' . $featuredNews->image) }}"
                         onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                         alt="{{ $featuredNews->title }}" loading="lazy">

                    <span class="mk-news-chip">خبر ویژه</span>
                </a>

                <div class="mk-news-featured-body">
                    <div class="mk-news-meta">
                        <span class="fa fa-user-o"></span>
                        {{ $featuredNews->author?->username ?? 'مگاشاپ' }}
                        <span class="mk-news-dot"></span>
                        <span class="fa fa-calendar-o"></span>
                        {{ verta($featuredNews->updated_at)->format('%d %B %Y') }}
                    </div>

                    <h4>
                        <a href="{{ route('home.posts.show', ['post' => $featuredNews->slug]) }}">
                            {{ $featuredNews->title }}
                        </a>
                    </h4>

                    <p>{{ Str::limit(strip_tags($featuredNews->text), 140) }}</p>

                    <a href="{{ route('home.posts.show', ['post' => $featuredNews->slug]) }}" class="mk-news-more">
                        ادامه مطلب
                        <span class="fa fa-angle-left"></span>
                    </a>
                </div>
            </article>

            @if($restNews->isNotEmpty())
                <div class="mk-news-list">
                    @foreach($restNews as $item)
                        <article class="mk-news-item">
                            <a href="{{ route('home.posts.show', ['post' => $item->slug]) }}" class="mk-news-thumb">
                                <img src="{{ Storage::url(config('upload.post_path') . '/' . $item->image) }}"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                     alt="{{ $item->title }}" loading="lazy">
                            </a>

                            <div class="mk-news-body">
                                <a href="{{ route('home.posts.show', ['post' => $item->slug]) }}" class="mk-news-title">
                                    {{ $item->title }}
                                </a>

                                <div class="mk-news-meta">
                                    <span class="fa fa-calendar-o"></span>
                                    {{ verta($item->updated_at)->format('%d %B %Y') }}
                                    @if(($item->comments_count ?? 0) > 0)
                                        <span class="mk-news-dot"></span>
                                        <span class="fa fa-comment-o"></span>
                                        {{ $item->comments_count }}
                                    @endif
                                </div>
                            </div>

                            <span class="fa fa-angle-left mk-news-arrow" aria-hidden="true"></span>
                        </article>
                    @endforeach

                    <a href="{{ route('home.posts.index') }}" class="mk-news-more-tile">
                        <span class="fa fa-file-text-o"></span>
                        <strong>همه اخبار مگاشاپ</strong>
                        <small>آرشیو کامل خبرها و مقالات</small>
                        <span class="mk-news-tile-arrow">
                            مشاهده آرشیو
                            <span class="fa fa-angle-left"></span>
                        </span>
                    </a>
                </div>
            @endif
        </div>
    @endif
</section>

<div class="nk-gap-2"></div>
