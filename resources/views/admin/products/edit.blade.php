@extends('admin.layout.master')

@section('title', 'ویرایش محصول')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش محصول</h3>
                    <p>{{ $product->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.products.show', ['product' => $product->slug]) }}"
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye mr-1"></i> نمایش
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.products.partials.form', ['product' => $product])
        </div>
    </main>
@endsection

@section('scripts')
    @include('admin.products.partials.scripts', ['product' => $product])
@endsection
