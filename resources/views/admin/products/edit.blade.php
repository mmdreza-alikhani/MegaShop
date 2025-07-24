@extends('admin.layout.master')

@php
    $title = 'ویرایش محصول';
@endphp

@section('title', $title)

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
                        <form class="row" method="POST" action="{{ route('admin.products.update', ['product' => $product]) }}">
                            @csrf
                            @method('put')
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
                                <label for="tagSelect">تگ ها:*</label>
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
                            <div class="form-group card shade c-grey col-12">
                                <h5 class="card-header c-primary">تصاویر</h5>
                                <div class="card shade c-grey ">
                                    <div class="card-body">
                                        <h4 class="text-center">تصویر اصلی</h4>
                                        <hr>
                                        <div class="row justify-content-center">
                                            <div class="text-center">
                                                <img class="card-img img-fluid"
                                                     src="{{ url(env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH')) . '/' . $product->primary_image }}"
                                                     alt="{{ $product->title }}-image" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                            </div>
                                        </div>
                                        <h4 class="text-center mt-2">تصاویر دیگر</h4>
                                        <hr>
                                        <div class="row">
                                            @foreach ($product->images as $image)
                                                <div class="col-12 col-md-4 text-center mb-3">
                                                    <img class="img-fluid"
                                                         src="{{ url(env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH') . '/' . $image->image) }}"
                                                         alt="{{ $product->title }}-image"
                                                         style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group card shade c-grey col-12">
                                <h5 class="card-header c-primary">دسته بندی</h5>
                                <div class="card-body row">
                                    <div class="form-group col-12 text-center">
                                        <label for="categorySelect">دسته بندی: *</label>
                                        <select id="categorySelect" class="form-control w-50">
                                            @foreach($categories as $key => $value)
                                                <option value="{{ $key }}" {{ $key == $product->category_id ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="attributeContainer">
                                        <h6 class="text-center">
                                            مقادیر ویژگی های قابل فیلتر:
                                        </h6>
                                        <div class="row">
                                            @foreach($filters as $filter)
                                                <div class="form-group col-12 col-lg-3 my-2">
                                                    <label for="filter-">{{ $filter['title'] }}:*</label>
                                                    <input id="filter-" type="text" class="form-control" value="{{ $filter['value'] }}" disabled>
                                                </div>
                                            @endforeach
                                        </div>
                                        <br>
                                        <h6 class="text-center">
                                            قیمت و موجودی برای متغیر
                                            <span id="variationTitle" class="font-weight-bold">{{ $variations->first()['title'] }}</span>:
                                        </h6>
                                        @foreach($variations as $variation)
                                            <div class="row">
                                                <div class="form-group col-12 col-lg-3 my-2">
                                                    <label for="value">عنوان:*</label>
                                                    <input id="value" type="text" class="form-control" value="{{ $variation['value'] }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-3 my-2">
                                                    <label for="price">قیمت:*</label>
                                                    <input id="price" type="text" class="form-control" value="{{ $variation['price'] }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-3 my-2">
                                                    <label for="quantity">تعداد:*</label>
                                                    <input id="quantity" type="text" class="form-control" value="{{ $variation['quantity'] }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-3 my-2">
                                                    <label for="sku">شناسه عددی:*</label>
                                                    <input id="sku" type="text" class="form-control" value="{{ $variation['sku'] }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-4 my-2">
                                                    <label for="sale_price">قیمت شامل تخفیف:*</label>
                                                    <input id="sale_price" type="text" class="form-control" value="{{ $variation['sale_price'] ? number_format($variation['sale_price']) : '' }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-4 my-2">
                                                    <label for="date_on_sale_from">شروع تخفیف:*</label>
                                                    <input id="date_on_sale_from" type="text" class="form-control" value="{{ $variation['date_on_sale_from'] ? verta($variation['date_on_sale_to']) : '' }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-4 my-2">
                                                    <label for="date_on_sale_to">پایان تخفیف:*</label>
                                                    <input id="date_on_sale_to" type="text" class="form-control" value="{{ $variation['date_on_sale_to'] ? verta($variation['date_on_sale_to']) : '' }}" disabled>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                                <button class="btn btn-primary" type="submit">ادامه</button>
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
            const categoryId = {{ $product->category_id }};
            const url = `{{ url('/management/get-category-attribute/' . $product->category_id) }}`;

            $.get(url, function (response, status) {
                if (status !== 'success') return alert('اتصال برقرار نشد!');

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
    </script>
@endsection
