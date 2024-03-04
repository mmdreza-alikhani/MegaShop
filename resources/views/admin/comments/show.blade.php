@extends('admin.layouts.master')
@section('title')
    نظر : {{ $comment->user->username }}
@endsection
@php
    $active_parent = 'comments';
    $active_child = '';
    if ($comment->product_id != null){
        $title = $comment->product->name;
    }elseif($comment->article_id != null){
        $title = $comment->article->title;
    }else{
        $title = $comment->news->title;
    }
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
                            <label>کاربر</label>
                            <input type="text" value="{{ $comment->user->username }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>عنوان محصول | مقاله | خبر</label>
                            <input type="text" value="{{ $title }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>وضعیت</label>
                            <br>
                            <span class="badge {{ $comment->getRawOriginal('approved') ?  'badge-success' : 'badge-secondary' }}">
                                {{$comment->approved}}
                            </span>
                        </div>
{{--                        <div class="form-group col-12 col-lg-6">--}}
{{--                            <label>در جواب به نظر:</label>--}}
{{--                            <input type="text" value="{{ $comment->reply_of == 0 ? 'نظر مستقل' : $comment->reply_for->user->username . ' برای محصول ' . $comment->reply_for->product->name }}" class="form-control" disabled>--}}
{{--                        </div>--}}
                        <div class="form-group col-12 col-lg-12">
                            <label>متن نظر</label>
                            <input type="text" value="{{ $comment->text }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($comment->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($comment->updated_at) }}" class="form-control" disabled>
                        </div>
                        @if($comment->product_id != null)
                            <div class="form-group col-12 col-lg-3">
                                <label>امتیاز داده شده</label>
                                <input type="text" value="{{ $comment->user->rates->where('product_id', $comment->product_id)->first()->rate . ' ستاره' }}" class="form-control" disabled>
                            </div>
                        @endif
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
                        @if($comment->getRawOriginal('approved') == 1)
                            <div class="col-12">
                                <a href="{{ route('admin.comments.changeStatus', ['comment' => $comment->id]) }}" class="btn btn-danger w-100">عدم تایید</a>
                            </div>
                        @else
                            <div class="col-12">
                                <a href="{{ route('admin.comments.changeStatus', ['comment' => $comment->id]) }}" class="btn btn-success w-100">تایید</a>
                            </div>
                        @endif
                        <hr>
                            <div class="col-12 mt-3">
                                <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-danger w-100">بازگشت</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
