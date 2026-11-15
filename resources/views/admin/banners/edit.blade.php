@extends('admin.layout.master')

@section('title', 'ویرایش بنر')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش بنر</h3>
                    <p>{{ $banner->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.banners.show', ['banner' => $banner->id]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye mr-1"></i> نمایش
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.banners.partials.form', ['banner' => $banner])
        </div>
    </main>
@endsection
