@extends('admin.layout.master')
@section('title' , 'ایجاد نقش')
@php
    $active_parent = 'users';
    $active_child = 'roles'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.roles.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">نام*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="displayName">نام نمایشی*</label>
                                <input type="text" name="displayName" id="displayName" class="form-control"
                                       value="{{ old('displayName') }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-right" type="button"
                                                    data-toggle="collapse" data-target="#permissionsCollapse">
                                                دسترسی به مجوز ها
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="permissionsCollapse" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body row">
                                            @foreach($permissions as $permission)
                                                <div class="form-check col-md-2">
                                                    <label class="form-check-label"
                                                           for="{{ $permission->name . '-check' }}">
                                                        {{ $permission->display_name }}
                                                    </label>
                                                    <input class="form-check-input mr-1" type="checkbox"
                                                           value="{{ $permission->name }}"
                                                           name="{{ $permission->name }}"
                                                           id="{{ $permission->name . '-check' }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
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
                                <button class="btn btn-primary w-100" type="submit" name="submit">افزودن</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-danger w-100" type="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
