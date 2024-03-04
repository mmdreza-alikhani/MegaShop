@extends('admin.layouts.master')
@section('title')
    لیست بنر ها
@endsection
@section('button')
    <a href="{{ route('admin.banners.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن بنر جدید
    </a>
@endsection
@php
    $active_parent = 'banners';
    $active_child = 'showbanners';
@endphp
@section('content')
    <div class="mx-4">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام</th>
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
                            {{$banner->is_active}}
                        </span>
                    </td>
                    <td>
                        {{ $banner->type }}
                    </td>
                    <td>
                        <div class="row" style="display: flex;justify-content: center">
                            <a href="{{ route('admin.banners.show' , [$banner->id]) }}" class="btn btn-success m-1">
                                نمایش
                            </a>
                            <a href="{{ route('admin.banners.edit' , [$banner->id]) }}" class="btn btn-info m-1">
                                ویرایش
                            </a>
                            <form action="{{ route('admin.banners.destroy', ['banner' => $banner]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="banner_id" value="{{{ $banner->id }}}">
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
        {{ $banners->links() }}
    </div>
@endsection
