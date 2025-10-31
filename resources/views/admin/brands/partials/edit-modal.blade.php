<div class="modal w-lg fade light rtl" id="editBrandModal-{{ $brand->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.brands.update', ['brand' => $brand->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش برند: {{ $brand->title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title-{{ $brand->id }}">عنوان:*</label>
                            <input type="text" name="title" id="title-{{ $brand->id }}" class="form-control"
                                   value="{{ $brand->title }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="is_active-{{ $brand->id }}">وضعیت:*</label>
                            <select class="form-control" id="is_active-{{ $brand->id }}" name="is_active" required>
                                <option value="1" {{ $brand->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ $brand->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
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
@if($errors->update->any() && session('brand_id'))
    <script>
        $(function() {
            $('#editBrandModal-{{ session('brand_id') }}').modal({
                show: true
            });
        });
    </script>
@endif
