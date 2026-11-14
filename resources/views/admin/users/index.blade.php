@extends('admin.layout.master')

@section('title', 'کاربران')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'role' => request('role'),
            'status' => request('status'),
            'sort' => request('sort'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>کاربران</h3>
                    <p>{{ number_format($users->total()) }} کاربر در مجموع</p>
                </div>
                <div class="mk-dash-actions">
                    @can('users-create')
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#createUserModal">
                            <i class="fas fa-user-plus mr-1"></i> کاربر جدید
                        </button>
                    @endcan
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-shield-alt mr-1"></i> گروه‌های کاربری
                    </a>
                </div>
            </div>

            {{-- آمار --}}
            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-users"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل کاربران</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">ثبت‌نام‌شده در فروشگاه</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-check"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">{{ $stats['total'] > 0 ? round(($stats['active'] / max(1, $stats['total'])) * 100) : 0 }}٪ از کاربران</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-shield"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">دارای نقش</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['staff']) }}</strong>
                        <span class="mk-kpi-hint">مدیر یا اپراتور</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-plus"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">جدید این ماه</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['new']) }}</strong>
                        <span class="mk-kpi-hint">از ابتدای ماه جاری</span>
                    </div>
                </div>
            </div>

            {{-- فیلترها --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>نقش</span>
                    <select name="role" onchange="this.form.submit()">
                        <option value="">همه نقش‌ها</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                                {{ $role->display_name ?: $role->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="active" @selected(request('status') === 'active')>فعال</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>غیرفعال</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>ترتیب</span>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">جدیدترین</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>قدیمی‌ترین</option>
                        <option value="orders" @selected(request('sort') === 'orders')>بیشترین سفارش</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.users.index', array_diff_key($query, ['q' => ''])) }}" aria-label="حذف جستجو">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.users.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            {{-- جدول --}}
            <section class="mk-adm-panel">
                <div class="mk-adm-panel-head">
                    <div>
                        <h4>لیست کاربران</h4>
                        <p>صفحه {{ $users->currentPage() }} از {{ $users->lastPage() }}</p>
                    </div>
                </div>

                @if($users->isEmpty())
                    <p class="mk-adm-empty">کاربری با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0 mk-users-table">
                            <thead>
                            <tr>
                                <th>کاربر</th>
                                <th>ارتباط</th>
                                <th>نقش</th>
                                <th>سفارش</th>
                                <th>وضعیت</th>
                                <th>عضویت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                @php($isActive = (bool) $user->getRawOriginal('is_active'))
                                <tr>
                                    <td>
                                        <div class="mk-user-cell">
                                            <img src="{{ Storage::url(config('upload.user_avatar_path').'/'.$user->avatar) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                                                 alt="{{ $user->username }}">
                                            <span>
                                                <strong>{{ trim($user->first_name.' '.$user->last_name) ?: $user->username }}</strong>
                                                <small>@ {{ $user->username }}</small>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="mk-user-contact">{{ $user->email }}</span>
                                        <small class="mk-user-phone">{{ $user->formatted_phone ?: '—' }}</small>
                                    </td>
                                    <td>
                                        @forelse($user->roles as $role)
                                            <span class="mk-pill is-role">{{ $role->display_name ?: $role->name }}</span>
                                        @empty
                                            <span class="mk-pill is-wait">بدون نقش</span>
                                        @endforelse
                                    </td>
                                    <td>{{ number_format($user->orders_count ?? 0) }}</td>
                                    <td>
                                        <span class="mk-pill {{ $isActive ? 'is-ok' : 'is-bad' }}">
                                            {{ $isActive ? 'فعال' : 'غیرفعال' }}
                                        </span>
                                    </td>
                                    <td>{{ verta($user->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات کاربر">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>

                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                @can('users-index')
                                                    <button type="button" class="dropdown-item"
                                                            data-target="#showUserModal-{{ $user->id }}"
                                                            data-toggle="modal" data-dismiss="modal">
                                                        <i class="far fa-eye"></i>
                                                        <span>نمایش پروفایل</span>
                                                    </button>
                                                @endcan
                                                @can('users-edit')
                                                    <button type="button" class="dropdown-item"
                                                            data-target="#editUserModal-{{ $user->id }}"
                                                            data-toggle="modal" data-dismiss="modal">
                                                        <i class="fas fa-pen"></i>
                                                        <span>ویرایش</span>
                                                    </button>
                                                @endcan
                                                <a class="dropdown-item" href="{{ route('admin.orders.index', ['q' => $user->username]) }}">
                                                    <i class="fas fa-receipt"></i>
                                                    <span>سفارش‌های کاربر</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </section>

            {{-- مودال‌های هر کاربر --}}
            @foreach($users as $user)
                @can('users-index')
                    @include('admin.users.partials.show-modal', ['user' => $user])
                @endcan
                @can('users-edit')
                    @include('admin.users.partials.edit-modal', ['user' => $user, 'roles' => $roles])
                @endcan
            @endforeach

            @can('users-create')
                @include('admin.users.partials.create-modal', ['roles' => $roles])
            @endcan
        </div>
    </main>
@endsection
