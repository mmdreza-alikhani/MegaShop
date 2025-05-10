@extends('admin.layout.master')
@section('title')
    خبر : {{ $news->name }}
@endsection
@php
    $active_parent = 'news';
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
                            <input type="text" value="{{ $news->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>اسلاگ</label>
                            <input type="text" value="{{ $news->slug }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تگ ها</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($news->tags as $tag)
                                    {{ $tag->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>متن</label>
                            <textarea type="text" class="form-control" disabled>{{ strip_tags($news->text) }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $news->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>نویسنده*</label>
                            <input type="text" value="{{ $news->author->username }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($news->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($news->updated_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <div class="card-body">
                                <div class="row">
                                    <img class="card-img"
                                         src="{{ url(env('NEWS_IMAGE_UPLOAD_PATH')) . '/' . $news->primary_image }}"
                                         alt="{{ $news->name }}-image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>

    </div>
@endsection
