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
                    <a href="{{ route('admin.categories.create') }}" class="btn f-primary fnt-xxs text-center">
                        ایجاد دسته بندی
                    </a>
                </div>
                <form action="#" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request('q') }}" name="q" required>
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
                                                        @can('categories-index')
                                                            <button data-target="#showCategoryModal-{{ $category->id }}" data-toggle="modal" type="button" class="dropdown-item">نمایش</button>
                                                        @endcan
                                                        @can('categories-edit')
                                                            <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}" class="dropdown-item">ویرایش</a>
                                                        @endcan
                                                        @can('categories-delete')
                                                            <button data-target="#deleteCategoryModal-{{ $category->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                            @can('categories-index')
                                                @include('admin.categories.partials.show-modal')
                                            @endcan
                                            @can('categories-delete')
                                                @include('admin.categories.partials.delete-modal')
                                            @endcan
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
