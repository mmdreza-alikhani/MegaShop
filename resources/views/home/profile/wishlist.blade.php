@extends('home.profile.master')

@php
    $active = 'wishlist';
@endphp

@section('section')
    <div class="info-box p-4 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6)">
        @if($wishlist->isEmpty())
            <div class="alert alert-danger text-center w-100 text-center">
                شما محصول مورد علاقه ای ندارید !!
            </div>
        @else
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">حذف</th>
                    <th scope="col">نام محصول</th>
                    <th scope="col">تصویر محصول</th>
                </tr>
                </thead>
                <tbody>
                @foreach($wishlist as $item)
                    <tr>
                        <td>
                            <form action="{{ route('home.products.wishlist.remove', ['product' => $item->product]) }}" method="GET">
                                <button class="btn btn-danger m-1" type="submit">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('home.products.show' , ['product' => $item->product->id]) }}">
                                {{ $item->product->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('home.products.show' , ['product' => $item->product->id]) }}">
                                <img src="{{ env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH') . '/' . $item->product->primary_image }}" alt="{{ $item->product->name }}" width="100">
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
