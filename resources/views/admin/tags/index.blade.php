@extends('admin.layout.master')

@php
    $title = 'برچسب ها';
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
                <div class="c-grey text-center">
                    <button data-target="#createTagModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                        ایجاد برچسب
                    </button>
                </div>
                <div class="modal w-lg fade light rtl" id="createTagModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('admin.tags.store') }}">
                            @csrf
                            <div class="modal-content card shade">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        ایجاد برچسب جدید
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->store])
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="title">
                                                نام:*</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                   value="{{ old('title') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn main f-main">ایجاد</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{ route('admin.tags.search') }}" method="GET" class="m-0 p-0">
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
                            @if($tags->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج برچسب وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tags as $key => $tag)
                                        <tr>
                                            <th>
                                                {{ $tags->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $tag->title }}
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <button data-target="#editTagModal-{{ $tag->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        <button data-target="#deleteTagModal-{{ $tag->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="editTagModal-{{ $tag->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post" action="{{ route('admin.tags.update', ['tag' => $tag->id]) }}">
                                                                @method('put')
                                                                @csrf
                                                                <div class="modal-content card shade">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            ویرایش برچسب: {{ $tag->title }}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @include('admin.layout.errors', ['errors' => $errors->update])
                                                                        <div class="row">
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="title-{{ $tag->id }}">عنوان:*</label>
                                                                                <input type="text" name="title" id="title-{{ $tag->id }}" class="form-control"
                                                                                       value="{{ $tag->title }}" required>
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
                                                                $('#editTagModal-{{ session()->get('tag_id') }}').modal({
                                                                    show: true
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="modal w-lg fade justify rtl" id="deleteTagModal-{{ $tag->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف برچسب: {{ $tag->title }}</h5>
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
                                                                    <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display: inline;">
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
                            {{ $tags->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        @if(count($errors->store) > 0)
        $(function() {
            $('#createTagModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
