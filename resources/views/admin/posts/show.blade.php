@extends('admin.layout.master')

@php
    $title = 'نمایش پست'
@endphp

@section('title', $title)

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
                    <div class="card-body row">
                        <div class="form-group col-12 col-lg-4">
                            <label for="title">عنوان:</label>
                            <input type="text" id="title" class="form-control" value="{{ $post->title }}" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="is_active">وضعیت:</label>
                            <select class="form-control" id="is_active" disabled>
                                <option value="1" {{ $post->is_active == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ $post->is_active == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="type">نوع:</label>
                            <select class="form-control" id="type" disabled>
                                <option {{ $post->type == 'article' ? 'selected' : '' }}>مقاله</option>
                                <option {{ $post->type == 'news' ? 'selected' : '' }}>خبر</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>برچسب ها</label>
                            <div class="form-control">
                                @foreach($post->tags as $tag)
                                    {{ $tag->title }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>نویسنده:</label>
                            <input type="text" value="{{ $post->author->username }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>لینک کوتاه:</label>
                            <input type="text" value="{{ $post->shortLink->code }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ ایجاد:</label>
                            <input type="text" value="{{ verta($post->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ آخرین تغییرات:</label>
                            <input type="text" value="{{ verta($post->updated_at) }}" class="form-control" disabled>
                        </div>
                        <div class="card shade c-grey ">
                            <div class="card-body">
                                <div class="row">
                                    <img class="card-img"
                                         src="{{ url(env('POST_IMAGE_UPLOAD_PATH')) . '/' . $post->image }}"
                                         alt="{{ $post->title }}-image">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label for="text">متن:</label>
                            <textarea id="text" type="text" class="form-control" disabled>{!! $post->text !!}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
