@extends('home.profile.master')

@section('section')
    <div class="info-box p-4 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6)">
        @if($wishlist->isEmpty())
            <div class="alert alert-danger text-center w-100 text-center" style="direction: rtl">
                شما محصول مورد علاقه ای ندارید!
            </div>
        @else
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">حذف</th>
                    <th scope="col">نام محصول</th>
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
                                {{ $item->product->title }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
