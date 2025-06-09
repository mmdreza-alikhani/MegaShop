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
                <div class="c-grey text-center my-auto">
                    <button data-target="#createUserModal" data-toggle="modal" type="button" class="btn f-primary btn-block fnt-xxs text-center">
                        ایجاد کاربر
                    </button>
                </div>
            </div>
            <div class="modal w-lg fade light rtl" id="createUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div class="modal-content card shade">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    ایجاد کاربر جدید
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @include('admin.layout.errors', ['errors' => $errors->store])
                                <div class="row">
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="username">نام کاربری:*</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                               value="{{ old('username') }}" required>
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="first_name">نام:</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                               value="{{ old('first_name') }}">
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="last_name">نام خانوادگی:</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                               value="{{ old('last_name') }}">
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="email">ایمیل:</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                               value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="password">رمز عبور:</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                               value="{{ old('password') }}" required>
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label for="phone_number">شماره تلفن:</label>
                                        <div class="input-group-prepend">
                                            <input type="tel" name="phone_number" id="phone_number" minlength="10"
                                                   maxlength="10" class="form-control" value="{{ old('phone_number') }}">
                                            <div class="input-group-text">98+</div>
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
{{--                                                <img src="{{ Str::contains($user->avatar, 'https://') ? $user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $user->avatar }}"--}}
{{--                                                     alt="{{ $user->username }}-image" id="output" width="100" height="100"/>--}}
                                                {{ '0' . $user->phone_number ?: 'شماره تلفن ثبت نشده!' }}
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
                                                        <a href="#" class="dropdown-item" data-target="#showUserModal-{{ $user->id }}" data-toggle="modal">نمایش</a>
                                                        <a href="#" class="dropdown-item" data-target="#editUserModal-{{ $user->id }}" data-toggle="modal">ویرایش</a>
                                                        <a href="#" class="dropdown-item" data-target="#deleteUserModal-{{ $user->id }}" data-toggle="modal">حذف</a>
                                                    </div>
                                                </div>
                                                <div class="modal w-lg fade light rtl" id="showUserModal-{{ $user->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content card shade">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    نمایش کاربر: {{ $user->username }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="username-{{ $user->id }}-show">نام کاربری:</label>
                                                                        <input id="username-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->username }}" disabled>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="first_name-{{ $user->id }}-show">نام:</label>
                                                                        <input id="first_name-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="last_name-{{ $user->id }}-show">نام خانوادگی:</label>
                                                                        <input id="last_name-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="email-{{ $user->id }}-show">ایمیل:
                                                                            @if($user->email_verified_at == null)
                                                                                <i class="fa fa-times-circle text-danger"></i>
                                                                            @else
                                                                                <i class="fa fa-check-circle text-success"></i>
                                                                            @endif
                                                                        </label>
                                                                        <input id="email-{{ $user->id }}-show" type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="phone_number-{{ $user->id }}-show">شماره تلفن:</label>
                                                                        <div class="input-group">
                                                                            <input id="phone_number-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->phone_number }}" disabled>
                                                                            <div class="input-group-append">
                                                                                <div class="input-group-text">+98</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-4">
                                                                        <label for="created_at-{{ $user->id }}-show">تاریخ ایجاد حساب:</label>
                                                                        <input id="created_at-{{ $user->id }}-show" type="text" class="form-control" value="{{ verta($user->created_at) }}" disabled>
                                                                    </div>
                                                                    <div class="form-group col-12 col-lg-12">
                                                                        <button class="btn btn-block text-right border border-info" type="button"
                                                                                data-toggle="collapse" data-target="#permissionsCollapse">
                                                                            دسترسی به مجوز ها
                                                                        </button>
                                                                        <div id="permissionsCollapse" class="collapse">
                                                                            <div class="row">
                                                                                @foreach($permissions as $permission)
                                                                                    <div class="col-lg-2 col-6 p-2 d-flex align-items-center">
                                                                                        <input type="checkbox"
                                                                                               value="{{ $permission->name }}"
                                                                                               id="{{ $permission->name . '-check' }}"
                                                                                               class="mr-2"
                                                                                               disabled
                                                                                            {{ in_array($permission->id, $user->getAllPermissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal w-lg fade light rtl" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ route('admin.users.update', ['user' => $user->id]) }}">
                                                            @method('put')
                                                            @csrf
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        ویرایش کاربر: {{ $user->username }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @include('admin.layout.errors', ['errors' => $errors->update])
                                                                    <div class="row">
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="username-{{ $user->id }}">نام کاربری:*</label>
                                                                            <input type="text" name="username" id="username-{{ $user->id }}" class="form-control"
                                                                                   value="{{ $user->username }}" required>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="first_name-{{ $user->id }}">نام:</label>
                                                                            <input type="text" name="first_name" id="first_name-{{ $user->id }}" class="form-control"
                                                                                   value="{{ $user->first_name }}">
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="last_name-{{ $user->id }}">نام خانوادگی:</label>
                                                                            <input type="text" name="last_name" id="last_name-{{ $user->id }}" class="form-control"
                                                                                   value="{{ $user->last_name }}">
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="email-{{ $user->id }}">ایمیل:</label>
                                                                            <input type="text" name="email" id="email-{{ $user->id }}" class="form-control"
                                                                                   value="{{ $user->email }}" required>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="phone_number-{{ $user->id }}">شماره تلفن:</label>
                                                                            <div class="input-group-prepend">
                                                                                <input type="tel" name="phone_number" id="phone_number-{{ $user->id }}" size="10" class="form-control" value="{{ $user->phone_number }}">
                                                                                <div class="input-group-text">98+</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="role">نقش کاربری:</label>
                                                                            <select class="form-control" id="role-{{ $user->id }}" name="role">
                                                                                <option></option>
                                                                                @foreach($roles as $role)
                                                                                    <option value="{{ $role->name }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-12">
                                                                            <button class="btn btn-block text-right border border-info" type="button"
                                                                                    data-toggle="collapse" data-target="#permissionsCollapse">
                                                                                دسترسی به مجوز ها
                                                                            </button>
                                                                            <div id="permissionsCollapse" class="collapse">
                                                                                <div class="row">
                                                                                    @foreach($permissions as $permission)
                                                                                        <div class="col-lg-2 col-6 p-2 d-flex align-items-center">
                                                                                            <input type="checkbox"
                                                                                                   value="{{ $permission->name }}"
                                                                                                   name="{{ $permission->name }}"
                                                                                                   id="{{ $permission->name . '-check' }}"
                                                                                                   class="mr-2"
                                                                                                {{ in_array($permission->id, $user->getAllPermissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                                                                    <button type="submit" class="btn main f-main">ویرایش</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @if(count($errors->update) > 0)
                                                    <script>
                                                        $(function() {
                                                            $('#editUserModal-{{ session()->get('user_id') }}').modal({
                                                                show: true
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                                <div class="modal w-lg fade light rtl" id="deleteUserModal-{{ $user->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ route('admin.users.store') }}">
                                                            @csrf
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        ایجاد کاربر جدید
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                    <button type="submit" class="btn main f-main">ایجاد</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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

@section('scripts')
    <script>
        @if(count($errors->store) > 0)
        $(function() {
            $('#createUserModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
