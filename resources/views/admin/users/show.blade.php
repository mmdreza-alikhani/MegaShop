@extends('admin.layouts.master')
@section('title')
    کاربر : {{ $user->username }}
@endsection
@php
    $active_parent = 'users';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
        <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        نمایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <img src="{{ Str::contains($user->avatar, 'https://') ? $user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $user->avatar }}" alt="{{ $user->username }}-image" id="output" width="100" height="100" />
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>نام کاربری</label>
                                <input type="text" value="{{ $user->username }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>نام</label>
                                <input type="text" value="{{ $user->first_name }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>نام خانوادگی</label>
                                <input type="text" value="{{ $user->last_name }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>شماره تلفن</label>
                                <input type="text" value="{{ $user->phone_number }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>ایمیل</label>
                                <div class="input-group-prepend">
                                    <input type="text" value="{{ $user->email }}" class="form-control" disabled>
                                    @if($user->email_verified_at == null)
                                        <div class="input-group-text">
                                            <i class="fa fa-times-circle text-danger"></i>
                                        </div>
                                    @else
                                        <div class="input-group-text">
                                            <i class="fa fa-check-circle text-success"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>طریقه ثبت نام</label>
                                @if($user->provider_name == 'manual')
                                    <input type="text" value="دستی توسط ادمین" class="form-control" disabled>
                                @elseif($user->provider_name == '')
                                    <input type="text" value="دستی توسط کاربر" class="form-control" disabled>
                                @else
                                    <input type="text" value="{{ $user->provider_name }}" class="form-control" disabled>
                                @endif
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>تاریخ ایجاد</label>
                                <input type="text" value="{{ verta($user->created_at) }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>تاریخ آخرین تغییرات</label>
                                <input type="text" value="{{ verta($user->updated_at) }}" class="form-control" disabled>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-danger">بازگشت</a>
                    </div>
                </div>
            </div>
        <div class="row mx-1">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        نظرات
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mx-4">
                                <table class="table text-center table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">عنوان محصول | مقاله | خبر</th>
                                        <th scope="col">متن نظر</th>
                                        <th scope="col">وضعیت نظر</th>
                                        <th scope="col">تنظیمات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td>
                                                @if($comment->product_id != null)
                                                    <a href="{{ route('admin.products.show' , ['product' => $comment->product->slug]) }}">
                                                        {{ substr($comment->product->name, 0, 50) }}
                                                    </a>
                                                @elseif($comment->article_id != null)
                                                    <a href="{{ route('admin.articles.show' , ['article' => $comment->article->slug]) }}">
                                                        {{ substr($comment->article->title, 0, 50) }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.news.show' , ['news' => $comment->news->slug]) }}">
                                                        {{ substr($comment->news->title, 0, 50) }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ substr($comment->text, 0, 30) }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $comment->getRawOriginal('approved') ?  'badge-success' : 'badge-secondary' }}">
                                                    {{$comment->approved}}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.comments.show' , [$comment->id]) }}" class="btn btn-success m-1">
                                                    نمایش
                                                </a>
                                                <form action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="btn btn-danger m-1" type="submit">
                                                        حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        موارد مورد علاقه
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mx-4">
                                <table class="table text-center table-responsive">
                                    <thead>
                                    <tr>
                                        <th>عنوان محصول</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($favorites as $favorite)
                                        <tr>
                                            <td>
                                                <a href="{{ route('home.products.show' , ['product' => $favorite->product->id]) }}">
                                                    {{ $favorite->product->name }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        سفارشات
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="mx-4">
                                <table class="table text-center table-responsive">
                                    <thead>
                                    <tr>
                                        <th scope="col">شناسه سفارش</th>
                                        <th scope="col">توکن</th>
                                        <th scope="col">مبلغ قابل پرداخت</th>
                                        <th scope="col">وضعیت پرداخت</th>
                                        <th scope="col">نمایش</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                {{ $order->id }}
                                            </td>
                                            <td>
                                                {{ $order->transaction->token }}
                                            </td>
                                            <td>
                                                {{ number_format($order->paying_amount) . 'تومان' }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $order->getRawOriginal('payment_status') ?  'badge-success' : 'badge-danger' }}">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show' , ['order' => $order->id]) }}" class="btn btn-success m-1">
                                                    نمایش
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
