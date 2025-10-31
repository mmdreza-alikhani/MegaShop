<div class="modal w-lg fade light rtl" id="createTagModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.tags.store') }}">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ایجاد برچسب جدید
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title">
                                نام:*</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title') }}" required>
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
        $('#createTagModal').modal({
            show: true
        });
    });
    @endif
</script>
