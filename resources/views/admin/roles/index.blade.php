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
                <div class="c-grey text-center my-auto">
                    <button data-target="#createRoleModal" data-toggle="modal" type="button" class="btn f-primary btn-block fnt-xxs text-center">
                        ایجاد نقش
                    </button>
                </div>
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
                                                        <a href="#" class="dropdown-item" data-target="#editRoleModal-{{ $role->id }}" data-toggle="modal">ویرایش</a>
                                                        <a href="#" class="dropdown-item" data-target="#deleteRoleModal-{{ $role->id }}" data-toggle="modal">حذف</a>
                                                    </div>
                                                </div>
                                                <div class="modal w-lg fade light rtl" id="editRoleModal-{{ $role->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ route('admin.roles.update', ['role' => $role->id]) }}">
                                                            @method('put')
                                                            @csrf
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        ویرایش نقش: {{ $role->name }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @include('admin.layout.errors', ['errors' => $errors->update])
                                                                    <div class="row">
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="name-{{ $role->id }}">نام:</label>
                                                                            <input type="text" name="name" id="name-{{ $role->id }}" class="form-control"
                                                                                   value="{{ $role->name }}">
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="display_name-{{ $role->id }}">نام نمایشی:</label>
                                                                            <input type="text" name="display_name" id="display_name-{{ $role->id }}" class="form-control"
                                                                                   value="{{ $role->display_name }}">
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
                                                                                                {{ in_array($permission->id, $role->permissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                                                            $('#editRoleModal-{{ session()->get('role_id') }}').modal({
                                                                show: true
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                                <div class="modal w-lg fade light rtl" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ route('admin.roles.update', ['role' => $role->id]) }}">
                                                            @method('put')
                                                            @csrf
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        ویرایش نقش: {{ $role->name }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @include('admin.layout.errors', ['errors' => $errors->update])
                                                                    <div class="row">
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="name-{{ $role->id }}">نام:</label>
                                                                            <input type="text" name="name" id="name-{{ $role->id }}" class="form-control"
                                                                                   value="{{ $role->name }}">
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="display_name-{{ $role->id }}">نام نمایشی:</label>
                                                                            <input type="text" name="display_name" id="display_name-{{ $role->id }}" class="form-control"
                                                                                   value="{{ $role->display_name }}">
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
                                                                                                {{ in_array($permission->id, $role->permissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                                                <div class="modal w-lg fade justify rtl" id="deleteRoleModal-{{ $role->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">حذف نقش: {{ $role->name }}</h5>
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
                                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn f-main">حذف</button>
                                                                </form>
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
                            {{ $roles->links('vendor.pagination.bootstrap-4') }}
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
            $('#createRoleModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
