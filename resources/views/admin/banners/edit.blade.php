@extends('admin.layout.master')

@php
    $title = 'ویرایش بنر'
@endphp

@section('title', $title)

@section('styles')
    <style>
        .d-flex.border.rounded.p-2 {
            background-color: transparent !important;
            border: none;
            padding: 0;
        }

        .btn.f-primary {
            background-color: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            color: inherit;
        }
    </style>
@endsection

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="row m-1 pb-4 mb-3">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="page-header breadcrumb-header">
                        <div class="row align-items-end">
                            <div class="col-lg-8">
                                <div class="page-header-title text-left-rtl">
                                    <div class="d-inline">
                                        <h3 class="lite-text">پنل مدیریت</h3>
                                        <span class="lite-text">{{ $title }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $title }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body">
                        @include('admin.layout.errors', ['errors' => $errors->update])
                        <form class="row p-3" action="{{ route('admin.banners.update', ['banner' => $banner]) }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان:*</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $banner->is_active == '1' ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ $banner->is_active == '0' ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="type">نوع:*</label>
                                <input type="text" name="type" id="type" class="form-control" value="{{ $banner->type }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="priority">اولویت:*</label>
                                <input type="number" min="0" max="100" name="priority" id="priority" class="form-control" value="{{ $banner->priority }}" required>
                            </div>
                            <div class="form-group card shade c-grey col-12 col-lg-6">
                                <div class="card-header bg-primary">
                                    ویرایش تصویر
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <img class="card-img"
                                                 src="{{ Storage::url(config('upload.banner_path') . '/') . $banner->image }}"
                                                 alt="{{ $banner->title }}-image">
                                        </div>
                                        <div class="col-6 m-1">
                                            <label class="d-block mb-2 text-right" for="image">تصویر اصلی</label>
                                            <div class="d-flex flex-row-reverse align-items-center border rounded p-2" style="background-color: #f8f9fa;">
                                            <span class="btn f-primary ml-2 px-2" onclick="document.getElementById('image').click();">
                                                انتخاب فایل
                                            </span>
                                                <span id="image-file-name" class="text-muted flex-grow-1 text-right">
                                                هیچ فایلی انتخاب نشده
                                            </span>
                                                <input type="file" id="image" name="image" class="d-none" lang="fa">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group card shade c-grey col-12 col-lg-6">
                                <h5 class="card-header c-primary">دکمه</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="button_text">متن دکمه:*</label>
                                            <input type="text" name="button_text" id="button_text" class="form-control"
                                                   value="{{ $banner->button_text }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="button_link">لینک دکمه:*</label>
                                            <input type="text" name="button_link" id="button_link" class="form-control"
                                                   value="{{ $banner->button_link }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="button_icon">آیکون دکمه:*</label>
                                            <input type="text" name="button_icon" id="button_icon" class="form-control"
                                                   value="{{ $banner->button_icon }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="text">متن:*</label>
                                <textarea id="text" type="text" name="text" class="form-control">{!! $banner->text !!}</textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                                <button type="submit" class="btn f-primary">ادامه</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $('#image').on('change', function () {
            const fileName = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
            $('#image-file-name').text(fileName);
        });

    </script>
@endsection
