@extends('admin.layout.master')
@section('title')
    محصول : {{ $product->name }}
@endsection
@php
    $active_parent = '$products';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    مشخصات محصول
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-3">
                            <label>نام</label>
                            <input type="text" value="{{ $product->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>نام برند</label>
                            <input type="text" value="{{ $product->brand->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>پلتفرم</label>
                            <input type="text" value="{{ $product->platform ? $product->platform->name : '' }}"
                                   class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>دسته بندی</label>
                            <input type="text" value="{{ $product->category->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>تگ ها</label>
                            <div class="form-control" style="background-color: #e9ecef">
                                @foreach($product->tags as $tag)
                                    {{ $tag->name }}{{ $loop->last ? '' : ',' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $product->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($product->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($product->updated_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>توضیحات</label>
                            <textarea type="text" class="form-control" disabled
                                      rows="6">{{ $product->description }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>هزینه ارسال</label>
                            <input type="text" value="{{ $product->delivery_amount }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>هزینه ارسال به ازای هر محصول</label>
                            <input type="text" value="{{ $product->delivery_amount_per_product }}" class="form-control"
                                   disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-1">
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        متغیر
                    </div>
                    <div class="card-body">
                        @foreach($productVariations as $variation)
                            <div class="row my-2">
                                <p>
                                    قیمت و موجودی برای متغیر:
                                </p>
                                <p class="font-weight-bold mx-2">
                                    {{ $variation->value }}
                                </p>
                                <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseExample-{{ $variation->id }}" aria-expanded="false"
                                        aria-controls="collapseExample">
                                    نمایش
                                </button>
                                <div class="collapse mt-2" id="collapseExample-{{ $variation->id }}">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>قیمت</label>
                                            <input type="text" value="{{ $variation->price }}" class="form-control"
                                                   disabled>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>تعداد</label>
                                            <input type="text" value="{{ $variation->quantity }}" class="form-control"
                                                   disabled>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>شناسه عددی</label>
                                            <input type="text" value="{{ $variation->sku }}" class="form-control"
                                                   disabled>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>قیمت حراجی</label>
                                            <input type="text" value="{{ $variation->sale_price }}" class="form-control"
                                                   disabled>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>تاریخ شروع حراجی</label>
                                            <input type="text"
                                                   value="{{ $variation->date_on_sale_from === null ? null : verta($variation->date_on_sale_from) }}"
                                                   class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>تاریخ پایان حراجی</label>
                                            <input type="text"
                                                   value="{{ $variation->date_on_sale_to === null ? null : verta($variation->date_on_sale_to) }}"
                                                   class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویژگی ها
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($productAttributes as $productAttribute)
                                <div class="form-group col-12 col-lg-6">
                                    <label>{{ $productAttribute->attribute->name }}</label>
                                    <input type="text" value="{{ $productAttribute->value }}" class="form-control"
                                           disabled>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        تصاویر محصول:
                    </div>

                    <label class="m-2">
                        تصویر اصلی
                    </label>
                    <img class="card-img w-25 p-3"
                         src="{{ url(env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH')) . '/' . $product->primary_image }}"
                         alt="{{ $product->name }}-primary_image">

                    <label class="m-2">
                        دیگر تصاویر
                    </label>
                    <div class="row">
                        @foreach($product->images as $image)
                            <img class="card-img w-25 p-3"
                                 src="{{ url(env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH')) . '/' . $image->image }}"
                                 alt="{{ $product->name }}-other_images">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-danger mb-2">بازگشت</a>
    </div>
@endsection
