@extends('admin.layouts.master')
@section('title')
    لیست دسته بندی ها
@endsection
@section('button')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن دسته بندی جدید
    </a>
@endsection
@php
    $active_parent = 'categories';
    $active_child = 'showcategories';
@endphp
@section('content')
    <div class="mx-4">
        <table class="table text-center table-responsive-sm">
            <thead>
            <tr>
                <th scope="col">تعداد</th>
                <th scope="col">نام</th>
                <th scope="col">نام انگلیسی</th>
                <th scope="col">والد</th>
                <th scope="col">وضعیت</th>
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $key => $category)
                <tr class="@if($category->parent_id==0) {{ 'table-info' }} @endif" >
                    <th>
                        {{ $categories->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $category->name }}
                    </td>
                    <td>
                        {{ $category->slug }}
                    </td>
                    <td>
                        @if($category->parent_id == 0)
                            <span class="badge badge-info">
                                سرپرست
                            </span>
                        @elseif($category->parent_id != 0)
                            {{ $category->parent->name }}
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $category->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{ $category->is_active }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.show' , [$category->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.categories.edit' , [$category->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                        <form action="{{ route('admin.categories.destroy', ['category' => $category]) }}" method="POST">
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
        {{ $categories->links() }}
    </div>
@endsection
