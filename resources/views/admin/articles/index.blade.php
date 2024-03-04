@extends('admin.layouts.master')
@section('title')
    لیست مقالات
@endsection
@section('button')
    <a href="{{ route('admin.articles.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن مقاله جدید
    </a>
@endsection
@php
    $active_parent = 'articles';
    $active_child = 'showarticles';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.articles.search') }}" method="GET" style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام مقاله" value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">تعداد</th>
                <th scope="col">نام</th>
                <th scope="col">وضعیت</th>
                <th scope="col">نویسنده</th>
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($articles as $key => $article)
                <tr>
                    <th>
                        {{ $articles->firstItem() + $key }}
                    </th>
                    <td>
                        <a href="{{ route('admin.articles.show' , ['article' => $article->id]) }}">
                            {{ $article->title }}
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $article->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{$article->is_active}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show' , [$article->user_id]) }}">
                            {{ $article->author->username }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.articles.show' , [$article->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.articles.edit' , [$article->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $articles->links() }}
    </div>
@endsection
