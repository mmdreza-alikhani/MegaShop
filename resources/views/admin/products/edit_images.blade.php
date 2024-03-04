@extends('admin.layouts.master')
@section('title')
    ویرایش تصاویر محصول:  {{$product->name}}
@endsection
@php
    $active_parent = 'products';
    $active_child = ''
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>
                        تصویر اصلی:
                    </h5>
                </div>
                <div class="col-12 col-md-3 mb-5">
                    <div class="card">
                        <img class="card-img-top" src="{{ url(env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH')) . '/' . $product->primary_image }}" alt="{{ $product->name }}-primary_image">
                    </div>
                </div>
            </div>

        <hr>
            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>
                        دیگر تصاویر:
                    </h5>
                </div>
                @foreach($product->images as $image)
                    <div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top" src="{{ url(env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH')) . '/' . $image->image }}" alt="{{ $product->name }}-other_images">
                            <hr>
                            <div class="card-body text-center">
                                <form action="{{ route('admin.products.images.destroy', ['product' => $product->slug]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{{ $image->id }}}">
                                    <input type="hidden" name="image_name" value="{{{ $image->image }}}">
                                    <button class="btn btn-danger mb-3" type="submit">
                                        حذف
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.images.set_primary', ['product' => $product->id]) }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{{ $image->id }}}">
                                    <button class="btn btn-info mb-3" type="submit">
                                        انتخاب بعنوان تصویر اصلی
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        <hr>
            <form action="{{ route('admin.products.images.add', ['product' => $product->slug]) }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary">
                                افزودن تصویر
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="custom-file col-12 m-1">
                                        <input type="file" name="primary_img" id="primary_img" class="form-control custom-control-input" lang="fa">
                                        <label for="primary_img" class="custom-file-label">تصویر اصلی</label>
                                    </div>
                                    <div class="custom-file col-12 m-1">
                                        <input type="file" name="other_imgs[]" id="other_imgs" class="form-control custom-control-input" lang="fa" multiple>
                                        <label for="other_imgs" class="custom-file-label">دیگر تصاویر</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-header bg-primary">
                                انتشار
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-primary w-100" type="submit" name="submit">افزودن</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-danger w-100" type="cancel">بازگشت</a>
                                    </div>
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
        $('#primary_img').change(function() {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })

        $('#other_imgs').change(function() {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })
    </script>
@endsection
