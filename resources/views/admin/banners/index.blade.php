@extends('admin.layout.master')

@php
    $title = 'بنر ها';
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
                    <a href="{{ route('admin.banners.create') }}" type="button" class="btn f-primary fnt-xxs text-center">
                        ایجاد بنر
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
                            @if($banners->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج بنری وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">اولویت</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">نوع بنر</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $key => $banner)
                                        <tr>
                                            <th>
                                                {{ $banners->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $banner->title }}
                                            </td>
                                            <td>
                                                {{ $banner->priority }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $banner->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $banner->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $banner->type }}
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        @can('banners-index')
                                                            <a href="{{ route('admin.banners.show', ['banner' => $banner->id]) }}" class="dropdown-item">نمایش</a>
                                                        @endcan
                                                        @can('banners-edit')
                                                            <a href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}" class="dropdown-item">ویرایش</a>
                                                        @endcan
                                                        @can('banners-delete')
                                                            <button data-target="#deleteBannerModal-{{ $banner->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                            @can('attributes-delete')
                                                @include('admin.banners.partials.delete-modal')
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $banners->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
