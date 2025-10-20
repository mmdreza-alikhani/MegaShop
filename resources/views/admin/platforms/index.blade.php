@extends('admin.layout.master')

@php
    $title = 'پلتفرم ها';
@endphp

@section('title', $title)

@section('styles')
    <style>
        /* Make the wrapper blend in */
        .imageInsertDiv {
            background-color: transparent !important;
            border: none;
            padding: 0;
        }

        /* Remove button background and border */
        .imageInsertBtn {
            background-color: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            color: inherit; /* Optional: inherit text color */
        }
    </style>
@endsection

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
                @can('platforms-create')
                    <div class="c-grey text-center">
                        <button data-target="#createPlatformModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                            ایجاد پلتفرم
                        </button>
                    </div>
                    @include('admin.platforms.partials.create-modal')
                @endcan
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
                            @if($platforms->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج پلتفرمی وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($platforms as $key => $platform)
                                        <tr>
                                            <th>
                                                {{ $platforms->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $platform->title }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $platform->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $platform->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        @can('platforms-edit')
                                                            <button data-target="#editPlatformModal-{{ $platform->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        @endcan
                                                        @can('platforms-delete')
                                                            <button data-target="#deletePlatformModal-{{ $platform->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                                @can('platforms-edit')
                                                    @include('admin.platforms.partials.edit-modal')
                                                @endcan
                                                @can('platforms-delete')
                                                    @include('admin.platforms.partials.delete-modal')
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $platforms->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
