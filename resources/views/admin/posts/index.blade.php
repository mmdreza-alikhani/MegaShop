@extends('admin.layout.master')

@section('title', 'پست‌ها')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'type' => request('type'),
            'status' => request('status'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>پست‌ها</h3>
                    <p>{{ number_format($posts->total()) }} پست و خبر ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    @can('posts-create')
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-plus mr-1"></i> پست جدید
                        </a>
                    @endcan
                    <a href="{{ route('home.posts.index') }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> مجله
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-newspaper"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل پست‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">محتوای مجله</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-eye"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">منتشرشده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش در سایت</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-file-alt"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">مقاله</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['articles']) }}</strong>
                        <span class="mk-kpi-hint">محتوای آموزشی</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-bolt"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">خبر</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['news']) }}</strong>
                        <span class="mk-kpi-hint">اخبار و رویدادها</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.posts.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>نوع</span>
                    <select name="type" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="article" @selected(request('type') === 'article')>مقاله</option>
                        <option value="news" @selected(request('type') === 'news')>خبر</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="1" @selected(request('status') === '1')>فعال</option>
                        <option value="0" @selected(request('status') === '0')>غیرفعال</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.posts.index', array_diff_key($query, ['q' => ''])) }}">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.posts.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست پست‌ها</h4>
                        <p>صفحه {{ $posts->currentPage() }} از {{ $posts->lastPage() }}</p>
                    </div>
                </header>

                @if($posts->isEmpty())
                    <p class="mk-adm-empty">پستی با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>تصویر</th>
                                <th>عنوان</th>
                                <th>نوع</th>
                                <th>نظرات</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $key => $post)
                                <tr>
                                    <td class="mk-muted">{{ $posts->firstItem() + $key }}</td>
                                    <td>
                                        <img class="mk-banner-thumb"
                                             src="{{ Storage::url(config('upload.post_path') . '/' . $post->image) }}"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                                             alt="{{ $post->title }}">
                                    </td>
                                    <td>
                                        <strong class="mk-attr-title">{{ Str::limit($post->title, 60) }}</strong>
                                    </td>
                                    <td>
                                        <span class="mk-chip">{{ $post->type === 'news' ? 'خبر' : 'مقاله' }}</span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $post->comments_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($post->comments_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $post->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $post->is_active_label }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($post->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات پست">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.posts.show', ['post' => $post->id]) }}">
                                                    <i class="far fa-eye"></i><span>نمایش</span>
                                                </a>
                                                @can('posts-edit')
                                                    <a class="dropdown-item" href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">
                                                        <i class="fas fa-pen"></i><span>ویرایش</span>
                                                    </a>
                                                @endcan
                                                <a class="dropdown-item" href="{{ route('home.posts.show', ['post' => $post->slug]) }}"
                                                   target="_blank" rel="noopener">
                                                    <i class="fas fa-external-link-alt"></i><span>در سایت</span>
                                                </a>
                                                @can('posts-delete')
                                                    <form action="{{ route('admin.posts.destroy', ['post' => $post->id]) }}"
                                                          method="POST" class="m-0"
                                                          onsubmit="return confirm('این پست حذف شود؟')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item is-danger">
                                                            <i class="fas fa-trash"></i><span>حذف</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">{{ $posts->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
