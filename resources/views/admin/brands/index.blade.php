@extends('admin.layout.master')

@php
    $title = 'برند ها';
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
                    <button data-target="#createBrandModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                        ایجاد برند
                    </button>
                </div>
                <div class="modal w-lg fade light rtl" id="createBrandModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('admin.brands.store') }}">
                            @csrf
                            <div class="modal-content card shade">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        ایجاد برند جدید
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->store])
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="title">نام:*</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                   value="{{ old('title') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="is_active">وضعیت:*</label>
                                            <select class="form-control" id="is_active" name="is_active" required>
                                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn main f-main">ایجاد</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{ route('admin.brands.search') }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword" required>
                    </div>
                </form>
            </div>
            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $title }}</h5>
                            <hr/>
                            @if($brands->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج برندی وجود ندارد!
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
                                    @foreach($brands as $key => $brand)
                                        <tr>
                                            <th>
                                                {{ $brands->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $brand->title }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $brand->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $brand->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <button data-target="#editBrandModal-{{ $brand->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        <button data-target="#deleteBrandModal-{{ $brand->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="editBrandModal-{{ $brand->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post" action="{{ route('admin.brands.update', ['brand' => $brand->id]) }}">
                                                                @method('put')
                                                                @csrf
                                                                <div class="modal-content card shade">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            ویرایش برند: {{ $brand->title }}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @include('admin.layout.errors', ['errors' => $errors->update])
                                                                        <div class="row">
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="title-{{ $brand->id }}">عنوان:*</label>
                                                                                <input type="text" name="title" id="title-{{ $brand->id }}" class="form-control"
                                                                                       value="{{ $brand->title }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="is_active-{{ $brand->id }}">وضعیت:*</label>
                                                                                <select class="form-control" id="is_active-{{ $brand->id }}" name="is_active" required>
                                                                                    <option value="1" {{ $brand->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                                                    <option value="0" {{ $brand->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                        <button type="submit" class="btn main f-main">ویرایش</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @if(count($errors->update) > 0)
                                                        <script>
                                                            $(function() {
                                                                $('#editBrandModal-{{ session()->get('brand_id') }}').modal({
                                                                    show: true
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="modal w-lg fade justify rtl" id="deleteBrandModal-{{ $brand->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف برند: {{ $brand->title }}</h5>
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
                                                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display: inline;">
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
                            {{ $brands->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        @if(count($errors->store) > 0)
        $(function() {
            $('#createBrandModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
