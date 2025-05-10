@extends('admin.layout.master')
@section('title' , 'ایجاد بنر')
@php
    $active_parent = 'banners';
    $active_child = 'makebanner'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.banners.store') }}" method="POST" class="row" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        مشخصات برند
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="title">عنوان*</label>
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="editor">متن</label>
                                <textarea type="text" name="text" id="editor" class="form-control">
                                    {{ old('text') }}
                                </textarea>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1">فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="type">نوع بنر*</label>
                                <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن دکمه
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="button_text">متن دکمه*</label>
                                <input type="text" name="button_text" id="button_text" class="form-control"
                                       value="{{ old('button_text') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="button_link">لینک دکمه*</label>
                                <input type="text" name="button_link" id="button_link" class="form-control"
                                       value="{{ old('button_link') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="button_icon">آیکون دکمه*</label>
                                <input type="text" name="button_icon" id="button_icon" class="form-control"
                                       value="{{ old('button_icon') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        اولویت
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-12">
                                <label for="priority">اولویت*</label>
                                <input type="number" name="priority" id="priority" class="form-control"
                                       value="{{ old('priority') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن تصویر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="custom-file col-12 m-1">
                                <input type="file" name="image" id="image" class="form-control custom-control-input"
                                       lang="fa">
                                <label for="image" class="custom-file-label">تصویر</label>
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
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-danger w-100" type="cancel"
                                   name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('#image').change(function () {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })

        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: {
                    // The UI will be English.
                    ui: 'fa',

                    // But the content will be edited in Arabic.
                    content: 'fa'
                }
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });
    </script>
@endsection
