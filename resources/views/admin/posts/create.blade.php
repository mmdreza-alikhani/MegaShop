@extends('admin.layout.master')

@section('title', 'پست جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>پست جدید</h3>
                    <p>مقاله یا خبر تازه‌ای برای مجله بنویسید</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.posts.partials.form', ['post' => null])
        </div>
    </main>
@endsection
