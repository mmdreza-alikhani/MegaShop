@extends('admin.layout.master')

@section('title', 'دسترسی‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>دسترسی‌ها</h3>
                    <p>{{ number_format($permissions->total()) }} مجوز تعریف شده است</p>
                </div>
                <div class="mk-dash-actions">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#createPermissionModal">
                            <i class="fas fa-plus mr-1"></i> دسترسی جدید
                        </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-user-tag mr-1"></i> گروه‌های کاربری
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-key"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل دسترسی‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['permissions']) }}</strong>
                        <span class="mk-kpi-hint">مجوزهای سیستم</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-user-tag"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">گروه‌های کاربری</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['roles']) }}</strong>
                        <span class="mk-kpi-hint">نقش‌های تعریف‌شده</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-link"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در حال استفاده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['used']) }}</strong>
                        <span class="mk-kpi-hint">به حداقل یک نقش متصل</span>
                    </div>
                </div>

                <div class="mk-kpi {{ $stats['unused'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-unlink"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">بدون استفاده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['unused']) }}</strong>
                        <span class="mk-kpi-hint">به هیچ نقشی وصل نیست</span>
                    </div>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست دسترسی‌ها</h4>
                        <p>صفحه {{ $permissions->currentPage() }} از {{ $permissions->lastPage() }}</p>
                    </div>

                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">
                            جستجو: {{ request('q') }}
                            <a href="{{ route('admin.permissions.index') }}" aria-label="حذف جستجو">&times;</a>
                        </span>
                    @endif
                </header>

                @if($permissions->isEmpty())
                    <p class="mk-adm-empty">دسترسی‌ای با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان دسترسی</th>
                                <th>شناسه</th>
                                <th>نقش‌های متصل</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $key => $permission)
                                <tr>
                                    <td class="mk-muted">{{ $permissions->firstItem() + $key }}</td>
                                    <td><strong class="mk-attr-title">{{ $permission->display_name ?: $permission->name }}</strong></td>
                                    <td><span class="mk-role-name">{{ $permission->name }}</span></td>
                                    <td>
                                        <span class="mk-pill {{ ($permission->roles_count ?? 0) > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($permission->roles_count ?? 0) }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($permission->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                            <button type="button" class="mk-adm-icon-link"
                                                    data-target="#editPermissionModal-{{ $permission->id }}"
                                                    data-toggle="modal" aria-label="ویرایش دسترسی">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">
                        {{ $permissions->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </section>

            {{-- مودال ساخت --}}
                <div class="modal fade mk-adm-modal" id="createPermissionModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.permissions.store') }}" class="modal-content">
                            @csrf

                            <header class="mk-adm-modal-head">
                                <div>
                                    <h5>دسترسی جدید</h5>
                                    <p>یک مجوز تازه برای استفاده در نقش‌ها.</p>
                                </div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>

                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->store])

                                <div class="mk-form-grid">
                                    <div class="mk-field">
                                        <label for="permission-name">شناسه <em>*</em></label>
                                        <input type="text" name="name" id="permission-name" class="form-control" dir="ltr"
                                               value="{{ old('name') }}" required placeholder="products-export">
                                        <small>حروف کوچک انگلیسی و خط تیره.</small>
                                    </div>

                                    <div class="mk-field">
                                        <label for="permission-display">عنوان نمایشی <em>*</em></label>
                                        <input type="text" name="display_name" id="permission-display" class="form-control"
                                               value="{{ old('display_name') }}" required placeholder="خروجی محصولات">
                                    </div>
                                </div>
                            </div>

                            <footer class="mk-adm-modal-foot">
                                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                                <button type="submit" class="btn btn-info">ایجاد دسترسی</button>
                            </footer>
                        </form>
                    </div>
                </div>

            {{-- مودال ویرایش --}}
                @foreach($permissions as $permission)
                    <div class="modal fade mk-adm-modal" id="editPermissionModal-{{ $permission->id }}" tabindex="-1"
                         role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="post"
                                  action="{{ route('admin.permissions.update', ['permission' => $permission->id]) }}"
                                  class="modal-content">
                                @csrf
                                @method('PATCH')

                                <header class="mk-adm-modal-head">
                                    <div>
                                        <h5>ویرایش دسترسی</h5>
                                        <p>{{ $permission->display_name ?: $permission->name }}</p>
                                    </div>
                                    <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                                </header>

                                <div class="mk-adm-modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->update])

                                    <div class="mk-form-grid">
                                        <div class="mk-field">
                                            <label for="edit-permission-name-{{ $permission->id }}">شناسه <em>*</em></label>
                                            <input type="text" name="name" id="edit-permission-name-{{ $permission->id }}"
                                                   class="form-control" dir="ltr" value="{{ old('name', $permission->name) }}" required>
                                        </div>

                                        <div class="mk-field">
                                            <label for="edit-permission-display-{{ $permission->id }}">عنوان نمایشی <em>*</em></label>
                                            <input type="text" name="display_name" id="edit-permission-display-{{ $permission->id }}"
                                                   class="form-control" value="{{ old('display_name', $permission->display_name) }}" required>
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
