@extends('admin.layout.master')
@section('title')
    ویرایش پلتفرم: {{$platform->name}}
@endsection
@php
    $active_parent = 'platforms';
    $active_child = 'makeplatform'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.platforms.update' , ['platform' => $platform->slug]) }}" method="POST" class="row"
              enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ $platform->name }}">
                                <input type="hidden" name="id" id="id" class="form-control" value="{{ $platform->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $platform->getRawOriginal('is_active') ? 'selected' : '' }}>
                                        فعال
                                    </option>
                                    <option value="0" {{ $platform->getRawOriginal('is_active') ? '' : 'selected' }}>
                                        غیرفعال
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش تصویر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <img class="card-img"
                                     src="{{ url(env('CATEGORY_IMAGE_PATH')) . '/' . $platform->image }}"
                                     alt="{{ $platform->name }}-image">
                            </div>
                            <div class="col-6" style="display: flex;align-items: center">
                                <div class="custom-file col-12 m-1">
                                    <input type="file" name="image" id="image" class="form-control custom-control-input"
                                           lang="fa">
                                    <label for="image" class="custom-file-label">تصویر</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.platforms.index') }}" class="btn btn-danger w-100"
                                   type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('#image').change(function () {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })
    </script>
@endsection
