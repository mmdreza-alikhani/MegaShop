<div class="nk-gap-2"></div>
<h3 class="nk-decorated-h-2"><span><span class="text-main-1">آخرین</span> اخبار</span></h3>
<div class="nk-gap"></div>

<div class="nk-news-box">
    <div class="nk-news-box-each-info">
        <div class="nano">
            <div class="nano-content">
                <!-- There will be inserted info about selected news-->
                <div class="nk-news-box-item-image">
                    <img src="assets/images/post-1.jpg" alt="">
                    <span class="nk-news-box-item-categories">
                    <span class="bg-main-4">MMO</span>
                </span>
                </div>
                <h3 class="nk-news-box-item-title text-right">Smell magic in the air. Or maybe barbecue</h3>
                <div class="nk-news-box-item-text text-right">
                    <p>With what mingled joy and sorrow do I take up the pen to write to my dearest friend! Oh, what a change between to-day and yesterday! Now I am friendless and alone...</p>
                </div>
                <a href="blog-article.html" class="nk-news-box-item-more">ادامه مطلب</a>
                <div class="nk-news-box-item-date" style="direction: rtl">
                    <span class="fa fa-calendar"></span> Sep 18, 2018
                </div>
            </div>
        </div>
    </div>
    <div class="nk-news-box-list">
        <div class="nano">
            <div class="nano-content">
                @foreach($news as $item)
                    <div class="nk-news-box-item nk-news-box-item-active">
                        <div class="nk-news-box-item-img">
                            <img src="{{ Storage::url(config('upload.post_path') . '/') . $item->image }}" alt="{{ $item->title }}-image">
                        </div>
                        <img src="{{ Storage::url(config('upload.post_path') . '/') . $item->image }}" alt="{{ $item->title }}-image" class="nk-news-box-item-full-img">
                        <h3 class="nk-news-box-item-title text-right">{{ $item->title }}</h3>

                        <span class="nk-news-box-item-categories">
                            <span class="bg-main-4">{{ $item->author->username }}  :نویسنده </span>
                        </span>

                        <div class="nk-news-box-item-text text-right">
                            <p style="direction: rtl">{{ substr(strip_tags($item->text), 0 , 250) . '...' }}</p>
                        </div>

                        <a href="{{ route('home.posts.show', ['post' => $item->slug]) }}" class="nk-news-box-item-url">ادامه مطلب</a>
                        <div class="nk-news-box-item-date" style="direction: rtl">{{ $item->updated_at->diffForHumans() }}<span class="fa fa-calendar mx-2"></span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="nk-gap-2"></div>

