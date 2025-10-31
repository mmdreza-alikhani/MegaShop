@extends('admin.layout.master')

@section('title')
    دسترسی ها
@endsection

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
                                    <span class="lite-text">دسترسی ها</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active">دسترسی ها</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex row m-1 justify-content-between">
            @can('users-create')
                <div class="c-grey text-center my-auto">
                    <button data-target="#createPermissionModal" data-toggle="modal" type="button" class="btn f-primary btn-block fnt-xxs text-center">
                        ایجاد دسترسی
                    </button>
                </div>
                @include('admin.permissions.partials.create-modal')
            @endcan
            <form action="#" method="GET" class="m-0 p-0">
                <div class="input-group">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary c-primary" type="submit">
                            <i class="fab fas fa-search"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="جستجو"
                           style="width: 300px"
                           value="{{ request('q') }}" name="q" required>
                </div>
            </form>
        </div>
        <div class="modal w-lg fade light rtl" id="createPermissionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('admin.permissions.store') }}">
                    @csrf
                    <div class="modal-content card shade">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                ایجاد دسترسی جدید
                            </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @include('admin.layout.errors', ['errors' => $errors->store])
                            <div class="row">
                                <div class="form-group col-12 col-lg-6">
                                    <label for="name">نام:*</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label for="display_name">نام نمایشی:*</label>
                                    <input type="text" name="display_name" id="display_name" class="form-control"
                                           value="{{ old('display_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                            <button type="submit" class="btn main f-main">ایجاد</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row m-1">
            <div class="col-xs-1 col-sm-1 col-md-12 col-lg-12 p-2">
                <div class="card shade h-100">
                    <div class="card-body">
                        <h5 class="card-title">دسترسی ها</h5>
                        <hr/>
                        @if($permissions->isEmpty())
                            <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                هیج دسترسی وجود ندارد!
                            </div>
                        @else
                            <table class="table table-striped justify-content-around col-12">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">نام نمایشی</th>
                                    <th scope="col">نام</th>
                                    <th scope="col">تنظیمات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $key => $permission)
                                    <tr>
                                        <th scope="row" class="col-1">{{ $permissions->firstItem() + $key }}</th>
                                        <td>
                                            {{ $permission->display_name }}
                                        </td>
                                        <td>
                                            {{ $permission->name }}
                                        </td>
                                        <td class="row">
                                            <div class="dropdown base show">
                                                <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    @can('users-edit')
                                                        <button class="dropdown-item" data-target="#editPermissionModal-{{ $permission->id }}" data-toggle="modal">ویرایش</button>
                                                    @endcan
                                                </div>
                                            </div>
                                            @can('users-edit')
                                                @include('admin.permissions.partials.edit-modal')
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                        {{ $permissions->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')
    <script>
        @if($errors->update->any() && session('permission_id'))
            $(function() {
                $('#editPermissionModal-{{ session('permission_id') }}').modal({
                    show: true
                });
            });
        @endif
    </script>
@endsection
