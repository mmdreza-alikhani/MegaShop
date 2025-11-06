@extends('admin.layout.master')

@php
    $title = 'ویرایش محصول';
@endphp

@section('title', $title)

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <div class="mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body">
                        @include('admin.layout.errors', ['errors' => $errors->update])
                        <form class="p-3" method="POST" action="{{ route('admin.products.update', ['product' => $product]) }}">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="form-group col-12 col-lg-4">
                                    <label for="title">عنوان:*</label>
                                    <input type="text" id="title" name="title" class="form-control" value="{{ $product->title }}" required>
                                </div>
                                <div class="form-group col-12 col-lg-4">
                                    <label for="is_active">وضعیت:*</label>
                                    <select class="form-control" id="is_active" name="is_active" required>
                                        <option value="1" {{ $product->is_active == '1' ? 'selected' : '' }}>فعال</option>
                                        <option value="0" {{ $product->is_active == '0' ? 'selected' : '' }}>غیرفعال</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-4">
                                    <label for="brandSelect">برند:*</label>
                                    <select id="brandSelect" name="brand_id" class="form-control" data-live-search="true" required>
                                        @foreach($brands as $key => $value)
                                            <option value="{{ $key }}" {{ $product->brand_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="platformSelect">پلتفرم:*</label>
                                    <select id="platformSelect" name="platform_id" class="form-control" required>
                                        @foreach($platforms as $key => $value)
                                            <option value="{{ $key }}" {{ $product->platform_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="delivery_amount">هزینه ارسال: (به تومان)</label>
                                    <input type="text" id="delivery_amount" name="delivery_amount" class="form-control"
                                           value="{{ $product->delivery_amount }}" required>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول: (به تومان)</label>
                                    <input type="text" name="delivery_amount_per_product" id="delivery_amount_per_product"
                                           class="form-control" value="{{ $product->delivery_amount_per_product }}" required>
                                </div>
                                <div class="form-group col-12 col-lg-3">
                                    <label for="tagSelect">برچسب ها:*</label>
                                    <select id="tagSelect" name="tag_ids[]" class="form-control" multiple required>
                                        @foreach($tags as $key => $value)
                                            <option value="{{ $key }}" {{ in_array($key , $product->tags()->pluck('id')->toArray() ) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-lg-12">
                                    <label for="description">توضیحات:*</label>
                                    <textarea id="description" name="description" class="form-control" required>{{ $product->description }}</textarea>
                                </div>
                                <label for="faqsContainer">سوالات متداول:</label>
                                <div class="col-12" id="faqsContainer">
                                    @foreach($product->faqs as $faq)
                                        <div class="recordset border-bottom">
                                            <div class="row m-2">
                                                <div class="form-group col-12">
                                                    <div class="form-group col-12">
                                                        <label for="question">عنوان سوال:*</label>
                                                        <input id="question" type="text" name="faqs[{{ $faq->id }}][question]" class="form-control" required value="{{ $faq->question }}">
                                                    </div>
                                                    <label for="answer">پاسخ سوال:*</label>
                                                    <textarea id="answer" type="text" name="faqs[{{ $faq->id }}][answer]" class="form-control" required>{{ $faq->answer }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div id="first">
                                        <div class="recordset border-bottom">
                                            <div class="row m-2">
                                                <div class="form-group col-12">
                                                    <label for="question">عنوان سوال:*</label>
                                                    <input id="question" type="text" name="newFaqs[][question]" class="form-control question" required>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label for="answer">پاسخ سوال:*</label>
                                                    <textarea id="answer" type="text" name="newFaqs[][answer]" class="form-control answer" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group card shade c-grey col-12">
                                    <h5 class="card-header c-primary">دسته بندی</h5>
                                    <div class="card-body row">
                                        <div class="form-group col-12 text-center">
                                            <label for="categorySelect">دسته بندی: *</label>
                                            <select id="categorySelect" class="form-control w-50" name="category_id">
                                                @foreach($categories as $key => $value)
                                                    <option value="{{ $key }}" {{ $key == $product->category_id ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="attributeContainer">
                                            <h6 class="text-center">
                                                مقادیر ویژگی های قابل فیلتر:
                                            </h6>
                                            <div id="filters" class="row">
                                                @foreach($filters as $filter)
                                                    <div class="form-group col-12 col-lg-3 my-2">
                                                        <label for="filter-{{ $filter['id'] }}">{{ $filter['title'] }}:*</label>
                                                        <input id="filter-{{ $filter['id'] }}" type="text" class="form-control" value="{{ $filter['value'] }}" name="filters_value[{{ $filter['id'] }}]" required>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <br>
                                            <h6 class="text-center">
                                                قیمت و موجودی برای متغیر
                                                <span id="variationTitle" class="font-weight-bold">{{ $variations->first()['title'] }}</span>:
                                            </h6>
                                            <div id="variationContainer">
                                                @foreach($variations as $variation)
                                                    <div class="recordset border-bottom">
                                                        <div class="row">
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="value">عنوان:*</label>
                                                                <input id="value" type="text" class="form-control" value="{{ $variation['value'] }}" name="variation_values[{{ $variation['id'] }}][value]" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="price">قیمت:*</label>
                                                                <input id="price" type="number" class="form-control" value="{{ $variation['price'] }}" name="variation_values[{{ $variation['id'] }}][price]" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="quantity">تعداد:*</label>
                                                                <input id="quantity" type="number" class="form-control" value="{{ $variation['quantity'] }}" name="variation_values[{{ $variation['id'] }}][quantity]" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="sku">شناسه عددی:*</label>
                                                                <input id="sku" type="text" class="form-control" value="{{ $variation['sku'] }}" name="variation_values[{{ $variation['id'] }}][sku]" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="sale_price">قیمت شامل تخفیف:*</label>
                                                                <input id="sale_price" type="number" class="form-control" value="{{ $variation['sale_price'] }}" name="variation_values[{{ $variation['id'] }}][sale_price]">
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="date_on_sale_from">شروع تخفیف:*</label>
                                                                <input id="date_on_sale_from" type="text" class="form-control" value="{{ $variation['date_on_sale_from'] ? str_replace('-', '/', verta($variation['date_on_sale_from'])) : '' }}" name="variation_values[{{ $variation['id'] }}][date_on_sale_from]" data-jdp>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="date_on_sale_to">پایان تخفیف:*</label>
                                                                <input id="date_on_sale_to" type="text" class="form-control" value="{{ $variation['date_on_sale_to'] ? str_replace('-', '/', verta($variation['date_on_sale_to'])) : '' }}" name="variation_values[{{ $variation['id'] }}][date_on_sale_to]" data-jdp>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                @endforeach
                                                <div id="first">
                                                    <div class="recordset">
                                                        <div class="row">
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="value">عنوان:*</label>
                                                                <input id="value" type="text"
                                                                       class="form-control variation-value" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="price">قیمت:*</label>
                                                                <input id="price" type="number"
                                                                       class="form-control variation-price" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="quantity">تعداد:*</label>
                                                                <input id="quantity" type="number"
                                                                       class="form-control variation-quantity" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-3 my-2">
                                                                <label for="sku">شناسه عددی:*</label>
                                                                <input id="sku" type="text"
                                                                       class="form-control variation-sku" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="sale_price">قیمت شامل تخفیف:</label>
                                                                <input id="sale_price" type="number"
                                                                       class="form-control variation-sale_price" required>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="date_on_sale_from">شروع تخفیف:</label>
                                                                <input id="date_on_sale_from" type="text"
                                                                       class="form-control variation-date_on_sale_from" required data-jdp>
                                                            </div>
                                                            <div class="form-group col-12 col-lg-4 my-2">
                                                                <label for="date_on_sale_to">پایان تخفیف:</label>
                                                                <input id="date_on_sale_to" type="text"
                                                                       class="form-control variation-date_on_sale_to" required data-jdp>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                                <button type="submit" class="btn f-primary p-2">ویرایش</button>
                            </div>
                        </form>

                        <div class="form-group card shade c-grey col-12">
                            <h5 class="card-header c-primary">تصاویر</h5>
                            <div class="card shade c-grey">
                                <div class="card-body">
                                    <!-- Add New Images Form -->
                                    <div class="mb-4">
                                        <h5 class="text-center">افزودن تصاویر جدید</h5>
                                        <form action="{{ route('admin.products.images.add', $product) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-12 col-lg-9">
                                                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                                                </div>
                                                <div class="form-group col-12 col-lg-3">
                                                    <button type="submit" class="btn btn-success btn-block">
                                                        <i class="fas fa-upload"></i> آپلود تصاویر
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <hr>

                                    <!-- Primary Image -->
                                    <h4 class="text-center">تصویر اصلی</h4>
                                    <hr>
                                    <div class="row justify-content-center">
                                        <div class="text-center">
                                            <img class="card-img img-fluid"
                                                 src="{{ Storage::url(config('upload.product_primary_path') . '/') . $product->primary_image }}"
                                                 alt="{{ $product->title }}-image"
                                                 style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                        </div>
                                    </div>

                                    <!-- Other Images -->
                                    <h4 class="text-center mt-4">تصاویر دیگر</h4>
                                    <hr>
                                    <div class="row">
                                        @forelse ($product->images as $image)
                                            <div class="col-12 col-md-4 text-center mb-3">
                                                <div class="card shade c-grey">
                                                    <img class="img-fluid p-2"
                                                         src="{{ Storage::url(config('upload.product_others_path') . '/') . $image->image }}"
                                                         alt="{{ $product->title }}-image"
                                                         style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                                    <div class="card-footer text-center">
                                                        <!-- Set as Primary Button -->
                                                        <form action="{{ route('admin.products.images.primary', ['product' => $product, 'image' => $image->id]) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('آیا می‌خواهید این تصویر را به عنوان تصویر اصلی تنظیم کنید؟')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-primary" title="تنظیم به عنوان تصویر اصلی">
                                                                <i class="fas fa-star"></i> اصلی
                                                            </button>
                                                        </form>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('admin.products.images.destroy', ['product' => $product, 'image' => $image->id]) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('آیا از حذف این تصویر مطمئن هستید؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف تصویر">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center">
                                                <p class="text-muted">تصویری وجود ندارد</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('description', {
            language: 'en',
            filebrowserImageUploadUrl: '/ckeditor/upload?_token=' + document.querySelector('meta[name="csrf-token"]').content,
            filebrowserBrowseUrl: '/storage/ckeditor/images',
            filebrowserImageBrowseUrl: '/storage/ckeditor/images',

            extraPlugins: 'uploadimage,filebrowser',
            removePlugins: 'image2,easyimage,cloudservices',
            toolbar: [
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                { name: 'tools', items: ['Maximize'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] }
            ]
        });
        const selects = ['#brandSelect', '#platformSelect', '#tagSelect', '#genreSelect', '#categorySelect'];
        selects.forEach(sel => $(sel).selectpicker({ title: $(sel).attr('title') || 'انتخاب گزینه' }));

        jalaliDatepicker.startWatch({
            minDate: 'today',
            time: true
        });

        $('#variationContainer').czMore();
        $('#faqsContainer').czMore();

        $('#categorySelect').on('changed.bs.select', function () {
            const categoryId = $(this).val();
            const url = `{{ url('/management/categories/${categoryId}/attributes') }}`;

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
                        name: `filters_value[${key}]`,
                        required: true
                    });

                    group.append(label).append(input);
                    $('#filters').append(group);
                });

                const variationValue = Object.values(response.variation)[0];
                $('#variationTitle').text(variationValue || '');
            }).fail(() => alert('مشکلی در درخواست!'));
        });

        $('form').on('submit', function () {
            $('.variation-value, .variation-price, .variation-quantity, .variation-sku, .variation-sale_price, .variation-date_on_sale_from, .variation-date_on_sale_to').removeAttr('name');
            $('.question, .answer').removeAttr('name');

            $('#variationContainer .recordset').each(function (index) {
                $(this).find('.variation-value').attr('name', `new_variation_values[${index}][value]`);
                $(this).find('.variation-price').attr('name', `new_variation_values[${index}][price]`);
                $(this).find('.variation-quantity').attr('name', `new_variation_values[${index}][quantity]`);
                $(this).find('.variation-sku').attr('name', `new_variation_values[${index}][sku]`);
                $(this).find('.variation-sale_price').attr('name', `new_variation_values[${index}][sale_price]`);
                $(this).find('.variation-date_on_sale_from').attr('name', `new_variation_values[${index}][date_on_sale_from]`);
                $(this).find('.variation-date_on_sale_to').attr('name', `new_variation_values[${index}][date_on_sale_to]`);
            });

            $('#faqsContainer .recordset').each(function (index) {
                $(this).find('.question').attr('name', `faqs[${index}][question]`);
                $(this).find('.answer').attr('name', `faqs[${index}][answer]`);
            });
        });
    </script>
@endsection
