@extends('home.profile.master')

@section('section')
    <div class="mk-panel">
        <header class="mk-panel-head">
            <div>
                <h2>آدرس‌های من</h2>
                <p>آدرس‌های ارسال سفارش را مدیریت کنید.</p>
            </div>
            <button type="button" class="mk-btn mk-btn-primary" data-toggle="modal" data-target="#createAddressModal">
                <span class="fa fa-plus"></span> ثبت آدرس جدید
            </button>
        </header>

        @if($user->addresses->isEmpty())
            <div class="mk-empty-state">
                <span class="mk-empty-icon"><span class="fa fa-map-marker"></span></span>
                <h2>هنوز آدرسی ثبت نکرده‌اید</h2>
                <p>برای ثبت سفارش، حداقل یک آدرس ارسال اضافه کنید.</p>
                <button type="button" class="mk-btn mk-btn-primary" data-toggle="modal"
                        data-target="#createAddressModal">
                    <span class="fa fa-plus"></span> ثبت آدرس جدید
                </button>
            </div>
        @else
            <div class="mk-address-grid">
                @foreach($user->addresses as $address)
                    <article class="mk-address-card">
                        <header class="mk-address-head">
                            <span class="mk-address-icon"><span class="fa fa-map-marker"></span></span>
                            <strong>{{ $address->title }}</strong>
                            <div class="mk-address-actions">
                                <button type="button" class="mk-address-action" title="ویرایش"
                                        data-toggle="modal" data-target="#editAddressModal-{{ $address->id }}">
                                    <span class="fa fa-pen"></span>
                                </button>
                                <button type="button" class="mk-address-action is-danger" title="حذف"
                                        data-toggle="modal" data-target="#deleteAddressModal-{{ $address->id }}">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </div>
                        </header>

                        <p class="mk-address-text">{{ $address->address }}</p>

                        <ul class="mk-address-meta">
                            <li>
                                <span>استان / شهر</span>
                                <strong>
                                    {{ $address->province?->name ?? '—' }}
                                    @if($address->city)
                                        — {{ $address->city->name }}
                                    @endif
                                </strong>
                            </li>
                            <li>
                                <span>کد پستی</span>
                                <strong dir="ltr">{{ $address->postal_code }}</strong>
                            </li>
                            <li>
                                <span>شماره تماس</span>
                                <strong dir="ltr">{{ $address->phone_number }}</strong>
                            </li>
                        </ul>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    @include('home.profile.addresses.partials.form-modal', ['address' => null])

    @foreach($user->addresses as $address)
        @include('home.profile.addresses.partials.form-modal', ['address' => $address])

        <div class="modal fade mk-modal" id="deleteAddressModal-{{ $address->id }}" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mk-modal-sm" role="document">
                <div class="modal-content">
                    <header class="mk-modal-head">
                        <div>
                            <h3>حذف آدرس</h3>
                            <p>{{ $address->title }}</p>
                        </div>
                        <button type="button" class="mk-modal-close" data-dismiss="modal" aria-label="بستن">
                            <span class="fa fa-times"></span>
                        </button>
                    </header>

                    <div class="mk-modal-body">
                        <p class="mk-modal-text">
                            این آدرس برای همیشه حذف می‌شود. مطمئن هستید؟
                        </p>
                    </div>

                    <footer class="mk-modal-foot">
                        <button type="button" class="mk-btn mk-btn-ghost" data-dismiss="modal">انصراف</button>
                        <form action="{{ route('home.profile.addresses.destroy', ['address' => $address->id]) }}"
                              method="post" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mk-btn mk-btn-danger">حذف آدرس</button>
                        </form>
                    </footer>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        (function () {
            'use strict';

            var allCities = @json($cities);

            function populateCities(provinceId, citySelect, selectedCityId) {
                if (! citySelect) {
                    return;
                }

                citySelect.innerHTML = '';

                if (! provinceId || ! allCities[provinceId]) {
                    return;
                }

                var placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.disabled = true;
                placeholder.textContent = 'انتخاب شهر…';
                citySelect.appendChild(placeholder);

                Object.keys(allCities[provinceId]).forEach(function (cityId) {
                    var option = document.createElement('option');
                    option.value = cityId;
                    option.textContent = allCities[provinceId][cityId];
                    option.selected = selectedCityId !== null && String(selectedCityId) === String(cityId);
                    citySelect.appendChild(option);
                });
            }

            document.querySelectorAll('.mk-modal').forEach(function (modal) {
                var province = modal.querySelector('[name="province_id"]');
                var city = modal.querySelector('[name="city_id"]');

                if (! province || ! city) {
                    return;
                }

                province.addEventListener('change', function () {
                    populateCities(province.value, city, null);
                });

                modal.addEventListener('shown.bs.modal', function () {
                    var provinceId = city.getAttribute('data-province-id') || province.value;
                    var selectedCity = city.getAttribute('data-selected-city');

                    if (provinceId) {
                        populateCities(provinceId, city, selectedCity);
                    }
                });

                // رویداد بوت‌استرپ با jQuery منتشر می‌شود، پس با jQuery هم گوش می‌دهیم.
                if (window.jQuery) {
                    window.jQuery(modal).on('shown.bs.modal', function () {
                        var provinceId = city.getAttribute('data-province-id') || province.value;
                        var selectedCity = city.getAttribute('data-selected-city');

                        if (provinceId) {
                            populateCities(provinceId, city, selectedCity);
                        }
                    });
                }
            });

            @if($errors->storeAddress->any())
                jQuery('#createAddressModal').modal('show');
            @endif
        })();
    </script>
@endsection
