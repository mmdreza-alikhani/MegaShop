<h3 class="nk-decorated-h-2"><span><span class="text-main-1">آخرین</span> پست ها</span></h3>
<div class="nk-gap"></div>
<div class="nk-blog-grid">
    <div class="row">
        @foreach($articles as $article)
            @include('home.sections.post', ['post' => $article])
        @endforeach
    </div>
</div>
