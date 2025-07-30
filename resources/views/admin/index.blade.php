@extends('admin.layout.master')

@section('title')
    داشبورد
@endsection

@php
    use Illuminate\Support\Facades\DB;
    $usersCount = DB::table('users')->count();
    $ordersCount = DB::table('orders')->count();
    $productsCount = DB::table('products')->count();
    $postsCount = DB::table('posts')->count();
@endphp

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
                                        <span class="lite-text">صفحه اصلی</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item active">صفحه اصلی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 mb-2">
                <div class="col-xl-3 col-md-6 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY"><i class="fab far fa-chart-bar b-first" aria-hidden="true"></i>
                        <span class="mb-1 c-first">تعداد کاربران</span>
                        <span>{{ $usersCount }} کاربر </span>
                        <p class="mt-3 mb-1 text-right"><i class="far fas fa-wallet mr-1 c-first"></i> در حال
                            پیشرفت</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY"><i class="fab far fa-clock b-second" aria-hidden="true"></i>
                        <span class="mb-1 c-second">تعداد سفارشات</span>
                        <span>{{ $ordersCount }} سفارش </span>
                        <p class="mt-3 mb-1 text-right"><i class="far fas fa-wifi mr-1 c-second"></i>در حال پیشرفت
                        </p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY"><i class="fab far fa-comments b-third" aria-hidden="true"></i>
                        <span class="mb-1 c-third">تعداد محصولات</span>
                        <span>{{ $productsCount }} محصول </span>
                        <p class="mt-3 mb-1 text-right"><i class="fab fa-whatsapp mr-1 c-third"></i>در حال پیشرفت
                        </p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY   "><i class="fab far fa-gem b-forth" aria-hidden="true"></i>
                        <span class="mb-1 c-forth">تعداد مقالات</span>
                        <span>{{ $postsCount }} مقاله </span>
                        <p class="mt-3 mb-1 text-right"><i class="fab fa-bluetooth mr-1 c-forth"></i>در حال پیشرفت
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
