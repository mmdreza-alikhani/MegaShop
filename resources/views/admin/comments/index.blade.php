@extends('admin.layouts.master')
@section('title')
    لیست نظرات
@endsection
@php
    $active_parent = 'comments';
    $active_child = 'showcomments';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.comments.search') }}" method="GET" style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با متن نظر" value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center table-responsive-lg">
            <thead>
            <tr>
                <th scope="col">تعداد</th>
                <th scope="col">نام کاربری کاربر</th>
                <th scope="col">عنوان محصول | مقاله | خبر</th>
                <th scope="col">متن نظر</th>
                <th scope="col">وضعیت نظر</th>
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $key => $comment)
                <tr>
                    <th>
                        {{ $comments->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $comment->user->username }}
                    </td>
                    <td>
                        @if($comment->product_id != null)
                            <a href="{{ route('admin.products.show' , ['product' => $comment->product->slug]) }}">
                                {{ $comment->product->name }}
                            </a>
                        @elseif($comment->article_id != null)
                            <a href="{{ route('admin.articles.show' , ['article' => $comment->article->slug]) }}">
                                {{ $comment->article->title }}
                            </a>
                        @else
                            <a href="{{ route('admin.news.show' , ['news' => $comment->news->slug]) }}">
                                {{ $comment->news->title }}
                            </a>
                        @endif
                    </td>
                    <td>
                        {{ $comment->text }}
                    </td>
                    <td>
                        <span class="badge {{ $comment->getRawOriginal('approved') ?  'badge-success' : 'badge-secondary' }}">
                            {{$comment->approved}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.comments.show' , [$comment->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <form action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger m-1" type="submit">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $comments->links() }}
    </div>
@endsection
