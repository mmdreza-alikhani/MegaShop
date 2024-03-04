@extends('admin.layouts.master')
@section('title')
    لیست پلتفرم ها
@endsection
@section('button')
    <a href="{{ route('admin.platforms.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن پلتفرم جدید
    </a>
@endsection
@php
    $active_parent = 'platforms';
    $active_child = 'showplatform';
@endphp
@section('content')ظ
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
            @foreach($platforms as $key => $platform)
                <tr>
                    <th>
                        {{ $platforms->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $platform->name }}
                    </td>
                    <td>
                        <span class="badge {{ $platform->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{$platform->is_active}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.platforms.show' , [$platform->slug]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.platforms.edit' , [$platform->slug]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                        <form action="{{ route('admin.platforms.destroy', ['platform' => $platform]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="image_id" value="{{{ $platform->slug }}}">
                            <button class="btn btn-danger m-1" type="submit">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $platforms->links() }}
    </div>
@endsection
