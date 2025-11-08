@extends('admin.layout.master')

@php
    $title = 'نمایش محصول';
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

            <div class="mx-1">
                <div class="card shade c-grey">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body row mx-1">
                        <div class="form-group col-12 col-lg-3">
                            <label for="title">عنوان:*</label>
                            <input type="text" id="title" class="form-control" value="{{ $product->title }}" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="is_active">وضعیت:*</label>
                            <select class="form-control" id="is_active">
                                <option value="1" {{ $product->is_active == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ $product->is_active == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="brandSelect">برند:*</label>
                            <select id="brandSelect" class="form-control" data-live-search="true" disabled>
                                <option value="{{ $product->brand_id }}" selected>{{ $product->brand->title }}</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>لینک کوتاه:</label>
                            <input type="text" value="{{ $product->shortLink->code }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="platformSelect">پلتفرم:*</label>
                            <select id="platformSelect" class="form-control" name="platform_id" disabled>
                                <option value="{{ $product->platform_id }}" selected>{{ $product->platform->title }}</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="delivery_amount">هزینه ارسال: (به تومان)</label>
                            <input type="text" id="delivery_amount" class="form-control"
                                   value="{{ $product->delivery_amount }}" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول: (به تومان)</label>
                            <input type="text" id="delivery_amount_per_product"
                                   class="form-control" value="{{ $product->delivery_amount_per_product }}" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label for="tags">برچسب ها:</label>
                            <button data-target="#showTagsModal" data-toggle="modal" type="button" id="tags" class="btn btn-block btn-primary">نمایش برچسب ها</button>
                        </div>
                        <div class="modal w-lg fade light rtl" id="showTagsModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content card shade">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            نمایش برچسب ها:
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <ul>
                                            @foreach($product->tags as $tag)
                                               <li>{{ $tag->title }}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="description">توضیحات:*</label>
                            <textarea id="description" name="description" class="form-control" disabled>{{ $product->description }}</textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="text">سوالات متداول:</label>
                            <div class="accordion" id="faqsAccordion">
                                @foreach($product->faqs as $faq)
                                    <div id="accordion" class="accordion card shade full-outlined o-primary">
                                        <div class="card-header" id="heading-{{ $faq->id }}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapse-{{ $faq->id }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse-{{ $faq->id }}" class="collapse" aria-labelledby="heading-{{ $faq->id }}" data-parent="#faqsAccordion">
                                            <div class="card-body">
                                                {{ $faq->answer }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                                                 src="{{ Storage::url(config('upload.product_primary_path') . '/') . $product->primary_image }}"
                                                 alt="{{ $product->title }}-image" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                        </div>
                                    </div>
                                    <h4 class="text-center mt-2">تصاویر دیگر</h4>
                                    <hr>
                                    <div class="row">
                                        @foreach ($product->images as $image)
                                            <div class="col-12 col-md-4 text-center mb-3">
                                                <img class="img-fluid"
                                                     src="{{ Storage::url(config('upload.product_others_path') . '/') . $image->image }}"
                                                     alt="{{ $product->title }}-image"
                                                     style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group card shade c-primary col-12">
                            <h5 class="card-header c-primary">دسته بندی</h5>
                            <div class="card-body row">
                                <div class="form-group col-12 text-center">
                                    <label for="categorySelect">دسته بندی: *</label>
                                    <select id="categorySelect" class="form-control w-50">
                                        <option value="{{ $product->category_id }}" selected>{{ $product->category->title }}</option>
                                    </select>
                                </div>
                                <div id="attributeContainer">
                                    <h6 class="text-center">
                                        مقادیر ویژگی های قابل فیلتر:
                                    </h6>
                                    <div class="row">
                                    @foreach($filters as $filter)
                                        <div class="form-group col-12 col-lg-3 my-2">
                                            <label for="filter-{{ $filter['title'] }}">{{ $filter['title'] }}:*</label>
                                            <input id="filter-{{ $filter['title'] }}" type="text" class="form-control" value="{{ $filter['value'] }}" disabled>
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
                                            <div class="form-group col-12 col-lg-3">
                                                <label for="value">عنوان:*</label>
                                                <input id="value" type="text" class="form-control" value="{{ $variation['value'] }}" disabled>
                                            </div>
                                            <div class="form-group col-12 col-lg-3">
                                                <label for="price">قیمت:*</label>
                                                <input id="price" type="text" class="form-control" value="{{ $variation['price'] }}" disabled>
                                            </div>
                                            <div class="form-group col-12 col-lg-3">
                                                <label for="quantity">تعداد:*</label>
                                                <input id="quantity" type="text" class="form-control" value="{{ $variation['quantity'] }}" disabled>
                                            </div>
                                            <div class="form-group col-12 col-lg-3">
                                                <label for="sku">شناسه عددی:*</label>
                                                <input id="sku" type="text" class="form-control" value="{{ $variation['sku'] }}" disabled>
                                            </div>
                                            @if($product->IsOnSale)
                                                <div class="form-group col-12 col-lg-4">
                                                    <label for="sale_price">قیمت شامل تخفیف:*</label>
                                                    <input id="sale_price" type="text" class="form-control" value="{{ $variation['sale_price'] ? number_format($variation['sale_price']) : '' }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-4">
                                                    <label for="date_on_sale_from">شروع تخفیف:*</label>
                                                    <input id="date_on_sale_from" type="text" class="form-control" value="{{ $variation['date_on_sale_from'] ? verta($variation['date_on_sale_to']) : '' }}" disabled>
                                                </div>
                                                <div class="form-group col-12 col-lg-4">
                                                    <label for="date_on_sale_to">پایان تخفیف:*</label>
                                                    <input id="date_on_sale_to" type="text" class="form-control" value="{{ $variation['date_on_sale_to'] ? verta($variation['date_on_sale_to']) : '' }}" disabled>
                                                </div>
                                            @else
                                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show"
                                                     role="alert">
                                                    هیچ تخفیفی وجود ندارد!
                                                </div>
                                            @endif
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
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
            toolbar: []
        });
    </script>
@endsection
