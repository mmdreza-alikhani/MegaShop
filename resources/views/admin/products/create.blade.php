@extends('admin.layout.master')

@section('title', 'ایجاد محصول')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ایجاد محصول</h3>
                    <p>اطلاعات پایه، تصاویر، تنوع‌ها و سوالات متداول</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.products.partials.form', ['product' => null])
        </div>
    </main>
@endsection

@section('scripts')
    @include('admin.products.partials.scripts', ['product' => null])
@endsection
