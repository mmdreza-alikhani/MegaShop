@extends('admin.layout.master')

@section('title', 'ویرایش پست')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش پست</h3>
                    <p>{{ $post->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.posts.show', ['post' => $post->id]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye mr-1"></i> نمایش
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.posts.partials.form', ['post' => $post])
        </div>
    </main>
@endsection
