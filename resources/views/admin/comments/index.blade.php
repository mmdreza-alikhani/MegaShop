@extends('admin.layout.master')

@section('title', 'نظرات')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'status' => request('status'),
            'type' => request('type'),
        ], fn ($value) => $value !== null && $value !== '');

        $statusLabels = [1 => ['در انتظار', 'is-wait'], 2 => ['رد شده', 'is-bad'], 3 => ['منتشر شده', 'is-ok']];
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>نظرات</h3>
                    <p>{{ number_format($comments->total()) }} نظر در مجموع</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.comments.index', ['status' => 1]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-clock mr-1"></i> در انتظار بررسی
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-box mr-1"></i> محصولات
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-comments"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل نظرات</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">محصولات و پست‌ها</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['pending'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-hourglass-half"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در انتظار</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['pending']) }}</strong>
                        <span class="mk-kpi-hint">نیازمند بررسی</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">منتشر شده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['approved']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش در سایت</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-ban"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">رد شده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['rejected']) }}</strong>
                        <span class="mk-kpi-hint">تایید نشده</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.comments.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="1" @selected(request('status') === '1')>در انتظار</option>
                        <option value="3" @selected(request('status') === '3')>منتشر شده</option>
                        <option value="2" @selected(request('status') === '2')>رد شده</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>بخش</span>
                    <select name="type" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="product" @selected(request('type') === 'product')>محصولات</option>
                        <option value="post" @selected(request('type') === 'post')>پست‌ها</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.comments.index', array_diff_key($query, ['q' => ''])) }}">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.comments.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست نظرات</h4>
                        <p>صفحه {{ $comments->currentPage() }} از {{ $comments->lastPage() }}</p>
                    </div>
                </header>

                @if($comments->isEmpty())
                    <p class="mk-adm-empty">نظری با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>نویسنده</th>
                                <th>متن نظر</th>
                                <th>مورد نظر</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                @php
                                    $status = (int) $comment->getRawOriginal('status');
                                    [$statusLabel, $statusClass] = $statusLabels[$status] ?? ['نامشخص', 'is-wait'];
                                    $user = $comment->user;
                                    $target = $comment->commentable;
                                    $isPost = $comment->commentable_type === \App\Models\Post::class;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="mk-user-cell">
                                            <img src="{{ $user?->avatar ? Storage::url(config('upload.user_avatar_path') . '/' . $user->avatar) : asset('assets/images/avatar-placeholder.svg') }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                                                 alt="{{ $user?->username ?? 'کاربر' }}">
                                            <span>
                                                <strong>{{ $user?->username ? '@' . $user->username : 'کاربر حذف‌شده' }}</strong>
                                                <small>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: '—' }}</small>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="mk-comment-text">
                                        {{ Str::limit($comment->text, 90) }}
                                        @if($comment->reply_of)
                                            <small class="mk-muted">در پاسخ به نظر #{{ $comment->reply_of }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($target)
                                            <span class="mk-chip">{{ $isPost ? 'پست' : 'محصول' }}</span>
                                            <a class="mk-adm-link" title="{{ $target->title }}"
                                               href="{{ $isPost ? route('admin.posts.show', ['post' => $target->id]) : route('admin.products.show', ['product' => $target->slug]) }}">
                                                {{ Str::limit($target->title, 42) }}
                                            </a>
                                        @else
                                            <span class="mk-muted">حذف شده</span>
                                        @endif
                                    </td>
                                    <td><span class="mk-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td class="mk-muted">{{ verta($comment->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات نظر">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                @if($status !== 3)
                                                    <form action="{{ route('admin.comments.approve', ['comment' => $comment->id]) }}"
                                                          method="POST" class="m-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check"></i><span>تایید و انتشار</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($status !== 2)
                                                    <form action="{{ route('admin.comments.reject', ['comment' => $comment->id]) }}"
                                                          method="POST" class="m-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-ban"></i><span>رد کردن</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.comments.destroy', ['comment' => $comment->id]) }}"
                                                      method="POST" class="m-0"
                                                      onsubmit="return confirm('این نظر و پاسخ‌هایش حذف شود؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item is-danger">
                                                        <i class="fas fa-trash"></i><span>حذف</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">{{ $comments->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
