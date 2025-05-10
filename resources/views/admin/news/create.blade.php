@extends('admin.layout.master')
@section('title' , 'ایجاد خبر')
@php
    $active_parent = 'news';
    $active_child = 'makenews'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.news.store') }}" method="POST" class="row" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان*</label>
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" selected>فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="tagSelect">تگ ها*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple
                                        data-live-search="true">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="summernote">متن*</label>
                                <textarea id="summernote" type="text" name="text"
                                          class="form-control">{{ old('text') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 ">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن تصویر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="form-control custom-control-input"
                                       lang="fa">
                                <label for="image" class="custom-file-label">تصویر اصلی</label>
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
                                <a href="{{ route('admin.news.index') }}" class="btn btn-danger w-100" type="cancel">بازگشت</a>
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
        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });
        $('#image').change(function () {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })
        $(document).ready(function () {
            $('#summernote').summernote();
        });
    </script>
@endsection
