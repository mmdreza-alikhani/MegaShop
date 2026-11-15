@extends('admin.layout.master')

@section('title', 'پلتفرم‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>پلتفرم‌ها</h3>
                    <p>{{ number_format($platforms->total()) }} پلتفرم ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#createPlatformModal">
                        <i class="fas fa-plus mr-1"></i> پلتفرم جدید
                    </button>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-gamepad"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل پلتفرم‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">پلتفرم‌های ثبت‌شده</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل انتخاب در محصول</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-link"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">دارای محصول</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['with_products']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک محصول</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['empty'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-unlink"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">بدون محصول</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['empty']) }}</strong>
                        <span class="mk-kpi-hint">نیازمند بررسی</span>
                    </div>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست پلتفرم‌ها</h4>
                        <p>صفحه {{ $platforms->currentPage() }} از {{ $platforms->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.platforms.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($platforms->isEmpty())
                    <p class="mk-adm-empty">پلتفرمی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>لوگو</th>
                                <th>عنوان</th>
                                <th>شناسه</th>
                                <th>محصولات</th>
                                <th>وضعیت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($platforms as $key => $platform)
                                <tr>
                                    <td class="mk-muted">{{ $platforms->firstItem() + $key }}</td>
                                    <td>
                                        <img class="mk-platform-logo"
                                             src="{{ $platform->image ? Storage::url(config('upload.platform_path', 'platforms/images') . '/' . $platform->image) : asset('assets/images/media-placeholder.svg') }}"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                             alt="{{ $platform->title }}">
                                    </td>
                                    <td><strong class="mk-attr-title">{{ $platform->title }}</strong></td>
                                    <td><span class="mk-role-name">{{ $platform->slug }}</span></td>
                                    <td>
                                        <span class="mk-pill {{ $platform->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($platform->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $platform->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $platform->is_active }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات پلتفرم">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <button type="button" class="dropdown-item"
                                                        data-target="#editPlatformModal-{{ $platform->id }}"
                                                        data-toggle="modal" data-dismiss="modal">
                                                    <i class="fas fa-pen"></i><span>ویرایش</span>
                                                </button>
                                                @if(Route::has('admin.platforms.destroy'))
                                                    <form action="{{ route('admin.platforms.destroy', ['platform' => $platform->id]) }}"
                                                          method="POST" class="m-0" onsubmit="return confirm('این پلتفرم حذف شود؟')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item is-danger">
                                                            <i class="fas fa-trash"></i><span>حذف</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mk-adm-pagination">{{ $platforms->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>

            {{-- مودال ساخت --}}
            <div class="modal fade mk-adm-modal" id="createPlatformModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form method="post" action="{{ route('admin.platforms.store') }}" class="modal-content" enctype="multipart/form-data">
                        @csrf
                        <header class="mk-adm-modal-head">
                            <div><h5>پلتفرم جدید</h5><p>عنوان و لوگو را وارد کنید.</p></div>
                            <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                        </header>
                        <div class="mk-adm-modal-body">
                            @include('admin.layout.errors', ['errors' => $errors->store])
                            <div class="mk-form-grid">
                                <div class="mk-field">
                                    <label for="platform-title">عنوان <em>*</em></label>
                                    <input type="text" name="title" id="platform-title" class="form-control"
                                           value="{{ old('title') }}" required>
                                </div>
                                <div class="mk-field">
                                    <label for="platform-active">وضعیت</label>
                                    <select name="is_active" id="platform-active" class="form-control">
                                        <option value="1" @selected(old('is_active', '1') === '1')>فعال</option>
                                        <option value="0" @selected(old('is_active') === '0')>غیرفعال</option>
                                    </select>
                                </div>
                                <div class="mk-field mk-field-full">
                                    <label for="platform-image">لوگو</label>
                                    <input type="file" name="image" id="platform-image" class="form-control" accept="image/*">
                                    <small>حداکثر ۲ مگابایت، فرمت JPG/PNG/SVG.</small>
                                </div>
                            </div>
                        </div>
                        <footer class="mk-adm-modal-foot">
                            <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                            <button type="submit" class="btn btn-info">ایجاد پلتفرم</button>
                        </footer>
                    </form>
                </div>
            </div>

            {{-- مودال‌های ویرایش --}}
            @foreach($platforms as $platform)
                <div class="modal fade mk-adm-modal" id="editPlatformModal-{{ $platform->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.platforms.update', ['platform' => $platform->id]) }}"
                              class="modal-content" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <header class="mk-adm-modal-head">
                                <div><h5>ویرایش پلتفرم</h5><p>{{ $platform->title }}</p></div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>
                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->update])
                                <div class="mk-form-grid">
                                    <div class="mk-field">
                                        <label for="edit-platform-title-{{ $platform->id }}">عنوان <em>*</em></label>
                                        <input type="text" name="title" id="edit-platform-title-{{ $platform->id }}"
                                               class="form-control" value="{{ old('title', $platform->title) }}" required>
                                    </div>
                                    <div class="mk-field">
                                        <label for="edit-platform-active-{{ $platform->id }}">وضعیت</label>
                                        <select name="is_active" id="edit-platform-active-{{ $platform->id }}" class="form-control">
                                            <option value="1" @selected((string) old('is_active', $platform->getRawOriginal('is_active')) === '1')>فعال</option>
                                            <option value="0" @selected((string) old('is_active', $platform->getRawOriginal('is_active')) === '0')>غیرفعال</option>
                                        </select>
                                    </div>
                                    <div class="mk-field mk-field-full">
                                        <label for="edit-platform-image-{{ $platform->id }}">لوگو جدید</label>
                                        <input type="file" name="image" id="edit-platform-image-{{ $platform->id }}"
                                               class="form-control" accept="image/*">
                                        <small>خالی بگذارید تا لوگوی فعلی بماند.</small>
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