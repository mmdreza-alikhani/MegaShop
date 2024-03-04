@extends('admin.layouts.master')
@section('title')
    ویرایش کاربر: {{$user->username}}
@endsection
@php
    $active_parent = 'users';
    $active_child = 'makeuser'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.users.update' , ['user' => $user->id]) }}" method="POST" class="row">
            @csrf
            @method('put')
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="username">نام کاربری*</label>
                                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}">
                                <input type="hidden" name="user_id" class="form-control" value="{{ $user->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="role">نقش کاربری*</label>
                                <select class="form-control" id="role" name="role">
                                    <option></option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-right" type="button" data-toggle="collapse" data-target="#permissionsCollapse">
                                                دسترسی به مجوز ها
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="permissionsCollapse" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body row">
                                            @foreach($permissions as $permission)
                                                <div class="form-check col-md-2">
                                                    <label class="form-check-label" for="{{ $permission->name . '-check' }}">
                                                        {{ $permission->display_name }}
                                                    </label>
                                                    <input class="form-check-input mr-1" type="checkbox" value="{{ $permission->name }}" name="{{ $permission->name }}" id="{{ $permission->name . '-check' }}" {{ in_array($permission->id, $user->getAllPermissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="first_name">نام</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{$user->first_name}}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="last_name">نام خانوادگی</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{$user->last_name}}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="phone_number">شماره تلفن</label>
                                <input type="tel" minlength="10" maxlength="10" name="phone_number" id="phone_number" class="form-control" value="{{$user->phone_number}}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="email">ایمیل</label>
                                <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="newPassword">اختصاص رمز عبور جدید</label>
                                <input type="text" minlength="8" maxlength="12" name="newPassword" id="newPassword" class="form-control" placeholder="رمز عبور جدید را وارد کنید">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="status">وضعیت</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $user->getRawOriginal('status') ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ $user->getRawOriginal('status') ? '' : 'selected' }}>غیرفعال</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
