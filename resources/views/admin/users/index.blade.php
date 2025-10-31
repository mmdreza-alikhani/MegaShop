@extends('admin.layout.master')

@section('title')
    لیست کاربران
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
                                        <span class="lite-text">کاربران</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">کاربران</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex row m-1 justify-content-between">
                @can('users-create')
                    <div class="c-grey text-center my-auto">
                        <button data-target="#createUserModal" data-toggle="modal" type="button" class="btn f-primary btn-block fnt-xxs text-center">
                            ایجاد کاربر
                        </button>
                    </div>
                    @include('admin.users.partials.create-modal')
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
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">کاربران</h5>
                            <hr/>
                            @if($users->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج کاربری وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">نام کاربری</th>
                                        <th scope="col">شماره تلفن</th>
                                        <th scope="col">ایمیل</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <th>
                                                {{ $users->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $user->username }}
                                            </td>
                                            <td>
                                                {{ $user->formatted_phone }}
                                            </td>
                                            <td>
                                                {{ $user->email }}
                                                @if($user->email_verified_at == null)
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                @else
                                                    <i class="fa fa-check-circle text-success"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $user->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $user->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        @can('users-edit')
                                                            <button class="dropdown-item" data-target="#showUserModal-{{ $user->id }}" data-toggle="modal">نمایش</button>
                                                        @endcan
                                                        @can('users-edit')
                                                            <button class="dropdown-item" data-target="#editUserModal-{{ $user->id }}" data-toggle="modal">ویرایش</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                                @can('users-index')
                                                    @include('admin.users.partials.show-modal')
                                                @endcan
                                                @can('users-edit')
                                                    @include('admin.users.partials.edit-modal')
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $users->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
