<div class="modal w-lg fade light rtl" id="showCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content card shade">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    نمایش کد تخفیف: {{ $coupon->title }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label for="title-{{ $coupon->id }}-show">عنوان:*</label>
                        <input type="text" id="title-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->title }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="code-{{ $coupon->id }}-show">کد:*</label>
                        <input type="text" id="code-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->code }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="expired_at-{{ $coupon->id }}-show">تاریخ انقضا:*</label>
                        <input id="expired_at-{{ $coupon->id }}-show" class="form-control" value="{{ verta($coupon->expired_at) }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="type-{{ $coupon->id }}-show">نوع:*</label>
                        <select class="form-control" id="type-{{ $coupon->id }}-show" name="type" disabled>
                            <option value="amount" {{ $coupon->type == 'amount' ? 'selected' : '' }}>مبلغی</option>
                            <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>درصدی</option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="amount-{{ $coupon->id }}-show">مبلغ:*</label>
                        <input type="number" id="amount-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->amount }}" disabled>
                        <script>convertNumberToPersianWords({{ $coupon->amount }})</script>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="percentage-{{ $coupon->id }}-show">درصد:*</label>
                        <input type="number" min="0" max="100" id="percentage-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->percentage }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="max_percentage_amount-{{ $coupon->id }}-show">حداکثر مبلغ برای نوع درصدی:*</label>
                        <input type="number" id="max_percentage_amount-{{ $coupon->id }}-show"
                               class="form-control" value="{{ $coupon->max_percentage_amount }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-12">
                        <label for="description-{{ $coupon->id }}-show">توضیحات:*</label>
                        <textarea type="text" name="description" id="description-{{ $coupon->id }}-show" class="form-control" disabled>{{ $coupon->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
