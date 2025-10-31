<div class="modal w-lg fade light rtl" id="editCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.coupons.update', ['coupon' => $coupon->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش کد تخفیف: {{ $coupon->title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title-{{ $coupon->id }}">عنوان:*</label>
                            <input type="text" name="title" id="title-{{ $coupon->id }}" class="form-control" value="{{ $coupon->title }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="code-{{ $coupon->id }}">کد:*</label>
                            <input type="text" name="code" id="code-{{ $coupon->id }}" class="form-control" value="{{ $coupon->code }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="expired_at-{{ $coupon->id }}">تاریخ انقضا:*</label>
                            <input data-jdp name="expired_at" id="expired_at-{{ $coupon->id }}" class="form-control"
                                   value="{{ verta($coupon->expired_at)->format('Y/m/d H:i:s') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="type-{{ $coupon->id }}">نوع:*</label>
                            <select class="form-control" id="type-{{ $coupon->id }}" name="type" required>
                                <option value="amount" {{ $coupon->getRawOriginal('type') == 'amount' ? 'selected' : '' }}>مبلغی</option>
                                <option value="percentage" {{ $coupon->getRawOriginal('type') == 'percentage' ? 'selected' : '' }}>درصدی</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="amount-{{ $coupon->id }}">مبلغ:*</label>
                            <input type="number" name="amount" id="amount-{{ $coupon->id }}" class="form-control" value="{{ $coupon->amount }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="percentage-{{ $coupon->id }}">درصد:*</label>
                            <input type="number" min="0" max="100" name="percentage" id="percentage-{{ $coupon->id }}" class="form-control" value="{{ $coupon->percentage }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="max_percentage_amount-{{ $coupon->id }}">حداکثر مبلغ برای نوع درصدی:*</label>
                            <input type="number" name="max_percentage_amount" id="max_percentage_amount-{{ $coupon->id }}"
                                   class="form-control" value="{{ $coupon->max_percentage_amount }}">
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label for="description-{{ $coupon->id }}">توضیحات:*</label>
                            <textarea type="text" name="description" id="description-{{ $coupon->id }}" class="form-control" required>{{ $coupon->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                    <button type="submit" class="btn main f-main">ویرایش</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const $type = $('#type-{{ $coupon->id }}');
    const $amount = $('#amount-{{ $coupon->id }}');
    const $percentage = $('#percentage-{{ $coupon->id }}');
    const $max = $('#max_percentage_amount-{{ $coupon->id }}');

    const $amountGroup = $amount.closest('.form-group');
    const $percentageGroup = $percentage.closest('.form-group');
    const $maxGroup = $max.closest('.form-group');

    function toggleFields(val) {
        if (val === 'amount') {
            $amount.prop({required: true, disabled: false}).attr('name', 'amount');
            $percentage.prop({required: false, disabled: true}).removeAttr('name');
            $max.prop({required: false, disabled: true}).removeAttr('name');

            $amountGroup.removeClass('fade-disable').addClass('fade-enable');
            $percentageGroup.add($maxGroup).removeClass('fade-enable').addClass('fade-disable');
        } else {
            $amount.prop({required: false, disabled: true}).removeAttr('name');
            $percentage.prop({required: true, disabled: false}).attr('name', 'percentage');
            $max.prop({required: true, disabled: false}).attr('name', 'max_percentage_amount');

            $amountGroup.removeClass('fade-enable').addClass('fade-disable');
            $percentageGroup.add($maxGroup).removeClass('fade-disable').addClass('fade-enable');
        }
    }

    toggleFields($type.val());
    $type.on('change', function () {
        toggleFields(this.value);
    });
    @if($errors->update->any() && session('coupon_id'))
        $(function() {
            $('#editCouponModal-{{ session('coupon_id') }}').modal({
                show: true
            });
        });
    @endif
</script>
