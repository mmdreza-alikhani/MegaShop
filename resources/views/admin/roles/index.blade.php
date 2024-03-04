@extends('admin.layouts.master')
@section('title')
    لیست نقش ها
@endsection
@section('button')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن نقش جدید
    </a>
@endsection
@php
    $active_parent = 'users';
    $active_child = 'roles';
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
            @foreach($roles as $key => $role)
                <tr>
                    <th>
                        {{ $roles->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $role->display_name }}
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.show' , [$role->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.roles.edit' , [$role->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                        <form action="{{ route('admin.roles.destroy', ['role' => $role]) }}" method="POST">
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
        {{ $roles->links() }}
    </div>
@endsection
