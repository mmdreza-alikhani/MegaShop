@extends('admin.layouts.master')
@section('title')
    لیست ویژگی ها
@endsection
@section('button')
    <a href="{{ route('admin.attributes.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن ویژگی جدید
    </a>
@endsection
@php
    $active_parent = 'attributes';
    $active_child = 'showattributes';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.attributes.search') }}" method="GET" style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام ویژگی" value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
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
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attributes as $key => $attribute)
                <tr>
                    <th>
                        {{ $attributes->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $attribute->name }}
                    </td>
                    <td>
                        <a href="{{ route('admin.attributes.show' , [$attribute->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.attributes.edit' , [$attribute->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                        <form action="{{ route('admin.attributes.destroy', ['attribute' => $attribute]) }}" method="POST">
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
{{--        {{ $attributes->links('vendor.pagination.bootstrap-5') }}--}}
        {{ $attributes->render() }}
    </div>
@endsection
