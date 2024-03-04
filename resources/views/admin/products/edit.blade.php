@extends('admin.layouts.master')
@section('title')
    ویرایش مشخصات محصول:  {{$product->name}}
@endsection
@php
    $active_parent = 'products';
    $active_child = ''
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.products.update' , ['product' => $product->slug]) }}" method="POST" class="row" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-3">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}">
                                <input type="hidden" name="id" id="id" class="form-control" value="{{ $product->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="brandSelect">نام برند*</label>
                                <select id="brandSelect" class="form-control" name="brand_id" data-live-search="true">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $brand->id === $product->brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="platform_id">پلتفرم*</label>
                                <select id="platform_id" class="form-control" name="platform_id" data-live-search="true">
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" @php if($product->platform && $platform->id === $product->platform->id) { $notHavePlatform = false; echo 'selected'; }else{ $notHavePlatform = true; } @endphp >{{ $platform->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="is_active">وضعیت*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $product->getRawOriginal('is_active') ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ $product->getRawOriginal('is_active') ? '' : 'selected' }}>غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="tagSelect">تگ ها*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple data-live-search="true">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            {{ in_array($tag->id , $product->tags()->pluck('id')->toArray() ) ? 'selected' : '' }}
                                        >{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="summernote">توضیحات*</label>
                                <textarea id="summernote" type="text" name="description" class="form-control">{{ $product->description }}</textarea>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="delivery_amount">هزینه ارسال*</label>
                                <input type="text" name="delivery_amount" id="delivery_amount" value="{{ $product->delivery_amount }}" class="form-control">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول</label>
                                <input type="text" name="delivery_amount_per_product" id="delivery_amount_per_product" value="{{ $product->delivery_amount_per_product }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-1">
                <div class="col-12 col-lg-6">
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
                                        {{ $variation->value }}*
                                    </p>
                                    <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample-{{ $variation->id }}" aria-expanded="false" aria-controls="collapseExample">
                                        نمایش
                                    </button>
                                    <div class="collapse mt-2" id="collapseExample-{{ $variation->id }}">
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label for="price">قیمت*</label>
                                                <input id="price" type="text" name="variation_values[{{ $variation->id }}][price]" value="{{ $variation->price }}" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="quantity">تعداد*</label>
                                                <input id="quantity" type="text" name="variation_values[{{ $variation->id }}][quantity]" value="{{ $variation->quantity }}" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="sku">شناسه عددی</label>
                                                <input id="sku" type="text" name="variation_values[{{ $variation->id }}][sku]" value="{{ $variation->sku }}" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="sale_price">قیمت حراجی</label>
                                                <input id="sale_price" type="text" name="variation_values[{{ $variation->id }}][sale_price]" value="{{ $variation->sale_price }}" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="date_on_sale_from">تاریخ شروع حراجی</label>
                                                <input data-jdp name="variation_values[{{ $variation->id }}][date_on_sale_from]" id="date_on_sale_from" value="{{ $variation->date_on_sale_from === null ? null : verta($variation->date_on_sale_from) }}" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="date_on_sale_to">تاریخ پایان حراجی</label>
                                                <input data-jdp name="variation_values[{{ $variation->id }}][date_on_sale_to]" id="date_on_sale_to" value="{{ $variation->date_on_sale_to === null ? null : verta($variation->date_on_sale_to) }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            ویژگی ها
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($productAttributes as $productAttribute)
                                    <div class="form-group col-12 col-lg-6">
                                        <label for="{{ $productAttribute->value }}">{{ $productAttribute->attribute->name }}*</label>
                                        <input id="{{ $productAttribute->value }}" type="text" name="attribute_values[{{ $productAttribute->id }}]" value="{{ $productAttribute->value }}" class="form-control">
                                    </div>
                                @endforeach
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
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-danger w-100" type="cancel">بازگشت</a>
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

        $('#tagSelect').selectpicker({
            'title' : 'انتخاب تگ'
        });

        $('#platformSelect').selectpicker({
            'title' : 'انتخاب پلتفرم'
        });

        $('#brandSelect').selectpicker({
            'title' : 'انتخاب برند'
        });

        $(document).ready(function() {
            $('#summernote').summernote();
        });

        jalaliDatepicker.startWatch({ time: true });
    </script>
@endsection
