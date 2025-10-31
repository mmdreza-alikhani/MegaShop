<div class="modal w-lg fade light rtl" id="editAttributeModal-{{ $attribute->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.attributes.update', ['attribute' => $attribute->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش ویژگی: {{ $attribute->title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title-{{ $attribute->id }}">عنوان:*</label>
                            <input type="text" name="title" id="title-{{ $attribute->id }}" class="form-control"
                                   value="{{ $attribute->title }}" required>
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
@if($errors->update->any() && session('attribute_id'))
    <script>
        $(function() {
            $('#editAttributeModal-{{ session('attribute_id') }}').modal({
                show: true
            });
        });
    </script>
@endif
