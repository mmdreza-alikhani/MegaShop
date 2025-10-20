@extends('admin.layout.master')

@section('title')
    نقش ها
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
                                        <span class="lite-text">نقش ها</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">نقش ها</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex row m-1 justify-content-between">
                @can('users-create')
                    <div class="c-grey text-center my-auto">
                        <button data-target="#createRoleModal" data-toggle="modal" type="button" class="btn f-primary btn-block fnt-xxs text-center">
                            ایجاد نقش
                        </button>
                    </div>
                    @include('admin.roles.partials.create-modal')
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
            <div class="modal w-lg fade light rtl" id="createRoleModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('admin.roles.store') }}">
                        @csrf
                        <div class="modal-content card shade">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    ایجاد نقش جدید
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
                                    <div class="form-group col-12 col-lg-12">
                                        <button class="btn btn-block text-right border border-info" type="button"
                                                data-toggle="collapse" data-target="#permissionsCollapse">
                                            مجوز ها
                                        </button>
                                        <div id="permissionsCollapse" class="collapse">
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                    <div class="col-lg-2 col-6 p-2 d-flex align-items-center">
                                                        <input type="checkbox"
                                                               value="{{ $permission->name }}"
                                                               name="{{ $permission->name }}"
                                                               id="{{ $permission->name . '-check' }}"
                                                               class="mr-2">
                                                        <label for="{{ $permission->name . '-check' }}">
                                                            {{ $permission->display_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
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
            <div class="row m-1">
                <div class="col-xs-1 col-sm-1 col-md-12 col-lg-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">نقش ها</h5>
                            <hr/>
                            @if($roles->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج نقشی وجود ندارد!
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
                                    @foreach($roles as $key => $role)
                                        <tr>
                                            <th>
                                                {{ $roles->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $role->display_name }}
                                            </td>
                                            <td>
                                                {{ $role->name }}
                                            </td>
                                            <td class="row">
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        @can('users-index')
                                                            <button class="dropdown-item" data-target="#showRoleModal-{{ $role->id }}" data-toggle="modal">نمایش</button>
                                                        @endcan
                                                        @can('users-edit')
                                                            <button class="dropdown-item" data-target="#editRoleModal-{{ $role->id }}" data-toggle="modal">ویرایش</button>
                                                        @endcan
                                                        @can('users-delete')
                                                            <button class="dropdown-item" data-target="#deleteRoleModal-{{ $role->id }}" data-toggle="modal">حذف</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                                @can('users-index')
                                                    @include('admin.roles.partials.show-modal')
                                                @endcan
                                                @can('users-edit')
                                                    @include('admin.roles.partials.edit-modal')
                                                @endcan
                                                @can('users-delete')
                                                    @include('admin.roles.partials.delete-modal')
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $roles->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
