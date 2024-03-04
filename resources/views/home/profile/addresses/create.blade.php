@extends('home.profile.master')

@php
    $active = 'addresses';
@endphp

@section('section')
    <div class="info-box p-5 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6);direction: rtl">
        <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
            <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl">
                    <li><span style="font-size: 24px">افزودن آدرس</span></li>
                </ul>
            </div>
        </aside>
        <div class="nk-gap"></div>
        <div class="mx-4">
            @include('home.sections.errors')
            <form action="{{ route('home.profile.addresses.store') }}" method="POST" class="row text-right" style="direction: rtl">
                @csrf
                <div class="col-12 col-lg-4">
                    <label for="title">عنوان:*</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="عنوان" id="title" value="{{ old('title') }}" name="title">
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="postalCode">کد پستی:*</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="کد پستی" id="postalCode" value="{{ old('postalCode') }}" name="postalCode">
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="phoneNumber">شماره تلفن:*</label>
                    <div class="input-group mb-3">
                        <input type="tel" class="form-control" placeholder="شماره تلفن" id="phoneNumber" value="{{ old('phoneNumber') }}" name="phoneNumber">
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <label for="provinceSelect">استان:*</label>
                    <div class="input-group mb-3">
                        <select id="provinceSelect" class="form-control" name="province_id">
                            <option value="0" selected disabled>استان خود را انتخاب کنید...</option>
                            @foreach(\App\Models\Province::all() as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <label for="citySelect">شهر:*</label>
                    <div class="input-group mb-3">
                        <select id="citySelect" class="form-control" name="city_id">
                        </select>
                    </div>
                </div>

                <div class="col-12 col-lg-12">
                    <label for="address">آدرس:*</label>
                    <div class="input-group mb-3">
                        <textarea id="address" name="address" class="form-control">{{ old('address') }}</textarea>
                    </div>
                </div>

                <br><br><br><br>

                <div class="col-12 col-lg-12">
                    <button class="btn btn-success" type="submit">ثبت</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#provinceSelect').change(function() {

            var provinceID = $(this).val();

            if (provinceID) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get_province_cities_list') }}?province_id=" + provinceID,
                    success: function(res) {
                        if (res) {
                            $("#citySelect").empty();

                            $.each(res, function(key , city) {
                                $("#citySelect").append('<option value="' + city.id + '">' +
                                    city.name + '</option>');
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
