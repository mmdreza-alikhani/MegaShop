@extends('admin.layout.master')
@section('title')
    برند : {{ $attribute->name }}
@endsection
@php
    $active_parent = 'attributes';
    $active_child = ''
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
                            <label>عنوان</label>
                            <input type="text" value="{{ $attribute->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($attribute->created_at) }}" class="form-control"
                                   disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($attribute->updated_at) }}" class="form-control"
                                   disabled>
                        </div>
                    </div>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
