@extends('admin.layout.master')
@section('title' , 'ایجاد محصول')
@php
    $active_parent = 'products';
    $active_child = 'makeproduct'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.products.store') }}" method="POST" class="row" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن محصول
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="brandSelect">برند*</label>
                                <select id="brandSelect" class="form-control" name="brand_id" data-live-search="true">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" selected>فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="tagSelect">تگ ها*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple
                                        data-live-search="true">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="platformSelect">پلتفرم*</label>
                                <select id="platformSelect" class="form-control" name="platform_id"
                                        data-live-search="true">
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="summernote">توضیحات*</label>
                                <textarea id="summernote" type="text" name="description"
                                          class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 ">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن تصویر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="custom-file col-12 m-1">
                                <input type="file" name="primary_img" id="primary_img"
                                       class="form-control custom-control-input" lang="fa">
                                <label for="primary_img" class="custom-file-label">تصویر اصلی</label>
                            </div>
                            <div class="custom-file col-12 m-1">
                                <input type="file" name="other_imgs[]" id="other_imgs"
                                       class="form-control custom-control-input" lang="fa" multiple>
                                <label for="other_imgs" class="custom-file-label">دیگر تصاویر</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        هزینه ارسال
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="delivery_amount">هزینه ارسال(به تومان)</label>
                                <input type="text" name="delivery_amount" id="delivery_amount" class="form-control"
                                       value="{{ old('delivery_amount') }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول(به تومان)</label>
                                <input type="text" name="delivery_amount_per_product" id="delivery_amount_per_product"
                                       class="form-control" value="{{ old('delivery_amount_per_product') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        تنظیم دسته بندی
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4"></div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="categorySelect">دسته بندی*</label>
                                <select id="categorySelect" class="form-control" name="category_id"
                                        data-live-search="true">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}
                                            -{{ $category->parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="attributeContainer">
                            {{--Show Attributes--}}
                            <div id="attributes" class="row"></div>
                            {{--End Show Attributes--}}
                            {{--Show Variation--}}
                            <hr>
                            افزودن قیمت و موجودی برای متغیر
                            <span id="variationName" class="font-weight-bold">

                            </span>:
                            <div id="czContainer">
                                <div id="first">
                                    <div class="recordset">
                                        <div class="row">
                                            <span class="col-12 col-lg-3 my-2">
                                                <label for="value">نام*</label>
                                                <input id="value" type="text" name="variation_values[value][]"
                                                       class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label for="price">قیمت*</label>
                                                <input id="price" type="text" name="variation_values[price][]"
                                                       class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label for="quantity">تعداد*</label>
                                                <input id="quantity" type="text" name="variation_values[quantity][]"
                                                       class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label for="sku">شناسه عددی*</label>
                                                <input id="sku" type="text" name="variation_values[sku][]"
                                                       class="form-control">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- The elements you want repeated must be wrapped in an element with id="recordset" -->
                            {{--End Show Variation--}}
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
                                <button class="btn btn-primary w-100" type="submit" name="submit">افزودن</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-danger w-100" type="cancel"
                                   name="cancel">بازگشت</a>
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
        $(document).ready(function () {
            $('#summernote').summernote();
        });

        $('#brandSelect').selectpicker({
            'title': 'انتخاب برند'
        });

        $('#platformSelect').selectpicker({
            'title': 'انتخاب پلتفرم'
        });

        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });

        $('#genreSelect').selectpicker({
            'title': 'انتخاب ژانر'
        });

        $('#categorySelect').selectpicker({
            'title': 'انتخاب دسته بندی'
        });

        $('#primary_img').change(function () {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })

        $('#other_imgs').change(function () {
            const filename = $(this).val();
            $(this).next('.custom-file-label').html(filename)
        })

        $('#attributeContainer').hide()
        $('#categorySelect').on('changed.bs.select', function () {
            const categoryId = $(this).val();

            $.get(`{{ url('/admin-panel/management/get-category-attribute/${categoryId}') }}`, function (response, status) {
                if (status === 'success') {
                    $('#attributeContainer').fadeIn()

                    $('#attributes').find('div').remove();
                    // console.log(response.attributes);

                    response.attributes.forEach(attribute => {
                        const attributeFormgroup = $('<div/>', {
                            class: 'form-group col-12 col-lg-4'
                        });
                        const attributeLable = $('<lable/>', {
                            for: attribute.name,
                            text: attribute.name
                        });
                        const attributeInput = $('<input/>', {
                            id: attribute.name,
                            type: 'text',
                            class: 'form-control',
                            name: `attribute_ids[${attribute.id}]`
                        });

                        attributeFormgroup.append(attributeLable);
                        attributeFormgroup.append(attributeInput);

                        $('#attributes').append(attributeFormgroup)
                    })

                    response.variation.forEach(variation => {
                        $('#variationName').text(variation.name);
                    })

                } else {
                    alert('مشکلی پیش آمد!')
                }
            }).fail(function () {
                alert('مشکلی پیش آمد!')
            })

        })

        $("#czContainer").czMore();
    </script>
@endsection
