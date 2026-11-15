@extends('admin.layout.master')

@section('title', 'برچسب‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>برچسب‌ها</h3>
                    <p>{{ number_format($tags->total()) }} برچسب ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    @can('tags-create')
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#createTagModal">
                            <i class="fas fa-plus mr-1"></i> برچسب جدید
                        </button>
                    @endcan
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-box mr-1"></i> محصولات
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-tags"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل برچسب‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">ثبت‌شده در فروشگاه</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-link"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">استفاده‌شده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['used']) }}</strong>
                        <span class="mk-kpi-hint">در محصول یا پست</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-box"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در محصولات</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['products']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک محصول</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-newspaper"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در پست‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['posts']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک پست</span>
                    </div>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست برچسب‌ها</h4>
                        <p>صفحه {{ $tags->currentPage() }} از {{ $tags->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.tags.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($tags->isEmpty())
                    <p class="mk-adm-empty">برچسبی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>محصولات</th>
                                <th>پست‌ها</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tags as $key => $tag)
                                <tr>
                                    <td class="mk-muted">{{ $tags->firstItem() + $key }}</td>
                                    <td><strong class="mk-attr-title">{{ $tag->title }}</strong></td>
                                    <td>
                                        <span class="mk-pill {{ $tag->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($tag->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $tag->posts_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($tag->posts_count) }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($tag->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات برچسب">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                @can('tags-edit')
                                                    <button type="button" class="dropdown-item"
                                                            data-target="#editTagModal-{{ $tag->id }}"
                                                            data-toggle="modal" data-dismiss="modal">
                                                        <i class="fas fa-pen"></i><span>ویرایش</span>
                                                    </button>
                                                @endcan
                                                @can('tags-delete')
                                                    <form action="{{ route('admin.tags.destroy', ['tag' => $tag->id]) }}"
                                                          method="POST" class="m-0"
                                                          onsubmit="return confirm('این برچسب حذف شود؟')">
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

                    <div class="mk-adm-pagination">{{ $tags->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>

            @can('tags-create')
                <div class="modal fade mk-adm-modal" id="createTagModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="post" action="{{ route('admin.tags.store') }}" class="modal-content">
                            @csrf
                            <header class="mk-adm-modal-head">
                                <div>
                                    <h5>برچسب جدید</h5>
                                    <p>عنوان برچسب را وارد کنید؛ مثل «پرفروش» یا «اورجینال».</p>
                                </div>
                                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                            </header>
                            <div class="mk-adm-modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->store])
                                <div class="mk-field">
                                    <label for="tag-title">عنوان <em>*</em></label>
                                    <input type="text" name="title" id="tag-title" class="form-control"
                                           value="{{ old('title') }}" required maxlength="255" autocomplete="off">
                                </div>
                            </div>
                            <footer class="mk-adm-modal-foot">
                                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                                <button type="submit" class="btn btn-info">ایجاد برچسب</button>
                            </footer>
                        </form>
                    </div>
                </div>
            @endcan

            @can('tags-edit')
                @foreach($tags as $tag)
                    <div class="modal fade mk-adm-modal" id="editTagModal-{{ $tag->id }}" tabindex="-1"
                         role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="post" action="{{ route('admin.tags.update', ['tag' => $tag->id]) }}"
                                  class="modal-content">
                                @csrf
                                @method('PATCH')
                                <header class="mk-adm-modal-head">
                                    <div>
                                        <h5>ویرایش برچسب</h5>
                                        <p>{{ $tag->title }}</p>
                                    </div>
                                    <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
                                </header>
                                <div class="mk-adm-modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->update])
                                    <div class="mk-field">
                                        <label for="edit-tag-title-{{ $tag->id }}">عنوان <em>*</em></label>
                                        <input type="text" name="title" id="edit-tag-title-{{ $tag->id }}"
                                               class="form-control" value="{{ old('title', $tag->title) }}"
                                               required maxlength="255">
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

@section('scripts')
    @if($errors->store->any())
        <script>
            jQuery(function () {
                jQuery('#createTagModal').modal('show');
            });
        </script>
    @endif

    @if($errors->update->any() && session('tag_id'))
        <script>
            jQuery(function () {
                jQuery('#editTagModal-{{ (int) session('tag_id') }}').modal('show');
            });
        </script>
    @endif
@endsection
