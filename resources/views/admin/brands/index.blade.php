@extends('admin.layout.master')

@section('title', 'برندها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>برندها</h3>
                    <p>{{ number_format($brands->total()) }} برند ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> برند جدید
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-box-open mr-1"></i> محصولات
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-tags"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل برندها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">ثبت‌شده در فروشگاه</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش در سایت</span>
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
                        <h4>لیست برندها</h4>
                        <p>صفحه {{ $brands->currentPage() }} از {{ $brands->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.brands.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($brands->isEmpty())
                    <p class="mk-adm-empty">برندی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان برند</th>
                                <th>شناسه</th>
                                <th>محصولات</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $key => $brand)
                                <tr>
                                    <td class="mk-muted">{{ $brands->firstItem() + $key }}</td>
                                    <td><strong class="mk-attr-title">{{ $brand->title }}</strong></td>
                                    <td><span class="mk-role-name">{{ $brand->slug }}</span></td>
                                    <td>
                                        <span class="mk-pill {{ $brand->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($brand->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $brand->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $brand->is_active }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($brand->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات برند">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.brands.edit', ['brand' => $brand->id]) }}">
                                                    <i class="fas fa-pen"></i><span>ویرایش</span>
                                                </a>
                                                @if(Route::has('admin.brands.destroy'))
                                                    <form action="{{ route('admin.brands.destroy', ['brand' => $brand->id]) }}"
                                                          method="POST" class="m-0" onsubmit="return confirm('این برند حذف شود؟')">
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
                    <div class="mk-adm-pagination">{{ $brands->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>

            {{-- مودال ساخت --}}
            <div class="modal fade mk-adm-modal" id="createBrandModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form method="post" action="{{ route('admin.brands.store') }}" class="modal-content">
                        @csrf
                        <header class="mk-adm-modal-head">
                            <div><h5>برند جدید</h5><p>عنوان برند را وارد کنید.</p></div>
                            <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                        </header>
                        <div class="mk-adm-modal-body">
                            @include('admin.layout.errors', ['errors' => $errors->store])
                            <div class="mk-form-grid">
                                <div class="mk-field">
                                    <label for="brand-title">عنوان <em>*</em></label>
                                    <input type="text" name="title" id="brand-title" class="form-control"
                                           value="{{ old('title') }}" required>
                                </div>
                                <div class="mk-field">
                                    <label for="brand-active">وضعیت</label>
                                    <select name="is_active" id="brand-active" class="form-control">
                                        <option value="1" @selected(old('is_active', '1') === '1')>فعال</option>
                                        <option value="0" @selected(old('is_active') === '0')>غیرفعال</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <footer class="mk-adm-modal-foot">
                            <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                            <button type="submit" class="btn btn-info">ایجاد برند</button>
                        </footer>
                    </form>
                </div>
            </div>

            {{-- مودال‌های ویرایش --}}
            @foreach($brands as $brand)
                <div class="modal fade mk-adm-modal" id="editBrandModal-{{ $brand->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.brands.update', ['brand' => $brand->id]) }}" class="modal-content">
                            @csrf @method('PATCH')
                            <header class="mk-adm-modal-head">
                                <div><h5>ویرایش برند</h5><p>{{ $brand->title }}</p></div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>
                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->update])
                                <div class="mk-form-grid">
                                    <div class="mk-field">
                                        <label for="edit-brand-title-{{ $brand->id }}">عنوان <em>*</em></label>
                                        <input type="text" name="title" id="edit-brand-title-{{ $brand->id }}"
                                               class="form-control" value="{{ old('title', $brand->title) }}" required>
                                    </div>
                                    <div class="mk-field">
                                        <label for="edit-brand-active-{{ $brand->id }}">وضعیت</label>
                                        <select name="is_active" id="edit-brand-active-{{ $brand->id }}" class="form-control">
                                            <option value="1" @selected((string) old('is_active', $brand->getRawOriginal('is_active')) === '1')>فعال</option>
                                            <option value="0" @selected((string) old('is_active', $brand->getRawOriginal('is_active')) === '0')>غیرفعال</option>
                                        </select>
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