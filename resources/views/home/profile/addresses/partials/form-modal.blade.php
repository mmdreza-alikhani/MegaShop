@php
    $isEdit = isset($address) && $address;
    $suffix = $isEdit ? '-' . $address->id : '';
    $modalId = $isEdit ? 'editAddressModal-' . $address->id : 'createAddressModal';
    $bag = $isEdit ? 'updateAddress' : 'storeAddress';
    $action = $isEdit
        ? route('home.profile.addresses.update', ['address' => $address->id])
        : route('home.profile.addresses.store');
@endphp

<div class="modal fade mk-modal" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <header class="mk-modal-head">
                <div>
                    <h3>{{ $isEdit ? 'ویرایش آدرس' : 'ثبت آدرس جدید' }}</h3>
                    <p>{{ $isEdit ? $address->title : 'آدرس ارسال سفارش‌ها را وارد کنید' }}</p>
                </div>
                <button type="button" class="mk-modal-close" data-dismiss="modal" aria-label="بستن">
                    <span class="fa fa-times"></span>
                </button>
            </header>

            <form action="{{ $action }}" method="post" class="mk-modal-form">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mk-modal-body">
                    @include('home.sections.errors', ['errors' => $errors->{$bag}])

                    <div class="mk-modal-grid">
                        <div class="mk-field mk-field-full">
                            <label for="title{{ $suffix }}">عنوان آدرس <em>*</em></label>
                            <input type="text" name="title" id="title{{ $suffix }}" maxlength="255" required
                                   placeholder="مثلاً: خانه، محل کار"
                                   value="{{ old('title', $isEdit ? $address->title : '') }}">
                        </div>

                        <div class="mk-field">
                            <label for="postal_code{{ $suffix }}">کد پستی <em>*</em></label>
                            <input type="text" name="postal_code" id="postal_code{{ $suffix }}" dir="ltr"
                                   inputmode="numeric" maxlength="10" required placeholder="1234567890"
                                   value="{{ old('postal_code', $isEdit ? $address->postal_code : '') }}">
                        </div>

                        <div class="mk-field">
                            <label for="phone_number{{ $suffix }}">شماره موبایل <em>*</em></label>
                            <div class="mk-phone">
                                <span>+98</span>
                                <input type="tel" name="phone_number" id="phone_number{{ $suffix }}" dir="ltr"
                                       minlength="10" maxlength="10" required placeholder="9121234567"
                                       value="{{ old('phone_number', $isEdit ? $address->phone_number : '') }}">
                            </div>
                        </div>

                        <div class="mk-field">
                            <label for="provinceSelect{{ $suffix }}">استان <em>*</em></label>
                            <select name="province_id" id="provinceSelect{{ $suffix }}" required>
                                <option value="" disabled @selected(! old('province_id', $isEdit ? $address->province_id : null))>
                                    انتخاب استان…
                                </option>
                                @foreach($provinces as $key => $value)
                                    <option value="{{ $key }}"
                                        @selected((string) old('province_id', $isEdit ? $address->province_id : '') === (string) $key)>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mk-field">
                            <label for="citySelect{{ $suffix }}">شهر <em>*</em></label>
                            <select name="city_id" id="citySelect{{ $suffix }}" required
                                    data-province-id="{{ $isEdit ? $address->province_id : '' }}"
                                    data-selected-city="{{ $isEdit ? $address->city_id : '' }}">
                                @if($isEdit && $address->city)
                                    <option value="{{ $address->city_id }}" selected>{{ $address->city->name }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="mk-field mk-field-full">
                            <label for="address{{ $suffix }}">نشانی کامل <em>*</em></label>
                            <textarea name="address" id="address{{ $suffix }}" rows="3" maxlength="255" required
                                      placeholder="خیابان، کوچه، پلاک و واحد">{{ old('address', $isEdit ? $address->address : '') }}</textarea>
                        </div>
                    </div>
                </div>

                <footer class="mk-modal-foot">
                    <button type="button" class="mk-btn mk-btn-ghost" data-dismiss="modal">انصراف</button>
                    <button type="submit" class="mk-btn mk-btn-primary">
                        {{ $isEdit ? 'ذخیره تغییرات' : 'ثبت آدرس' }}
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
