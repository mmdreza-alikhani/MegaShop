@extends('home.layout.master')

@section('title')
    تماس با ما
@endsection

@section('content')
    <div class="nk-gap-1"></div>
    <div class="container my-3">
        <ul class="nk-breadcrumbs text-right" style="direction: rtl">
            <li><a href="{{ route('home.index') }}">خانه</a></li>
            <li><span class="fa fa-angle-left"></span></li>
            <li><span>تماس با ما</span></li>
        </ul>
    </div>
    <div class="nk-gap-1"></div>

    <div class="container">
        <div class="row vertical-gap">
            <div class="col-lg-8">
                <h3 class="nk-title h4 text-right" style="direction: rtl">فرم تماس</h3>

                @include('home.sections.errors', ['errors' => $errors])

                <form action="{{ route('home.contactForm') }}" method="POST" class="nk-form text-right"
                      style="direction: rtl">
                    @csrf
                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <label for="name">نام و نام خانوادگی:</label>
                            <input type="text" class="form-control required" id="name" name="name"
                                   value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email">ایمیل:</label>
                            <input type="email" class="form-control required" id="email" name="email"
                                   value="{{ old('email') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject">موضوع:</label>
                            <input type="text" class="form-control required" id="subject" name="subject"
                                   value="{{ old('subject') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="message">متن پیام:</label>
                            <textarea class="form-control required" id="message" name="message" rows="6"
                                      required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <div class="nk-gap-1"></div>
                    <button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="submit">ارسال پیام</button>
                </form>
            </div>

            <div class="col-lg-4">
                <aside class="nk-sidebar nk-sidebar-right" style="direction: rtl">
                    <div class="nk-widget nk-widget-highlighted">
                        <h4 class="nk-widget-title"><span>راه‌های ارتباطی</span></h4>
                        <div class="nk-widget-content">
                            <ul class="nk-social-links">
                                @include('home.sections.contact-accounts')
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
