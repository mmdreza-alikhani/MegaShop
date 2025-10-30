@extends('admin.layout.master')

@php
    $title = 'ایجاد پست';
@endphp

@section('title', $title)

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <div class="mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body">
                        @include('admin.layout.errors', ['errors' => $errors->store])
                        <form class="p-3" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12 col-lg-3">
                                    <label for="title">عنوان:*</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="is_active">وضعیت:*</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active') && old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                        <option value="0" {{ old('is_active') && old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="type">نوع:*</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="article" {{ old('type') && old('type') == 'article' ? 'selected' : '' }}>مقاله</option>
                                        <option value="news" {{ old('type') && old('type') == 'news' ? 'selected' : '' }}>خبر</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="short_link">لینک کوتاه:(برای تولید خودکار این فیلد را خالی بگذارید.)</label>
                                    <input type="text" name="short_link" id="short_link" class="form-control" value="{{ old('short_link') }}">
                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label for="tagSelect">برچسب ها:*</label>
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
                                <div class="form-group col-12">
                                    <label for="text">متن:*</label>
                                    <textarea id="text" type="text" name="text" class="form-control" required>{{ old('text') }}</textarea>
                                </div>
                                <label for="faqsContainer">سوالات متداول:</label>
                                <div class="col-12" id="faqsContainer">
                                    <div id="first">
                                        <div class="recordset border-bottom">
                                            <div class="row m-2">
                                                <div class="form-group col-12">
                                                    <label for="question">عنوان سوال:*</label>
                                                    <input id="question" type="text" name="faqs[][question]" class="form-control question" required>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="answer">پاسخ سوال:*</label>
                                                    <textarea id="answer" type="text" name="faqs[][answer]" class="form-control answer" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                                <button type="submit" class="btn f-primary p-2">ادامه</button>
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
        CKEDITOR.replace('text', {
            language: 'en',
            filebrowserImageUploadUrl: '/ckeditor/upload?_token=' + document.querySelector('meta[name="csrf-token"]').content,
            filebrowserBrowseUrl: '/storage/ckeditor/images',
            filebrowserImageBrowseUrl: '/storage/ckeditor/images',

            extraPlugins: 'uploadimage,filebrowser',
            removePlugins: 'image2,easyimage,cloudservices',
            toolbar: [
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                { name: 'tools', items: ['Maximize'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] }
            ]
        });

        $('#faqsContainer').czMore();

        $('#tagSelect').selectpicker({
            title: 'انتخاب برچسب'
        });

        $('#image').on('change', function () {
            const fileName = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
            $('#image-file-name').text(fileName);
        });

        $('form').on('submit', function () {
            // Remove previous name attributes to avoid duplicates
            $('.question, .answer').removeAttr('name');

            $('#faqsContainer .recordset').each(function (index) {
                $(this).find('.question').attr('name', `faqs[${index}][question]`);
                $(this).find('.answer').attr('name', `faqs[${index}][answer]`);
            });
        });
    </script>
@endsection
