@extends('admin.layout.master')

@php
    $title = 'نظرات';
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
            <div class="d-flex m-1 align-items-center justify-content-between">
                <form action="{{ route('admin.comments.search') }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword" required>
                    </div>
                </form>
            </div>
            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $title }}</h5>
                            <hr/>
                            @if($comments->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج نظری وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">کاربر</th>
                                        <th scope="col">نوع محتوا</th>
                                        <th scope="col">عنوان محتوا</th>
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
                                                {{ $comment->commentable }}
                                            </td>
                                            <td>
                                                {{ $comment->commentable }}برسسی
                                            </td>
                                            <td>
                                                <span class="badge {{ $comment->getRawOriginal('approved') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $comment->approved }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <button data-target="#reviewCommentModal-{{ $comment->id }}" data-toggle="modal" type="button" class="dropdown-item">بررسی</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="reviewCommentModal-{{ $comment->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post" action="{{ route('admin.comments.update', ['comment' => $comment->id]) }}">
                                                                @method('put')
                                                                @csrf
                                                                <div class="modal-content card shade">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            ویرایش برند: {{ $comment->title }}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @include('admin.layout.errors', ['errors' => $errors->update])
                                                                        <div class="row">
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="title-{{ $comment->id }}">عنوان:*</label>
                                                                                <input type="text" name="title" id="title-{{ $comment->id }}" class="form-control"
                                                                                       value="{{ $comment->title }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="is_active-{{ $comment->id }}">وضعیت:*</label>
                                                                                <select class="form-control" id="is_active-{{ $comment->id }}" name="is_active" required>
                                                                                    <option value="1" {{ $comment->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                                                    <option value="0" {{ $comment->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                        <button type="submit" class="btn main f-main">ویرایش</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @if(count($errors->update) > 0)
                                                        <script>
                                                            $(function() {
                                                                $('#editCommentModal-{{ session()->get('comment_id') }}').modal({
                                                                    show: true
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="modal w-lg fade justify rtl" id="deleteCommentModal-{{ $comment->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف برند: {{ $comment->title }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    آیا از این عملیات مطمئن هستید؟
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn outlined o-danger c-danger"
                                                                            data-dismiss="modal">بستن</button>
                                                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" style="display: inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn f-main">حذف</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $comments->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
