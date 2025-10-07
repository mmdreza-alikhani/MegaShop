@extends('home.profile.master')

@section('section')
    <div class="info-box p-5 m-3 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6);direction: rtl">
        <div class="w-100">
            <a href="#" data-toggle="modal" data-target="#createAddressModal" class="btn text-white h-auto nk-btn-color-info d-block w-25" style="margin: 0 auto">
                ثبت آدرس جدید
            </a>
        </div>
        <div class="nk-modal modal fade" id="createAddressModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ion-android-close"></span>
                        </button>
                        <h4 class="mb-0 text-right"><span class="text-main-1"> ایجاد</span> آدرس</h4>
                        <div class="nk-gap-1"></div>
                        <form action="{{ route('home.profile.addresses.store') }}" method="post" class="nk-form text-white" style="direction: rtl">
                            @include('home.sections.errors', ['errors' => $errors->storeAddress])
                            @csrf
                            <div class="row vertical-gap text-right">
                                <div class="col-12">
                                    <label for="title">عنوان:*</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="عنوان" id="title" value="{{ old('title') }}" name="title" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="postal_code">کد پستی:*</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="کد پستی" id="postal_code" value="{{ old('postal_code') }}" name="postal_code" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="phone_number">شماره تلفن:*</label>
                                    <div class="input-group mb-3">
                                        <input type="tel" class="form-control" minlength="10" maxlength="10" placeholder="9121234567" id="phone_number" value="{{ old('phone_number') }}" name="phone_number">
                                        <div class="input-group-append">
                                            <span class="input-group-text">+98</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="provinceSelect">استان:*</label>
                                    <div class="input-group mb-3">
                                        <select id="provinceSelect" class="form-control" name="province_id" required>
                                            <option value="0" selected disabled>استان خود را انتخاب کنید...</option>
                                            @foreach($provinces as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="citySelect">شهر:*</label>
                                    <div class="input-group mb-3">
                                        <select id="citySelect" class="form-control" name="city_id" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <label for="address">آدرس:*</label>
                                    <div class="input-group mb-3">
                                        <textarea id="address" name="address" class="form-control" required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-gap-1"></div>
                            <div class="row vertical-gap">
                                <div class="col-md-6 text-right" style="direction: rtl">
                                    <button class="back_to_login nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-block" type="button">بازگشت</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">ایجاد</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                                <a class="btn nk-btn-color-main-1 text-light" href="#" data-toggle="modal" data-target="#editAddressModal-{{ $address->id }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                            <div class="nk-modal modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span class="ion-android-close"></span>
                                            </button>
                                            <h4 class="mb-0 text-right"><span class="text-main-1"> ویرایش آدرس: </span> {{ $address->title }}</h4>
                                            <div class="nk-gap-1"></div>
                                            <form action="{{ route('home.profile.addresses.update', ['address' => $address->id]) }}" method="post" class="nk-form text-white" style="direction: rtl">
                                                @include('home.sections.errors', ['errors' => $errors->updateAddress])
                                                @csrf
                                                <div class="row vertical-gap text-right">
                                                    <div class="col-12">
                                                        <label for="title">عنوان:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" placeholder="عنوان" id="title" value="{{ $address->title }}" name="title" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="postal_code">کد پستی:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" placeholder="کد پستی" id="postal_code" value="{{ $address->postal_code }}" name="postal_code" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="phone_number">شماره تلفن:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="tel" class="form-control" minlength="10" maxlength="10" placeholder="9121234567" id="phone_number" value="{{ $address->phone_number }}" name="phone_number">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">+98</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="provinceSelect">استان:*</label>
                                                        <div class="input-group mb-3">
                                                            <select id="provinceSelect" class="form-control" name="{{ $address->province_id }}" required>
                                                                <option value="0" selected disabled>استان خود را انتخاب کنید...</option>
                                                                @foreach($provinces as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="citySelect">شهر:*</label>
                                                        <div class="input-group mb-3">
                                                            <select id="citySelect" class="form-control" name="city_id" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-12">
                                                        <label for="address">آدرس:*</label>
                                                        <div class="input-group mb-3">
                                                            <textarea id="address" name="address" class="form-control" required>{{ $address->address }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-gap-1"></div>
                                                <div class="row vertical-gap">
                                                    <div class="col-md-6 text-right" style="direction: rtl">
                                                        <button class="back_to_login nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-block" type="button">بازگشت</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">ایجاد</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(count($errors->updateAddress) > 0)
                                <script>
                                    $(function() {
                                        $('#editAddressModal-{{ session()->get('address_id') }}').modal({
                                            show: true
                                        });
                                    });
                                </script>
                            @endif

                            <div class="col-6">
                                <a class="btn nk-btn-color-main-1 text-light" href="#" data-toggle="modal" data-target="#deleteAddressModal-{{ $address->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
{{--                                <form action="{{ route('home.profile.addresses.destroy', ['address' => $address->id]) }}" method="POST">--}}
{{--                                    @method('DELETE')--}}
{{--                                    @csrf--}}
{{--                                    <button type="submit" class="btn nk-btn-color-main-1 text-light">--}}
{{--                                        <i class="fa fa-trash"></i>--}}
{{--                                    </button>--}}
{{--                                </form>--}}
                            </div>
                            <div class="nk-modal modal fade" id="deleteAddressModal-{{ $address->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span class="ion-android-close"></span>
                                            </button>
                                            <h4 class="mb-0 text-right"><span class="text-main-1"> حذف آدرس:</span> {{ $address->title }}</h4>
                                            <div class="nk-gap-1"></div>
                                            <form action="{{ route('home.profile.addresses.destroy', ['address' => $address->id]) }}" method="post" class="nk-form text-white" style="direction: rtl">
                                                @method('DELETE')
                                                <div class="row vertical-gap text-right">
                                                    <div class="col-12">
                                                        <label for="title">عنوان:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" placeholder="عنوان" id="title" value="{{ old('title') }}" name="title" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="postal_code">کد پستی:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" placeholder="کد پستی" id="postal_code" value="{{ old('postal_code') }}" name="postal_code" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="phone_number">شماره تلفن:*</label>
                                                        <div class="input-group mb-3">
                                                            <input type="tel" class="form-control" minlength="10" maxlength="10" placeholder="9121234567" id="phone_number" value="{{ old('phone_number') }}" name="phone_number">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">+98</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="provinceSelect">استان:*</label>
                                                        <div class="input-group mb-3">
                                                            <select id="provinceSelect" class="form-control" name="province_id" required>
                                                                <option value="0" selected disabled>استان خود را انتخاب کنید...</option>
                                                                @foreach($provinces as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <label for="citySelect">شهر:*</label>
                                                        <div class="input-group mb-3">
                                                            <select id="citySelect" class="form-control" name="city_id" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-12">
                                                        <label for="address">آدرس:*</label>
                                                        <div class="input-group mb-3">
                                                            <textarea id="address" name="address" class="form-control" required>{{ old('address') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-gap-1"></div>
                                                <div class="row vertical-gap">
                                                    <div class="col-md-6 text-right" style="direction: rtl">
                                                        <button class="back_to_login nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-block" type="button">بازگشت</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">ایجاد</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        @if(count($errors->storeAddress) > 0)
        $('#createAddressModal').modal({
            show: true
        });
        @endif
        $('#provinceSelect').change(function() {
            let provinceID = $(this).val();
            if (provinceID) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get_province_cities_list') }}/" + provinceID,
                    success: function(res) {
                        if (res) {
                            $("#citySelect").empty();
                            $.each(res, function(key , value) {
                                $("#citySelect").append('<option value="' + key + '">' + value + '</option>');
                            });
                        } else {
                            $("#citySelect").empty();
                        }
                    }
                });
            } else {
                $("#citySelect").empty();
            }
        });
    </script>
@endsection
