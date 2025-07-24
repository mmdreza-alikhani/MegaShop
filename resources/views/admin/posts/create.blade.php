@extends('admin.layout.master')

@php
    $title = 'ایجاد پست';
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
                        @include('admin.layout.errors', ['errors' => $errors->store])
                        <form class="row p-3" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان:*</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active') && old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ old('is_active') && old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="type">نوع:*</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="article" {{ old('type') && old('type') == 'article' ? 'selected' : '' }}>مقاله</option>
                                    <option value="news" {{ old('type') && old('type') == 'news' ? 'selected' : '' }}>خبر</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="tagSelect">تگ ها:*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple data-live-search="true">
                                    @foreach($tags as $key => $value)
                                        <option value="{{ $key }}" {{ old('tag_ids') && in_array($key , old('tag_ids')) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group card shade c-grey col-12 col-lg-6">
                                <h5 class="card-header c-primary">افزودن تصویر</h5>
                                <div class="card-body row">
                                    <div class="col-12 m-1">
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
                            <div class="form-group col-12 col-lg-12">
                                <label for="text">متن:*</label>
                                <textarea id="text" type="text" name="text" class="form-control">{{ old('text') }}</textarea>
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
        // $(document).ready(function () {
        //     $('#text').summernote();
        // });
        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });
        $('#image').on('change', function () {
            const fileName = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
            $('#image-file-name').text(fileName);
        });

    </script>
@endsection
