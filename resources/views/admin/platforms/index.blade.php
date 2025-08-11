@extends('admin.layout.master')

@php
    $title = 'پلتفرم ها';
@endphp

@section('title', $title)

@section('styles')
    <style>
        /* Make the wrapper blend in */
        .imageInsertDiv {
            background-color: transparent !important;
            border: none;
            padding: 0;
        }

        /* Remove button background and border */
        .imageInsertBtn {
            background-color: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            color: inherit; /* Optional: inherit text color */
        }
    </style>
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
                    <button data-target="#createPlatformModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                        ایجاد پلتفرم
                    </button>
                </div>
                <div class="modal w-lg fade light rtl" id="createPlatformModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('admin.platforms.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content card shade">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        ایجاد پلتفرم جدید
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->store])
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="title">نام:*</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                   value="{{ old('title') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="is_active">وضعیت:*</label>
                                            <select class="form-control" id="is_active" name="is_active" required>
                                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-12">
                                            <label class="d-block mb-2 text-right" for="image">تصویر اصلی</label>
                                            <div class="d-flex flex-row-reverse align-items-center border rounded p-2 imageInsertDiv" style="background-color: #f8f9fa;">
                                                <span class="btn f-primary ml-2 px-2 imageInsertBtn" onclick="document.getElementById('image').click();">
                                                    انتخاب فایل
                                                </span>
                                                <span id="image-file-name" class="text-muted flex-grow-1 text-right">
                                                    هیچ فایلی انتخاب نشده
                                                </span>
                                                <input type="file" id="image" name="image" class="d-none" lang="fa" required>
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
            </div>
            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $title }}</h5>
                            <hr/>
                            @if($platforms->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج پلتفرمی وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($platforms as $key => $platform)
                                        <tr>
                                            <th>
                                                {{ $platforms->firstItem() + $key }}
                                            </th>
                                            <td>
                                                {{ $platform->title }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $platform->getRawOriginal('is_active') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{ $platform->is_active }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <button data-target="#editPlatformModal-{{ $platform->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        <button data-target="#deletePlatformModal-{{ $platform->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="editPlatformModal-{{ $platform->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post" action="{{ route('admin.platforms.update', ['platform' => $platform->id]) }}" enctype="multipart/form-data">
                                                                @method('put')
                                                                @csrf
                                                                <div class="modal-content card shade">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            ویرایش پلتفرم: {{ $platform->title }}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @include('admin.layout.errors', ['errors' => $errors->update])
                                                                        <div class="row">
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="title-{{ $platform->id }}">عنوان:*</label>
                                                                                <input type="text" name="title" id="title-{{ $platform->id }}" class="form-control"
                                                                                       value="{{ $platform->title }}">
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="is_active-{{ $platform->id }}">وضعیت:*</label>
                                                                                <select class="form-control" id="is_active-{{ $platform->id }}" name="is_active">
                                                                                    <option value="1" {{ $platform->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                                                    <option value="0" {{ $platform->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="is_active-{{ $platform->id }}">وضعیت:*</label>
                                                                                <select class="form-control" id="is_active-{{ $platform->id }}" name="is_active">
                                                                                    <option value="1" {{ $platform->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                                                                    <option value="0" {{ $platform->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-12">
                                                                                <label class="d-block mb-2 text-right" for="image-{{ $platform->id }}">تصویر جدید</label>
                                                                                <div class="d-flex flex-row-reverse align-items-center border rounded p-2 imageInsertDiv" style="background-color: #f8f9fa;">
                                                                                    <span class="btn f-primary ml-2 px-2 imageInsertBtn" onclick="document.getElementById('image-{{ $platform->id }}').click();">
                                                                                        انتخاب فایل
                                                                                    </span>
                                                                                    <span id="image-file-name-{{ $platform->id }}" class="text-muted flex-grow-1 text-right">
                                                                                        هیچ فایلی انتخاب نشده
                                                                                    </span>
                                                                                    <input type="file" id="image-{{ $platform->id }}" name="image" class="d-none" lang="fa">
                                                                                </div>
                                                                            </div>
                                                                            <script>
                                                                                const imageInput_{{ $platform->id }} = document.getElementById('image-{{ $platform->id }}');
                                                                                const imageFileNameDisplay_{{ $platform->id }} = document.getElementById('image-file-name-{{ $platform->id }}');

                                                                                imageInput_{{ $platform->id }}.addEventListener('change', function () {
                                                                                    imageFileNameDisplay_{{ $platform->id }}.textContent = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
                                                                                });
                                                                            </script>
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
                                                                $('#editPlatformModal-{{ session()->get('platform_id') }}').modal({
                                                                    show: true
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="modal w-lg fade justify rtl" id="deletePlatformModal-{{ $platform->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف پلتفرم: {{ $platform->title }}</h5>
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
                                                                    <form action="{{ route('admin.platforms.destroy', $platform->id) }}" method="POST" style="display: inline;">
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
                            {{ $platforms->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        const imageInput = document.getElementById('image');
        const imageFileNameDisplay = document.getElementById('image-file-name');

        imageInput.addEventListener('change', function () {
            imageFileNameDisplay.textContent = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
        });

        @if(count($errors->store) > 0)
        $(function() {
            $('#createPlatformModal').modal({
                show: true
            });
        });
        @endif
    </script>
@endsection
