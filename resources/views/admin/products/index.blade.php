@extends('admin.layout.master')

@section('title')
    لیست محصولات
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
                                        <span class="lite-text">محصولات</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">محصولات</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex m-1 align-items-center justify-content-between">
                <div class="c-grey text-center">
                    <a href="{{ route('admin.products.create') }}" class="btn f-primary fnt-xxs text-center">
                        ایجاد محصول
                    </a>
                </div>
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" placeholder="جستجو با نام محصول"
                           value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary c-primary" type="button">
                            <i class="fab fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">محصولات</h5>
                            <hr/>
                            @if($products->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج محصولی وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">عنوان برند</th>
                                        <th scope="col">عنوان دسته</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $key => $product)
                                        <tr>
                                            <th>
                                                {{ $products->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $product->title }}
                                            </td>
                                            <td>
                                                <a href="">
                                                    {{ $product->category->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="">
                                                    {{ $product->category->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge {{ $product->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $product->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="#" class="dropdown-item">نمایش</a>
                                                        <a href="#" class="dropdown-item">ویرایش</a>
                                                        <a href="#" class="dropdown-item">حذف</a>
                                                    </div>
                                                </div>
                                                <div class="modal w-lg fade light rtl" id="deleteUserModal-{{ $product->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ route('admin.products.store') }}">
                                                            @csrf
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        ایجاد محصول جدید
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                    <button type="submit" class="btn main f-main">ایجاد</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $products->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
