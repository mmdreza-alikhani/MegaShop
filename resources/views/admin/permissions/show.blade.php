@extends('admin.layout.master')
@section('title')
    مجوز : {{ $permission->name }}
@endsection
@php
    $active_parent = 'users';
    $active_child = 'permissions'
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
        <div class="col-lg-7 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    نمایش
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label>نام</label>
                            <input type="text" value="{{ $permission->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>نام نمایشی</label>
                            <input type="text" value="{{ $permission->display_name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($permission->created_at) }}" class="form-control"
                                   disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($permission->updated_at) }}" class="form-control"
                                   disabled>
                        </div>
                    </div>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>
    </div>
@endsection
