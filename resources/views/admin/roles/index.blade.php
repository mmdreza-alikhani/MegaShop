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
                                                    </div>
                                                </div>
                                                @can('users-index')
                                                    @include('admin.roles.partials.show-modal')
                                                @endcan
                                                @can('users-edit')
                                                    @include('admin.roles.partials.edit-modal')
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
