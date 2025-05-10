@extends('admin.layout.master')
@section('title')
    مقاله : {{ $article->title }}
@endsection
@php
    $active_parent = 'articles';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    نمایش
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-4">
                            <label>عنوان</label>
                            <input type="text" value="{{ $article->title }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>اسلاگ</label>
                            <input type="text" value="{{ $article->slug }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تگ ها</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($article->tags as $tag)
                                    {{ $tag->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>متن</label>
                            <textarea type="text" class="form-control"
                                      disabled>{{ strip_tags($article->text) }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $article->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>نویسنده*</label>
                            <input type="text" value="{{ $article->author->username }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($article->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($article->updated_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <div class="card-body">
                                <div class="row">
                                    <img class="card-img"
                                         src="{{ url(env('ARTICLE_IMAGE_UPLOAD_PATH')) . '/' . $article->primary_image }}"
                                         alt="{{ $article->title }}-image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>

    </div>
@endsection
