<div class="modal w-lg fade light rtl" id="createPlatformModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.platforms.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ایجاد پلتفرم جدید
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
                        <div class="form-group col-12 col-lg-12">
                            <label class="d-block mb-2 text-right" for="image">تصویر اصلی</label>
                            <div class="d-flex flex-row-reverse align-items-center border rounded p-2 imageInsertDiv" style="background-color: #f8f9fa;">
                                                <span class="btn f-primary ml-2 px-2 imageInsertBtn" onclick="document.getElementById('image').click();">
                                                    انتخاب فایل
                                                </span>
                                <span id="image-file-name" class="text-muted flex-grow-1 text-right">
                                                    هیچ فایلی انتخاب نشده
                                                </span>
                                <input type="file" id="image" name="image" class="d-none" lang="fa" required>
                            </div>
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
    const imageInput = document.getElementById('image');
    const imageFileNameDisplay = document.getElementById('image-file-name');

    imageInput.addEventListener('change', function () {
        imageFileNameDisplay.textContent = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
    });
    @if($errors->store->any())
    $(function() {
        $('#createPlatformModal').modal({
            show: true
        });
    });
    @endif
</script>
