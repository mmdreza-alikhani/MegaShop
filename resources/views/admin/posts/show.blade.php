@extends('admin.layout.master')

@section('title', 'نمایش پست')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>نمایش پست</h3>
                    <p>{{ Str::limit($post->title, 80) }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('home.posts.show', ['post' => $post->slug]) }}" target="_blank" rel="noopener"
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> در سایت
                    </a>
                    @can('posts-edit')
                        <a href="{{ route('admin.posts.edit', ['post' => $post->id]) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-pen mr-1"></i> ویرایش
                        </a>
                    @endcan
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <section class="mk-product-hero">
                <div class="mk-product-media">
                    <div class="mk-product-cover">
                        <img src="{{ Storage::url(config('upload.post_path') . '/' . $post->image) }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                             alt="{{ $post->title }}">
                        <span class="mk-chip {{ $post->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }} mk-product-cover-badge">
                            {{ $post->is_active_label }}
                        </span>
                    </div>
                </div>

                <div class="mk-product-info">
                    <div class="mk-product-badges">
                        <span class="mk-chip">{{ $post->type === 'news' ? 'خبر' : 'مقاله' }}</span>
                        <span class="mk-chip">{{ $post->author?->username ? '@' . $post->author->username : 'بدون نویسنده' }}</span>
                        <span class="mk-chip">{{ number_format($post->comments->count()) }} نظر</span>
                    </div>

                    <h2 class="mk-product-title">{{ $post->title }}</h2>

                    <ul class="mk-product-meta">
                        <li>
                            <span>لینک کوتاه</span>
                            <strong dir="ltr">{{ $post->shortLink?->code ? url('/q/' . $post->shortLink->code) : '—' }}</strong>
                        </li>
                        <li>
                            <span>تاریخ ایجاد</span>
                            <strong>{{ verta($post->created_at)->format('%d %B %Y') }}</strong>
                        </li>
                        <li>
                            <span>آخرین ویرایش</span>
                            <strong>{{ verta($post->updated_at)->format('%d %B %Y') }}</strong>
                        </li>
                    </ul>

                    <div class="mk-tags mt-2">
                        @forelse($post->tags as $tag)
                            <span class="mk-chip">{{ $tag->title }}</span>
                        @empty
                            <span class="mk-muted">برچسبی ثبت نشده است.</span>
                        @endforelse
                    </div>
                </div>
            </section>

            <div class="mk-product-grid">
                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>متن پست</h4>
                            <p>همان‌طور که در سایت نمایش داده می‌شود</p>
                        </div>
                    </header>

                    <div class="mk-rich-text">
                        {!! $post->text !!}
                    </div>
                </section>

                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>سوالات متداول</h4>
                            <p>{{ number_format($post->faqs->count()) }} سوال ثبت شده است</p>
                        </div>
                    </header>

                    @forelse($post->faqs as $faq)
                        <details class="mk-faq-acc" @if($loop->first) open @endif>
                            <summary>
                                <span class="mk-faq-num">{{ $loop->iteration }}</span>
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <p>{{ $faq->answer }}</p>
                        </details>
                    @empty
                        <p class="mk-adm-empty">سوال متداولی ثبت نشده است.</p>
                    @endforelse
                </section>
            </div>
        </div>
    </main>
@endsection
