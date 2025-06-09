@extends('admin.layout.master')

@section('title')
    داشبورد
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
                <div class="col-xl-6 col-md-4 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY">
                        <i class="fab far fa-chart-bar b-first" aria-hidden="true"></i>
                        <span class="mb-1 c-first">قرارداد های فعال</span>
{{--                        <span>{{ $activatedContracts }}</span>--}}
                    </div>
                </div>
                <div class="col-xl-6 col-md-4 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY">
                        <i class="fab far fa-chart-bar b-first" aria-hidden="true"></i>
                        <span class="mb-1 c-first">الحاقیه های فعال</span>
{{--                        <span>{{ $activatedAppendices }}</span>--}}
                    </div>
                </div>
                <div class="col-xl-6 col-md-4 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY"><i class="fab far fa-clock b-second" aria-hidden="true"></i>
                        <span class="mb-1 c-second">قرارداد های منتظر تایید شما</span>

                    </div>
                </div>
                <div class="col-xl-6 col-md-4 col-sm-6 p-2">
                    <div class="box-card text-right mini animate__animated animate__flipInY"><i class="fab far fa-clock b-second" aria-hidden="true"></i>
                        <span class="mb-1 c-second">الحاقیه های منتظر تایید شما</span>
{{--                        <span>{{ $pendingAppendices }}</span>--}}
{{--                    </div>--}}
                </div>
            </div>
            @if(!auth()->user()->hasanyrole(['user', 'admin']))
                <div class="alert alert-warning">
                    شما هیچ دسترسی ندارید!
                </div>
            @endif
        </div>
    </main>
@endsection
