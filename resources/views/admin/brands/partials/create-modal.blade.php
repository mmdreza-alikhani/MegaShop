<div class="modal w-lg fade light rtl" id="createBrandModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.brands.store') }}">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ایجاد برند جدید
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title">نام:*</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="is_active">وضعیت:*</label>
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                    <button type="submit" class="btn main f-main">ایجاد</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    @if($errors->store->any())
    $(function() {
        $('#createBrandModal').modal({
            show: true
        });
    });
    @endif
</script>
