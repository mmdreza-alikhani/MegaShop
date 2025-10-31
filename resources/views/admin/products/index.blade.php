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
                <div class="input-group">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary c-primary" type="button">
                            <i class="fab fas fa-search"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="جستجو با نام محصول"
                           style="width: 300px"
                           value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
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
                                        <th scope="col">لینک کوتاه</th>
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
                                                {{ $product->shortLink->code }}
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="{{ route('admin.products.show', ['product' => $product]) }}" class="dropdown-item">نمایش</a>
                                                        <a href="{{ route('admin.products.edit', ['product' => $product]) }}" class="dropdown-item">ویرایش</a>
                                                        <button data-target="#deleteProductModal-{{ $product->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                </div>
                                                <div class="modal w-lg fade justify rtl" id="deleteProductModal-{{ $product->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">حذف محصول: {{ $product->title }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                آیا از این عملیات مطمئن هستید؟
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn outlined o-danger c-danger"
                                                                        data-dismiss="modal">بازگشت</button>
                                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn f-main">حذف</button>
                                                                </form>
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
                            {{ $products->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
