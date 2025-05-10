@extends('admin.layout.master')
@section('title')
    دسته بندی : {{ $category->name }}
@endsection
@php
    $active_parent = 'categories';
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
                            <input type="text" value="{{ $category->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>نام انگلیسی</label>
                            <input type="text" value="{{ $category->slug }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>والد</label>
                            <input type="text"
                                   value="@if($category->parent_id == 0)سرپرست@elseif($category->parent_id != 0){{$category->parent->name}}@endif"
                                   class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $category->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>آیکون</label>
                            <input type="text" value="{{ $category->icon }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>ویژگی ها</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($category->attributes as $attribute)
                                    {{ $attribute->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>ویژگی های قابل فیلتر</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($category->attributes()->wherePivot('is_filter' , 1)->get() as $attribute)
                                    {{ $attribute->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>ویژگی های قابل متغیر</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($category->attributes()->wherePivot('is_variation' , 1)->get() as $attribute)
                                    {{ $attribute->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>توضیحات</label>
                            <textarea class="form-control" disabled>{{ $category->description }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($category->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($category->updated_at) }}" class="form-control" disabled>
                        </div>
                    </div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
