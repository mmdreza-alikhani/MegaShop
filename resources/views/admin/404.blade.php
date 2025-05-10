@extends('admin.layout.master')
@section('title' , 'یافت نشد!')
@section('content')
    <div class='w-100 h-100 text-center'>
        <div class=''>
            <img alt='404 NOT FOUND' src='/admin/dist/img/404.png'/>
        </div>

        <div class=''>
            <h1 style="font-size: 60px;">404</h1>
            <h3>اوپس صفحه یافت نشد</h3>
            <p>صفحه‌ای که به دنبال آن هستید یافت نشد.</p>
            <div>
                <a href="{{ route('index') }}" type='primary' class='btn btn-primary'>رفتن به خانه</a>
            </div>
        </div>
    </div>
@endsection
