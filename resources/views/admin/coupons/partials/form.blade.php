@php
    $isEdit = isset($coupon) && $coupon;
    $action = $isEdit ? route('admin.coupons.update', ['coupon' => $coupon]) : route('admin.coupons.store');
    $errorBag = $isEdit ? 'update' : 'store';
    $type = old('type', $isEdit ? $coupon->getRawOriginal('type') : 'percentage');
    $expiredAt = old('expired_at', $isEdit && $coupon->expired_at ? verta($coupon->expired_at)->format('Y/m/d') : '');
@endphp

<form method="POST" action="{{ $action }}" class="mk-product-form" id="couponForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @include('admin.layout.errors', ['errors' => $errors->{$errorBag}])

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>اطلاعات کد تخفیف</h4>
                <p>عنوان، کد یکتا، نوع تخفیف و تاریخ انقضا</p>
            </div>
            @if($isEdit)
                <span class="mk-panel-chip">
                    {{ number_format($coupon->orders_count ?? 0) }} بار استفاده شده
                </span>
            @endif
        </header>

        <div class="mk-form-grid">
            <div class="mk-field mk-field-full">
                <label for="title">عنوان <em>*</em></label>
                <input type="text" name="title" id="title" class="form-control" maxlength="255"
                       value="{{ old('title', $isEdit ? $coupon->title : '') }}" required>
                <small>این عنوان فقط در پنل مدیریت دیده می‌شود.</small>
            </div>

            <div class="mk-field">
                <label for="code">کد تخفیف <em>*</em></label>
                <input type="text" name="code" id="code" class="form-control" dir="ltr" maxlength="255"
                       value="{{ old('code', $isEdit ? $coupon->code : '') }}" placeholder="MEGA25" required>
            </div>

            <div class="mk-field">
                <label for="type">نوع تخفیف <em>*</em></label>
                <select name="type" id="type" class="form-control" required>
                    <option value="percentage" @selected($type === 'percentage')>درصدی</option>
                    <option value="amount" @selected($type === 'amount')>مبلغی (تومان)</option>
                </select>
            </div>

            <div class="mk-field">
                <label for="expired_at">تاریخ انقضا <em>*</em></label>
                <input type="text" name="expired_at" id="expired_at" class="form-control" data-jdp
                       value="{{ $expiredAt }}" placeholder="1405/01/01" required>
            </div>

            <div class="mk-field">
                <label for="is_active">وضعیت</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" @selected((string) old('is_active', $isEdit ? $coupon->getRawOriginal('is_active') : '1') === '1')>فعال</option>
                    <option value="0" @selected((string) old('is_active', $isEdit ? $coupon->getRawOriginal('is_active') : '1') === '0')>غیرفعال</option>
                </select>
            </div>

            <div class="mk-field" data-coupon-field="amount">
                <label for="amount">مبلغ تخفیف (تومان) <em>*</em></label>
                <input type="number" min="0" step="1000" name="amount" id="amount" class="form-control"
                       value="{{ old('amount', $isEdit ? $coupon->amount : '') }}">
            </div>

            <div class="mk-field" data-coupon-field="percentage">
                <label for="percentage">درصد تخفیف <em>*</em></label>
                <input type="number" min="1" max="100" name="percentage" id="percentage" class="form-control"
                       value="{{ old('percentage', $isEdit ? $coupon->percentage : '') }}">
            </div>

            <div class="mk-field" data-coupon-field="percentage">
                <label for="max_percentage_amount">سقف تخفیف درصدی (تومان) <em>*</em></label>
                <input type="number" min="0" step="1000" name="max_percentage_amount" id="max_percentage_amount"
                       class="form-control"
                       value="{{ old('max_percentage_amount', $isEdit ? $coupon->max_percentage_amount : '') }}">
            </div>

            <div class="mk-field mk-field-full">
                <label for="description">توضیحات <em>*</em></label>
                <textarea name="description" id="description" class="form-control" rows="3" maxlength="255"
                          required>{{ old('description', $isEdit ? $coupon->description : '') }}</textarea>
            </div>
        </div>
    </section>

    <div class="mk-form-actions mk-product-submit">
        <button type="submit" class="btn btn-info">
            <i class="fas fa-check"></i>
            {{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد کد تخفیف' }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">انصراف</a>
    </div>
</form>

<script>
    (function () {
        var type = document.getElementById('type');
        var amountField = document.querySelector('[data-coupon-field="amount"]');
        var percentageFields = document.querySelectorAll('[data-coupon-field="percentage"]');
        var amountInput = document.getElementById('amount');
        var percentageInput = document.getElementById('percentage');
        var maxInput = document.getElementById('max_percentage_amount');

        function sync() {
            var isAmount = type.value === 'amount';

            amountField.classList.toggle('d-none', ! isAmount);
            percentageFields.forEach(function (field) {
                field.classList.toggle('d-none', isAmount);
            });

            amountInput.required = isAmount;
            amountInput.disabled = ! isAmount;
            percentageInput.required = ! isAmount;
            percentageInput.disabled = isAmount;
            maxInput.required = ! isAmount;
            maxInput.disabled = isAmount;
        }

        type.addEventListener('change', sync);
        sync();

        if (window.jalaliDatepicker) {
            jalaliDatepicker.startWatch({ minDate: 'today', time: false });
        }
    })();
</script>
