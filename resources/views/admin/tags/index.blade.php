@extends('admin.layouts.master')
@section('title')
    لیست تگ ها
@endsection
@section('button')
    <a href="{{ route('admin.tags.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن تگ جدید
    </a>
@endsection
@php
    $active_parent = 'tags';
    $active_child = 'showtags';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.tags.search') }}" method="GET" style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام تگ" value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
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
            @foreach($tags as $key => $tag)
                <tr>
                    <th>
                        {{ $tags->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $tag->name }}
                    </td>
                    <td>
                        <div class="">
                            <a href="{{ route('admin.tags.show' , [$tag->id]) }}" class="btn btn-success m-1">
                                نمایش
                            </a>
                            <a href="{{ route('admin.tags.edit' , [$tag->id]) }}" class="btn btn-info m-1">
                                ویرایش
                            </a>
                            <form action="{{ route('admin.tags.destroy', ['tag' => $tag]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger m-1" type="submit">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $tags->links() }}
    </div>
@endsection
