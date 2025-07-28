@extends('admin.layout.master')

@php
    $title = 'ایجاد محصول';
@endphp

@section('title', $title)

@section('styles')
    <style>
        .d-flex.border.rounded.p-2 {
            background-color: transparent !important;
            border: none;
            padding: 0;
        }

        .btn.f-primary {
            background-color: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            color: inherit;
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

            <div class="row mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body">
                        @include('admin.layout.errors', ['errors' => $errors->store])
                        <form id="form" class="row p-3" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان:*</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active') && old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ old('is_active') && old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="brandSelect">برند:*</label>
                                <select id="brandSelect" class="form-control" name="brand_id" data-live-search="true" required>
                                    @foreach($brands as $key => $value)
                                        <option value="{{ $key }}" {{ old('brand_id') && old('brand_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="tagSelect">برچسب ها:*</label>
                                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple
                                        data-live-search="true" required>
                                    @foreach($tags as $key => $value)
                                        <option value="{{ $key }}" {{ old('tag_ids') ? (in_array($key , old('tag_ids')->toArray() ) ? 'selected' : '') : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="platformSelect">پلتفرم:*</label>
                                <select id="platformSelect" class="form-control" name="platform_id"
                                        data-live-search="true" required>
                                    @foreach($platforms as $key => $value)
                                        <option value="{{ $key }}" {{ old('platform_id') && old('platform_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="description">توضیحات:*</label>
                                <textarea id="description" name="description" class="form-control" required>{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group card shade c-grey col-12 col-lg-6">
                                <h5 class="card-header c-primary">افزودن تصویر</h5>
                                <div class="card-body row">
                                    <div class="col-12 m-1">
                                        <label class="d-block mb-2 text-right" for="primary_image">تصویر اصلی</label>
                                        <div class="d-flex flex-row-reverse align-items-center border rounded p-2" style="background-color: #f8f9fa;">
                                            <span class="btn f-primary ml-2 px-2" onclick="document.getElementById('primary_image').click();">
                                                انتخاب فایل
                                            </span>
                                            <span id="primary-image-file-name" class="text-muted flex-grow-1 text-right">
                                                هیچ فایلی انتخاب نشده
                                            </span>
                                            <input type="file" id="primary_image" name="primary_image" class="d-none" lang="fa">
                                        </div>
                                    </div>
                                    <div class="col-12 m-1">
                                        <label class="d-block mb-2 text-right" for="other_images">تصاویر دیگر</label>

                                        <div class="d-flex border rounded p-2 flex-row-reverse align-items-center">
                                            <span class="btn f-primary ml-2 px-2" onclick="document.getElementById('other_images').click();">
                                                انتخاب فایل‌ها
                                            </span>
                                            <span id="other-files-display" class="flex-grow-1 text-right">
                                                هیچ فایلی انتخاب نشده
                                            </span>
                                            <input type="file" id="other_images" name="other_images[]" multiple class="d-none" lang="fa">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group card shade c-grey col-12 col-lg-6">
                                <h5 class="card-header c-primary">هزینه ارسال</h5>
                                <div class="card-body row">
                                    <div class="form-group col-12">
                                        <label for="delivery_amount">هزینه ارسال: (به تومان)</label>
                                        <input type="text" name="delivery_amount" id="delivery_amount" class="form-control"
                                               value="{{ old('delivery_amount') }}">
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول: (به تومان)</label>
                                        <input type="text" name="delivery_amount_per_product" id="delivery_amount_per_product"
                                               class="form-control" value="{{ old('delivery_amount_per_product') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group card shade c-grey col-12">
                                <h5 class="card-header c-primary">دسته بندی</h5>
                                <div class="card-body row">
                                    <div class="form-group col-12 text-center">
                                        <label for="categorySelect">دسته بندی: *</label>
                                        <select id="categorySelect" class="form-control w-50" name="category_id" data-live-search="true">
                                            @foreach($categories as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="attributeContainer" class="col-12">
                                        <div id="filters" class="row"></div>
                                        <hr>
                                        افزودن قیمت و موجودی برای متغیر
                                        <span id="variationTitle" class="font-weight-bold"></span>:
                                        <div id="czContainer">
                                            <div id="first">
                                                <div class="recordset">
                                                    <div class="row">
                                                        <div class="form-group col-12 col-lg-3 my-2">
                                                            <label for="value">نام:*</label>
                                                            <input id="value" type="text" name="variation_values[][value]"
                                                                   class="form-control variation-value" required>
                                                        </div>
                                                        <div class="form-group col-12 col-lg-3 my-2">
                                                            <label for="price">قیمت:*</label>
                                                            <input id="price" type="text" name="variation_values[][price]"
                                                                   class="form-control variation-price" required>
                                                        </div>
                                                        <div class="form-group col-12 col-lg-3 my-2">
                                                            <label for="quantity">تعداد:*</label>
                                                            <input id="quantity" type="text" name="variation_values[][quantity]"
                                                                   class="form-control variation-quantity" required>
                                                        </div>
                                                        <div class="form-group col-12 col-lg-3 my-2">
                                                            <label for="sku">شناسه عددی:*</label>
                                                            <input id="sku" type="text" name="variation_values[][sku]"
                                                                   class="form-control variation-sku" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                                <button type="submit" class="btn f-primary">ادامه</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(function () {
            // 🔹 File input handlers
            $('#primary_image').on('change', function () {
                $('#primary-image-file-name').text(this.files[0]?.name || 'هیچ فایلی انتخاب نشده');
            });

            $('#other_images').on('change', function () {
                const fileNames = this.files.length
                    ? Array.from(this.files).map(f => f.name).join(', ')
                    : 'هیچ فایلی انتخاب نشده';
                $('#other-files-display').text(fileNames);
            });

            // 🔹 Plugin initializers (grouped to avoid repetition)
            const selects = ['#brandSelect', '#platformSelect', '#tagSelect', '#genreSelect', '#categorySelect'];
            selects.forEach(sel => $(sel).selectpicker({ title: $(sel).attr('title') || 'انتخاب گزینه' }));

            $('#attributeContainer').hide();
            $('#czContainer').czMore();

            // 🔹 Dynamic filters loading on category change
            $('#categorySelect').on('changed.bs.select', function () {
                const categoryId = $(this).val();
                const url = `{{ url('/management/get-category-attribute/${categoryId}') }}`;

                $.get(url, function (response, status) {
                    if (status !== 'success') return alert('اتصال برقرار نشد!');

                    $('#attributeContainer').fadeIn();
                    $('#filters').empty();

                    $.each(response.filters, function (key, value) {
                        const group = $('<div/>', { class: 'form-group col-12 col-lg-4' });
                        const label = $('<label/>', { for: 'filter' + value, text: `${value}:` });
                        const input = $('<input/>', {
                            id: 'filter' + value,
                            type: 'text',
                            class: 'form-control',
                            name: `filters_value[${key}]`
                        });

                        group.append(label).append(input);
                        $('#filters').append(group);
                    });

                    const variationValue = Object.values(response.variation)[0];
                    $('#variationTitle').text(variationValue || '');
                }).fail(() => alert('مشکلی در درخواست!'));
            });
            $('form').on('submit', function () {
                // Remove previous name attributes to avoid duplicates
                $('.variation-value, .variation-price, .variation-quantity, .variation-sku').removeAttr('name');

                $('#czContainer .recordset').each(function (index) {
                    $(this).find('.variation-value').attr('name', `variation_values[${index}][value]`);
                    $(this).find('.variation-price').attr('name', `variation_values[${index}][price]`);
                    $(this).find('.variation-quantity').attr('name', `variation_values[${index}][quantity]`);
                    $(this).find('.variation-sku').attr('name', `variation_values[${index}][sku]`);
                });
            });

        });
    </script>
@endsection
