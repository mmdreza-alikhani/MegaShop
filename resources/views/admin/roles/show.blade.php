@extends('admin.layout.master')
@section('title')
    نقش : {{ $role->display_name }}
@endsection
@php
    $active_parent = 'users';
    $active_child = 'roles'
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    نمایش
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label>نام</label>
                            <input type="text" value="{{ $role->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>نام نمایشی</label>
                            <input type="text" value="{{ $role->display_name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($role->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($role->updated_at) }}" class="form-control" disabled>
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
                                <div id="permissionsCollapse" class="collapse show" data-parent="#accordionExample">
                                    <div class="card-body row">
                                        @foreach($role->permissions as $permission)
                                            <div class="form-check col-md-2">
                                                <label class="form-check-label"
                                                       for="{{ $permission->name . '-check' }}">
                                                    {{ $permission->display_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>
    </div>
@endsection
