@extends('home.layout.master')

@section('title')
    سبد خرید
@endsection

@section('content')

    <!-- START: Breadcrumbs -->
    <div class="nk-gap-1"></div>
    <div class="container my-3">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>

            <li><span class="fa fa-angle-left"></span></li>

            <li><span>سبد خرید</span></li>
        </ul>
    </div>
    <div class="nk-gap-1"></div>
    <!-- END: Breadcrumbs -->
    <div class="container">
        @if(isCartEmpty())
            <div class="alert alert-danger text-center text-right" style="direction: rtl">
                هیچ محصولی در سبد خرید شما وجود ندارد!
            </div>
        @else
            <div class="nk-store nk-store-cart">
                <form action="{{ route('home.cart.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('home.sections.errors', ['errors' => $errors->updateCart])
                    <div class="table-responsive">
                        <table class="table nk-store-cart-products text-right" style="direction: rtl">
                            <tbody>
                            @foreach(cartItems() as $product)
                                <tr>
                                    <td class="nk-product-cart-thumb">
                                        <a href="{{ route('home.products.show', ['product' => $product->itemable->slug]) }}"
                                           class="nk-image-box-1 nk-post-image">
                                            <img
                                                src="{{ Storage::url('products/images/primary_images') . '/' . $product->itemable->primary_image }}"
                                                width="115" alt="">
                                        </a>
                                    </td>
                                    <td class="nk-product-cart-title">
                                        <h5 class="h6">محصول:</h5>
                                        <h2 class="nk-post-title h4">
                                            <a href="{{ route('home.products.show', ['product' => $product->itemable->slug]) }}">{{ $product->itemable->title }}</a>
                                            <div class="nk-gap"></div>
{{--                                            <small style="color: #dd163b">--}}
{{--                                                {{ \App\Models\Attribute::findOrFail($product->attributes->attribute_id)->name . ':' }}--}}
{{--                                                {{ $product->attributes->value }}--}}
{{--                                            </small>--}}
                                        </h2>
                                    </td>
                                    <td class="nk-product-cart-price">
                                        <h5 class="h6">قیمت:</h5>
                                        <div class="nk-gap-1"></div>
                                        <strong>
                                            {{ number_format($product->variation->price) }}
                                            <i id="tooman_icon" class="icony icony-toman"></i>
                                        </strong>
                                        @if($product->variation->is_discounted)
                                            <small style="color: #dd163b">
                                                درصد تخفیف:
                                                {{ round((($product->variation->price - $product->variation->sale_price) / $product->variation->price) * 100) . '%' }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="nk-product-cart-quantity">
                                        <h5 class="h6">تعداد:</h5>
                                        <div class="nk-gap-1"></div>
                                        <div class="nk-form text-left" style="direction: ltr;width: 125%">
                                            <input type="number" class="form-control" value="{{ $product->quantity }}" min="1" max="{{ $product->variation->quantity }}" name="products[{{ $product->itemable->id }}][{{ $product->variation->id }}]]">
                                        </div>
                                    </td>
                                    <td class="nk-product-cart-total">
                                        <h5 class="h6">قیمت کل:</h5>
                                        <div class="nk-gap-1"></div>
                                        <strong>
                                            {{ number_format($product->quantity * ($product->variation->is_discounted ? $product->variation->sale_price : $product->variation->price)) }}
                                            <i id="tooman_icon" class="icony icony-toman"></i>
                                        </strong>
                                    </td>
                                    <td class="nk-product-cart-remove">
                                        <a href="{{ route('home.cart.remove', ['itemable_id' => $product->itemable->id]) }}">
                                            <span class="ion-android-close"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="nk-gap-1"></div>
                    <button class="nk-btn nk-btn-rounded nk-btn-color-white float-right" type="submit">ثبت تغییرات
                    </button>
                    <a href="{{ route('home.cart.clear') }}"
                       class="nk-btn nk-btn-rounded nk-btn-color-main-1 float-right mr-3">حذف سبد خرید</a>
                </form>

                <div class="clearfix"></div>
                <div class="nk-gap-2"></div>
                <div class="row vertical-gap">
                    <div class="col-md-8">
                        <!-- START: Cart Totals -->
                        <h3 class="nk-title h4 text-right" style="direction: rtl">مجموع سفارش</h3>
                        <table class="nk-table nk-table-sm text-right" style="direction: rtl">
                            <tbody>
                            <tr class="nk-store-cart-totals-subtotal">
                                <td>
                                    قیمت کل:
                                </td>
                                <td>
                                    {{ number_format(cartTotalAmount()) }} <i id="tooman_icon" class="icony icony-toman"></i>
                                </td>
                            </tr>
                            <tr class="nk-store-cart-totals-shipping">
                                <td>
                                    هزینه ارسال:
                                </td>
                                <td>
                                    {{ number_format(cartDeliveryAmount()) }} <i id="tooman_icon" class="icony icony-toman"></i>
                                </td>
                            </tr>
{{--                            <tr class="nk-store-cart-totals-shipping">--}}
{{--                                <td>--}}
{{--                                    مقدار تخفیف کالاها:--}}
{{--                                </td>--}}
{{--                                <td style="color: #dd163b">--}}
{{--                                    {{ number_format(cartTotalAmount()) }} <i id="tooman_icon"--}}
{{--                                                                                  class="icony icony-toman"></i>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
                            @if(session()->has('coupon'))
                                <tr class="nk-store-cart-totals-shipping">
                                    <td>
                                        مبلغ کد تخفیف:
                                    </td>
                                    <td style="color: #dd163b">
                                        {{ number_format( session()->get('coupon.amount') ) }} <i id="tooman_icon"
                                                                                                  class="icony icony-toman"></i>
                                    </td>
                                </tr>
                            @endif
                            <tr class="nk-store-cart-totals-total">
                                <td>
                                    قیمت نهایی:
                                </td>
                                <td>
                                    {{ number_format(cartPayingAmount()) }} <i id="tooman_icon"
                                                                              class="icony icony-toman"></i>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- END: Cart Totals -->
                    </div>
                    <div class="col-md-4">
                        <h3 class="nk-title h4 text-right">کد تخفیف</h3>
                        <form action="{{ route('home.cart.coupons.check') }}" method="POST" class="nk-form">
                            @csrf
                            <div class="nk-gap-1"></div>
                            <div class="row vertical-gap text-right" style="direction: rtl">
                                <div class="col-12">
                                    <label for="code">کد تخفیف:</label>
                                    <input type="text" class="form-control required" name="code" id="code"
                                           placeholder="کد تخفیف خود را وارد کنید...">
                                </div>
                            </div>
                            <div class="nk-gap-1"></div>
                            <button class="nk-btn nk-btn-rounded nk-btn-color-white float-right" type="submit">ثبت
                            </button>
                        </form>
                    </div>
                </div>

                <div class="nk-gap-2"></div>
                <a class="nk-btn nk-btn-rounded nk-btn-color-main-1 float-right"
                   href="{{ route('home.cart.checkout') }}">نهایی کردن خرید</a>
                <div class="clearfix"></div>
            </div>
        @endif
    </div>

    <div class="nk-gap-2"></div>

@endsection
