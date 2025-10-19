<div class="modal w-lg fade light rtl" id="createCouponModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.coupons.store') }}">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ایجاد کد تخفیف جدید
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title">عنوان:*</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="code">کد:*</label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="expired_at">تاریخ انقضا:*</label>
                            <input data-jdp name="expired_at" id="expired_at" class="form-control"
                                   value="{{ old('expired_at') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="type">نوع:*</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="amount" {{ old('type') == 'amount' ? 'selected' : '' }}>مبلغی</option>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>درصدی</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="amount">مبلغ:*</label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="percentage">درصد:*</label>
                            <input type="number" min="0" max="100" name="percentage" id="percentage" class="form-control" value="{{ old('percentage') }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصدی:*</label>
                            <input type="number" name="max_percentage_amount" id="max_percentage_amount"
                                   class="form-control" value="{{ old('max_percentage_amount') }}">
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label for="description">توضیحات:*</label>
                            <textarea type="text" name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn main f-main">ایجاد</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    @if($errors->store->any())
    $(function() {
        $('#createCouponModal').modal({
            show: true
        });
    });
    @endif
</script>
