@extends('admin.layout.master')

@section('title', 'بنر جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>بنر جدید</h3>
                    <p>یک بنر تازه برای صفحه اصلی یا دسته‌بندی بسازید</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.banners.partials.form', ['banner' => null])
        </div>
    </main>
@endsection
