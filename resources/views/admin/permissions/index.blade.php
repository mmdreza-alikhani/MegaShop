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
            <div class="c-grey text-center  my-auto">
                <button data-target="#createPermissionModal" data-toggle="modal" type="button" class="btn shade f-primary btn-block fnt-xxs text-center">
                    ایجاد دسترسی
                </button>
            </div>
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
                            @include('admin.layout.errors', ['errors' => $errors->createPermission])
                            <p>نام: *</p>
                            <div class="input-group mb-3 col-md-6 col-12">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required/>
                            </div>
                            <p>نام نمایشی: *</p>
                            <div class="input-group mb-3 col-md-6 col-12">
                                <input type="text" class="form-control" name="display_name" value="{{ old('display_name') }}" required/>
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
                                            <div class="c-grey text-center mx-2">
                                                <a href="{{ route('admin.permissions.edit', ['permission' => $permission]) }}" class="btn main f-main btn-block fnt-xxs">
                                                    ویرایش
                                                </a>
                                            </div>
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
        @if(count($errors->createPermission) > 0)
        $(function() {
            $('#createPermissionModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
