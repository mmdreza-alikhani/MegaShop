@extends('admin.layout.master')
@section('title')
    لیست محصولات
@endsection
@section('button')
    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن محصول جدید
    </a>
@endsection
@php
    $active_parent = 'products';
    $active_child = 'showproducts';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.products.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام محصول"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center table-responsive-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام</th>
                <th scope="col">نام برند</th>
                <th scope="col">نام دسته</th>
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
                        <a href="{{ route('admin.products.show' , ['product' => $product->slug]) }}">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.brands.show' , ['brand' => $product->brand->id]) }}">
                            {{ $product->brand->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.show' , ['category' => $product->category->id]) }}">
                            {{ $product->category->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $product->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{$product->is_active}}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown m-1">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                ویرایش
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                       href="{{ route('admin.products.edit' , ['product' => $product->slug]) }}">ویرایش
                                        محصول</a></li>
                                <li><a class="dropdown-item"
                                       href="{{ route('admin.products.images.edit' , ['product' => $product->slug]) }}">ویرایش
                                        تصاویر</a></li>
                                <li><a class="dropdown-item"
                                       href="{{ route('admin.products.category.edit' , ['product' => $product->slug]) }}">ویرایش
                                        دسته بندی</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('admin.products.show' , ['product' => $product->slug]) }}"
                           class="btn btn-success m-1">
                            نمایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
@endsection
