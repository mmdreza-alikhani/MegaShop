@extends('admin.layout.master')
@section('title')
    لیست برند ها
@endsection
@section('button')
    <a href="{{ route('admin.brands.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن برند جدید
    </a>
@endsection
@php
    $active_parent = 'brands';
    $active_child = 'showbrands';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.brands.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام برند"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">تعداد</th>
                <th scope="col">نام</th>
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
                        {{ $brand->name }}
                    </td>
                    <td>
                        <span class="badge {{ $brand->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{$brand->is_active}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.brands.show' , [$brand->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.brands.edit' , [$brand->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                        <form action="{{ route('admin.brands.destroy', ['brand' => $brand]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger m-1" type="submit">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $brands->links() }}
    </div>
@endsection
