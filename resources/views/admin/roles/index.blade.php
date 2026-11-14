@extends('admin.layout.master')

@section('title', 'گروه‌های کاربری')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>گروه‌های کاربری</h3>
                    <p>{{ number_format($roles->total()) }} نقش برای تعیین سطح دسترسی</p>
                </div>
                <div class="mk-dash-actions">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#createRoleModal">
                            <i class="fas fa-plus mr-1"></i> نقش جدید
                        </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-key mr-1"></i> دسترسی‌ها
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-tag"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">نقش‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['roles']) }}</strong>
                        <span class="mk-kpi-hint">گروه‌های کاربری</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-key"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">دسترسی‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['permissions']) }}</strong>
                        <span class="mk-kpi-hint">مجوزهای تعریف‌شده</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-shield"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کاربران دارای نقش</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['staff']) }}</strong>
                        <span class="mk-kpi-hint">مدیر و اپراتور</span>
                    </div>
                </div>

                <div class="mk-kpi {{ $stats['empty'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-unlink"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">نقش بدون دسترسی</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['empty']) }}</strong>
                        <span class="mk-kpi-hint">نیازمند بازبینی</span>
                    </div>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست نقش‌ها</h4>
                        <p>صفحه {{ $roles->currentPage() }} از {{ $roles->lastPage() }}</p>
                    </div>

                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">
                            جستجو: {{ request('q') }}
                            <a href="{{ route('admin.roles.index') }}" aria-label="حذف جستجو">&times;</a>
                        </span>
                    @endif
                </header>

                @if($roles->isEmpty())
                    <p class="mk-adm-empty">نقشی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان نقش</th>
                                <th>شناسه</th>
                                <th>کاربران</th>
                                <th>دسترسی‌ها</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $key => $role)
                                <tr>
                                    <td class="mk-muted">{{ $roles->firstItem() + $key }}</td>
                                    <td>
                                        <strong class="mk-attr-title">{{ $role->display_name ?: $role->name }}</strong>
                                    </td>
                                    <td><span class="mk-role-name">{{ $role->name }}</span></td>
                                    <td>
                                        <span class="mk-pill {{ ($role->users_count ?? 0) > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($role->users_count ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="mk-perm-chips">
                                            @forelse($role->permissions->take(3) as $permission)
                                                <span class="mk-perm-chip">{{ $permission->display_name ?: $permission->name }}</span>
                                            @empty
                                                <span class="mk-pill is-wait">بدون دسترسی</span>
                                            @endforelse

                                            @if($role->permissions->count() > 3)
                                                <span class="mk-perm-more">+{{ $role->permissions->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="mk-muted">{{ verta($role->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                            <button type="button" class="mk-adm-icon-link"
                                                    data-target="#editRoleModal-{{ $role->id }}" data-toggle="modal"
                                                    aria-label="ویرایش نقش">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">
                        {{ $roles->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </section>

            {{-- مودال ساخت --}}
                <div class="modal fade mk-adm-modal" id="createRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.roles.store') }}" class="modal-content">
                            @csrf

                            <header class="mk-adm-modal-head">
                                <div>
                                    <h5>نقش جدید</h5>
                                    <p>نام نمایشی و شناسه یکتای نقش را وارد کنید.</p>
                                </div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>

                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->store])

                                <div class="mk-form-grid">
                                    <div class="mk-field">
                                        <label for="role-name">شناسه <em>*</em></label>
                                        <input type="text" name="name" id="role-name" class="form-control" dir="ltr"
                                               value="{{ old('name') }}" required placeholder="content-manager">
                                        <small>حروف کوچک انگلیسی و خط تیره.</small>
                                    </div>

                                    <div class="mk-field">
                                        <label for="role-display">نام نمایشی <em>*</em></label>
                                        <input type="text" name="display_name" id="role-display" class="form-control"
                                               value="{{ old('display_name') }}" required placeholder="مدیر محتوا">
                                    </div>
                                </div>
                            </div>

                            <footer class="mk-adm-modal-foot">
                                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                                <button type="submit" class="btn btn-info">ایجاد نقش</button>
                            </footer>
                        </form>
                    </div>
                </div>

            {{-- مودال ویرایش --}}
                @foreach($roles as $role)
                    <div class="modal fade mk-adm-modal" id="editRoleModal-{{ $role->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="post" action="{{ route('admin.roles.update', ['role' => $role->id]) }}" class="modal-content">
                                @csrf
                                @method('PATCH')

                                <header class="mk-adm-modal-head">
                                    <div>
                                        <h5>ویرایش نقش</h5>
                                        <p>{{ $role->display_name ?: $role->name }}</p>
                                    </div>
                                    <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                                </header>

                                <div class="mk-adm-modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->update])

                                    <div class="mk-form-grid">
                                        <div class="mk-field">
                                            <label for="edit-role-name-{{ $role->id }}">شناسه <em>*</em></label>
                                            <input type="text" name="name" id="edit-role-name-{{ $role->id }}"
                                                   class="form-control" dir="ltr" value="{{ old('name', $role->name) }}" required>
                                        </div>

                                        <div class="mk-field">
                                            <label for="edit-role-display-{{ $role->id }}">نام نمایشی <em>*</em></label>
                                            <input type="text" name="display_name" id="edit-role-display-{{ $role->id }}"
                                                   class="form-control" value="{{ old('display_name', $role->display_name) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <footer class="mk-adm-modal-foot">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-info">ذخیره تغییرات</button>
                                </footer>
                            </form>
                        </div>
                    </div>
                @endforeach
        </div>
    </main>
@endsection
