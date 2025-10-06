@extends('home.profile.master')

@section('section')
    <div class="info-box p-5 m-3 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6);direction: rtl">
        <div class="w-100">
            <a href="{{ route('home.profile.addresses.create') }}" class="btn text-white h-auto nk-btn-color-info d-block w-25" style="margin: 0 auto">
                ثبت آدرس جدید
            </a>
        </div>
        <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
            <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl">
                    <li><span style="font-size: 24px">آدرس ها:</span></li>
                </ul>
            </div>
        </aside>
        @if($user->addresses->isEmpty())
            <div class="alert alert-danger text-center w-100">
                شما تا به حال آدرسی ثبت نکرده اید!
            </div>
        @else
            <table class="nk-table text-right container" style="direction: rtl">
                <thead>
                <tr>
                    <th colspan="4">آدرس های ثبت شده</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th class="text-center">عنوان</th>
                    <th class="text-center">کد پستی</th>
                    <th class="text-center">شماره تلفن</th>
                    <th class="text-center">تنظیمات</th>
                </tr>
                @foreach($user->addresses as $address)
                    <tr>
                        <td class="text-center">{{ $address->title }}</td>
                        <td class="text-center">{{ $address->postal_code }}</td>
                        <td class="text-center">{{ $address->phone_number }}</td>
                        <td class="text-center row">
                            <div class="col-6">
                                <a class="btn nk-btn-color-main-1 text-light" href="{{ route('home.profile.addresses.edit', ['address' => $address->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>

                            <div class="col-6">
                                <form action="{{ route('home.profile.addresses.destroy', ['address' => $address->id]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn nk-btn-color-main-1 text-light">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
