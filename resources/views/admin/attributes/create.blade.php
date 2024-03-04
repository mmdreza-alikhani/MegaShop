@extends('admin.layouts.master')
@section('title' , 'ایجاد ویژگی')
@php
    $active_parent = 'attributes';
    $active_child = 'makeattribute'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.attributes.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                         افزودن ویژگی
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
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
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
