@extends('admin.layout.master')
@section('title')
    ویرایش مقاله: {{$article->title}}
@endsection
@php
    $active_parent = 'articles';
    $active_child = 'makearticle'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.articles.update' , ['article' => $article->id]) }}" method="POST" class="row"
              enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان*</label>
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ $article->title }}">
                                <input type="hidden" name="id" id="id" class="form-control" value="{{ $article->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="slug">نام انگلیسی</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                       placeholder="{{$article->slug}}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="tagSelect">تگ ها*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple
                                        data-live-search="true">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                                {{ in_array($tag->id , $article->tags()->pluck('id')->toArray() ) ? 'selected' : '' }}
                                        >{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $article->getRawOriginal('is_active') ? 'selected' : '' }}>
                                        فعال
                                    </option>
                                    <option value="0" {{ $article->getRawOriginal('is_active') ? '' : 'selected' }}>
                                        غیرفعال
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="summernote">متن*</label>
                                <textarea id="summernote" type="text" name="text"
                                          class="form-control">{!! $article->text !!} </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش تصویر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <img class="card-img"
                                     src="{{ url(env('ARTICLE_IMAGE_UPLOAD_PATH')) . '/' . $article->primary_image }}"
                                     alt="{{ $article->title }}-image">
                            </div>
                            <div class="col-6" style="display: flex;align-items: center">
                                <div class="custom-file col-12 m-1">
                                    <input type="file" name="image" id="image" class="form-control custom-control-input"
                                           lang="fa">
                                    <label for="image" class="custom-file-label">تصویر</label>
                                </div>
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
                                <a href="{{ route('admin.articles.index') }}" class="btn btn-danger w-100" type="cancel"
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
