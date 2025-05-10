@extends('admin.layout.master')
@section('title')
    ویرایش برند {{$brand->name}}
@endsection
@php
    $active_parent = 'brands';
    $active_child = 'makebrand'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.brands.update' , ['brand' => $brand->id]) }}" method="POST" class="row">
            @csrf
            @method('put')
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ $brand->name }}">
                                <input type="hidden" name="id" id="id" class="form-control" value="{{ $brand->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="slug">نام انگلیسی</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                       placeholder="{{$brand->slug}}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $brand->getRawOriginal('is_active') ? 'selected' : '' }}>فعال
                                    </option>
                                    <option value="0" {{ $brand->getRawOriginal('is_active') ? '' : 'selected' }}>
                                        غیرفعال
                                    </option>
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
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-danger w-100" type="cancel"
                                   name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
