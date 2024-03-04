@extends('admin.layouts.master')
@section('title')
    لیست مجوز ها
@endsection
@section('button')
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن مجوز جدید
    </a>
@endsection
@php
    $active_parent = 'users';
    $active_child = 'permissions';
@endphp
@section('content')
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
            @foreach($permissions as $key => $permission)
                <tr>
                    <th>
                        {{ $permissions->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $permission->display_name }}
                    </td>
                    <td>
                        <a href="{{ route('admin.permissions.show' , [$permission->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $permissions->links() }}
    </div>
@endsection
