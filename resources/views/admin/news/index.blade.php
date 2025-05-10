@extends('admin.layout.master')
@section('title')
    لیست اخبار
@endsection
@section('button')
    <a href="{{ route('admin.news.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن خبر جدید
    </a>
@endsection
@php
    $active_parent = 'news';
    $active_child = 'shownews';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.news.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام خبر"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
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
            @foreach($news as $key => $new_s)
                <tr>
                    <th>
                        {{ $news->firstItem() + $key }}
                    </th>
                    <td>
                        <a href="{{ route('admin.news.show' , ['news' => $new_s->id]) }}">
                            {{ $new_s->title }}
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $new_s->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                            {{ $new_s->is_active }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show' , [$new_s->user_id]) }}">
                            {{ $new_s->author->username }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.news.show' , [$new_s->id]) }}" class="btn btn-success m-1">
                            نمایش
                        </a>
                        <a href="{{ route('admin.news.edit' , [$new_s->id]) }}" class="btn btn-info m-1">
                            ویرایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $news->links() }}
    </div>
@endsection
