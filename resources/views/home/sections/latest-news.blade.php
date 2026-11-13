@php($featuredNews = $news->first())
<div class="nk-gap-2"></div>
<h3 class="nk-decorated-h-2"><span><span class="text-main-1">آخرین</span> اخبار</span></h3>
<div class="nk-gap"></div>

<div class="nk-news-box">
    @if($featuredNews)
        <div class="nk-news-box-each-info">
            <div class="nano">
                <div class="nano-content">
                    <div class="nk-news-box-item-image">
                        <img src="{{ Storage::url(config('upload.post_path') . '/' . $featuredNews->image) }}"
                             alt="{{ $featuredNews->title }}">
                        <span class="nk-news-box-item-categories">
                            <span class="bg-main-4">{{ $featuredNews->author?->username ?? 'مگاشاپ' }}</span>
                        </span>
                    </div>
                    <h3 class="nk-news-box-item-title text-right">{{ $featuredNews->title }}</h3>
                    <div class="nk-news-box-item-text text-right">
                        <p style="direction: rtl">{{ Str::limit(strip_tags($featuredNews->text), 250) }}</p>
                    </div>
                    <a href="{{ route('home.posts.show', ['post' => $featuredNews->slug]) }}"
                       class="nk-news-box-item-more">ادامه مطلب</a>
                    <div class="nk-news-box-item-date" style="direction: rtl">
                        <span class="fa fa-calendar"></span>
                        {{ verta($featuredNews->updated_at)->format('%d %B, %Y') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="nk-news-box-list">
        <div class="nano">
            <div class="nano-content">
                @foreach($news as $item)
                    <div class="nk-news-box-item nk-news-box-item-active">
                        <div class="nk-news-box-item-img">
                            <img src="{{ Storage::url(config('upload.post_path') . '/' . $item->image) }}"
                                 alt="{{ $item->title }}">
                        </div>
                        <img src="{{ Storage::url(config('upload.post_path') . '/' . $item->image) }}"
                             alt="{{ $item->title }}" class="nk-news-box-item-full-img">
                        <h3 class="nk-news-box-item-title text-right">{{ $item->title }}</h3>

                        <span class="nk-news-box-item-categories">
                            <span class="bg-main-4">نویسنده: {{ $item->author?->username ?? 'مگاشاپ' }}</span>
                        </span>

                        <div class="nk-news-box-item-text text-right">
                            <p style="direction: rtl">{{ Str::limit(strip_tags($item->text), 250) }}</p>
                        </div>

                        <a href="{{ route('home.posts.show', ['post' => $item->slug]) }}"
                           class="nk-news-box-item-url">ادامه مطلب</a>
                        <div class="nk-news-box-item-date" style="direction: rtl">
                            {{ $item->updated_at?->diffForHumans() }}
                            <span class="fa fa-calendar mx-2"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="nk-gap-2"></div>
