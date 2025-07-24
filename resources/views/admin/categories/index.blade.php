@extends('admin.layout.master')

@php
    $title = 'دسته بندی ها';
@endphp

@section('title', $title)

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="row m-1 pb-4 mb-3">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="page-header breadcrumb-header">
                        <div class="row align-items-end">
                            <div class="col-lg-8">
                                <div class="page-header-title text-left-rtl">
                                    <div class="d-inline">
                                        <h3 class="lite-text">پنل مدیریت</h3>
                                        <span class="lite-text">{{ $title }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $title }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex m-1 align-items-center justify-content-between">
                <div class="c-grey text-center">
                    <a href="{{ route('admin.categories.index') }}" class="btn f-primary fnt-xxs text-center">
                        ایجاد دسته بندی
                    </a>
                </div>
                <form action="{{ route('admin.categories.search') }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request()->keyword ?? '' }}" name="keyword" required>
                    </div>
                </form>
            </div>
            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $title }}</h5>
                            <hr/>
                            @if($categories->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج دسته بندی وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">والد</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $key => $category)
                                        <tr>
                                            <th>
                                                {{ $categories->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $category->title }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $category->isParent() ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $category->isParent() ? 'والد' : $category->parent->title  }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $category->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $category->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <button data-target="#showCategoryModal-{{ $category->id }}" data-toggle="modal" type="button" class="dropdown-item">نمایش</button>
                                                        <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}" class="dropdown-item">ویرایش</a>
                                                        <button data-target="#deleteCategoryModal-{{ $category->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="showCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        نمایش دسته بندی: {{ $category->title }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="title-{{ $category->id }}">عنوان:*</label>
                                                                            <input type="text" id="title-{{ $category->id }}" class="form-control" value="{{ $category->title }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="parent_id-{{ $category->id }}">والد:*</label>
                                                                            <input type="text" id="parent_id-{{ $category->id }}" class="form-control" value="{{ $category->isParent() ? 'والد' : $category->parent->title }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="is_active-{{ $category->id }}">وضعیت:*</label>
                                                                            <input type="text" id="is_active-{{ $category->id }}" class="form-control" value="{{ $category->is_active }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="icon-{{ $category->id }}">آیکون:*</label>
                                                                            <input type="text" id="icon-{{ $category->id }}" class="form-control" value="{{ $category->icon }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-12">
                                                                            <label for="description-{{ $category->id }}">توضیحات:*</label>
                                                                            <textarea id="description-{{ $category->id }}" class="form-control" disabled>>{{ $category->description }}</textarea>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <button class="btn btn-block text-right border border-info" type="button"
                                                                                    data-toggle="collapse" data-target="#filtersCollapse">
                                                                                فیلتر
                                                                            </button>
                                                                            <div id="filtersCollapse" class="collapse">
                                                                                <div class="row align-items-center">
                                                                                    @foreach($category->filters as $filter)
                                                                                        <p><a href="{{ route('admin.attributes.search', ['keyword' => $filter->title]) }}">{{ $filter->title }}</a>,</p>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <button class="btn btn-block text-right border border-info" type="button"
                                                                                    data-toggle="collapse" data-target="#variationCollapse">
                                                                                متغیر
                                                                            </button>
                                                                            <div id="variationCollapse" class="collapse">
                                                                                <div class="row">
                                                                                    @foreach($category->variation as $variation)
                                                                                        <p><a href="{{ route('admin.attributes.search', ['keyword' => $variation->title]) }}">{{ $variation->title }}</a>,</p>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal w-lg fade justify rtl" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف دسته بندی: {{ $category->title }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    آیا از این عملیات مطمئن هستید؟
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn outlined o-danger c-danger"
                                                                            data-dismiss="modal">بستن</button>
                                                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn f-main">حذف</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $categories->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
