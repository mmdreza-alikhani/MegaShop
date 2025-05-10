@extends('admin.layout.master')
@section('title')
    لیست کاربران
@endsection
@section('button')
    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن کاربر جدید
    </a>
@endsection
@php
    $active_parent = 'users';
    $active_child = 'showusers';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.users.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام کاربری کاربر"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center table-responsive-lg">
            <thead>
            <tr>
                <th scope="col">تعداد</th>
                <th scope="col">نام کاربری</th>
                <th scope="col">آواتار</th>
                <th scope="col">ایمیل</th>
                <th scope="col">وضعیت</th>
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $key => $user)
                <tr>
                    <th>
                        {{ $users->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $user->username }}
                    </td>
                    <td>
                        <img src="{{ Str::contains($user->avatar, 'https://') ? $user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $user->avatar }}"
                             alt="{{ $user->username }}-image" id="output" width="100" height="100"/>
                    </td>
                    <td>
                        {{ $user->email }}
                        @if($user->email_verified_at == null)
                            <i class="fa fa-times-circle text-danger"></i>
                        @else
                            <i class="fa fa-check-circle text-success"></i>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $user->getRawOriginal('status') ?  'badge-success' : 'badge-secondary' }}">
                            {{$user->status}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show' , [$user->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.users.edit' , [$user->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
@endsection
