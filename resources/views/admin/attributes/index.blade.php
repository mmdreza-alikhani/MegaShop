@extends('admin.layout.master')

@section('title', 'ویژگی‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویژگی‌ها</h3>
                    <p>{{ number_format($attributes->total()) }} ویژگی ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    @can('attributes-create')
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#createAttributeModal">
                            <i class="fas fa-plus mr-1"></i> ویژگی جدید
                        </button>
                    @endcan
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-layer-group mr-1"></i> دسته‌بندی‌ها
                    </a>
                </div>
            </div>

            {{-- آمار --}}
            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-star"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل ویژگی‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">در کاتالوگ فروشگاه</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-filter"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">ویژگی فیلتر</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['filter']) }}</strong>
                        <span class="mk-kpi-hint">قابل استفاده در فیلترها</span>
                    </div>
                </div>

                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-code-branch"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">ویژگی تنوع</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['variation']) }}</strong>
                        <span class="mk-kpi-hint">مشخصه تنوع محصول</span>
                    </div>
                </div>

                <div class="mk-kpi {{ $stats['unused'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-unlink"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">بدون مقدار</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['unused']) }}</strong>
                        <span class="mk-kpi-hint">هنوز مقداری ندارد</span>
                    </div>
                </div>
            </div>

            {{-- لیست --}}
            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست ویژگی‌ها</h4>
                        <p>صفحه {{ $attributes->currentPage() }} از {{ $attributes->lastPage() }}</p>
                    </div>

                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">
                            جستجو: {{ request('q') }}
                            <a href="{{ route('admin.attributes.index') }}" aria-label="حذف جستجو">&times;</a>
                        </span>
                    @endif
                </header>

                @if($attributes->isEmpty())
                    <p class="mk-adm-empty">ویژگی‌ای با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0 mk-attrs-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان ویژگی</th>
                                <th>مقادیر فیلتر</th>
                                <th>مقادیر تنوع</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attributes as $key => $attribute)
                                <tr>
                                    <td class="mk-muted">{{ $attributes->firstItem() + $key }}</td>
                                    <td>
                                        <strong class="mk-attr-title">{{ $attribute->title }}</strong>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $attribute->filter_values_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($attribute->filter_values_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $attribute->variation_values_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($attribute->variation_values_count) }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($attribute->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات ویژگی">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>

                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                @can('attributes-edit')
                                                    <button type="button" class="dropdown-item"
                                                            data-target="#editAttributeModal-{{ $attribute->id }}"
                                                            data-toggle="modal" data-dismiss="modal">
                                                        <i class="fas fa-pen"></i>
                                                        <span>ویرایش عنوان</span>
                                                    </button>
                                                @endcan

                                                @can('attributes-delete')
                                                    <form action="{{ route('admin.attributes.destroy', ['attribute' => $attribute->id]) }}"
                                                          method="POST" class="m-0"
                                                          onsubmit="return confirm('این ویژگی حذف شود؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item is-danger">
                                                            <i class="fas fa-trash"></i>
                                                            <span>حذف ویژگی</span>
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

                    <div class="mk-adm-pagination">
                        {{ $attributes->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </section>

            {{-- مودال ساخت --}}
            @can('attributes-create')
                <div class="modal fade mk-adm-modal" id="createAttributeModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.attributes.store') }}" class="modal-content">
                            @csrf

                            <header class="mk-adm-modal-head">
                                <div>
                                    <h5>ویژگی جدید</h5>
                                    <p>عنوان ویژگی را وارد کنید؛ مثل «رنگ» یا «حافظه».</p>
                                </div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>

                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->store])

                                <div class="mk-field">
                                    <label for="attribute-title">نام ویژگی <em>*</em></label>
                                    <input type="text" name="title" id="attribute-title" class="form-control"
                                           value="{{ old('title') }}" required autocomplete="off">
                                </div>
                            </div>

                            <footer class="mk-adm-modal-foot">
                                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                                <button type="submit" class="btn btn-info">ایجاد ویژگی</button>
                            </footer>
                        </form>
                    </div>
                </div>
            @endcan

            {{-- مودال‌های ویرایش --}}
            @can('attributes-edit')
                @foreach($attributes as $attribute)
                    <div class="modal fade mk-adm-modal" id="editAttributeModal-{{ $attribute->id }}" tabindex="-1"
                         role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="post"
                                  action="{{ route('admin.attributes.update', ['attribute' => $attribute->id]) }}"
                                  class="modal-content">
                                @csrf
                                @method('PATCH')

                                <header class="mk-adm-modal-head">
                                    <div>
                                        <h5>ویرایش ویژگی</h5>
                                        <p>{{ $attribute->title }}</p>
                                    </div>
                                    <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                                </header>

                                <div class="mk-adm-modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->update])

                                    <div class="mk-field">
                                        <label for="edit-attribute-title-{{ $attribute->id }}">نام ویژگی <em>*</em></label>
                                        <input type="text" name="title" id="edit-attribute-title-{{ $attribute->id }}"
                                               class="form-control" value="{{ old('title', $attribute->title) }}" required>
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
            @endcan
        </div>
    </main>
@endsection
