<div class="modal w-lg fade light rtl" id="editPlatformModal-{{ $platform->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.platforms.update', ['platform' => $platform->id]) }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش پلتفرم: {{ $platform->title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="title-{{ $platform->id }}">عنوان:*</label>
                            <input type="text" name="title" id="title-{{ $platform->id }}" class="form-control"
                                   value="{{ $platform->title }}">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="is_active-{{ $platform->id }}">وضعیت:*</label>
                            <select class="form-control" id="is_active-{{ $platform->id }}" name="is_active">
                                <option value="1" {{ $platform->getRawOriginal('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ $platform->getRawOriginal('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label class="d-block mb-2 text-right" for="image-{{ $platform->id }}">تصویر جدید</label>
                            <div class="d-flex flex-row-reverse align-items-center border rounded p-2 imageInsertDiv" style="background-color: #f8f9fa;">
                                <span class="btn f-primary ml-2 px-2 imageInsertBtn" onclick="document.getElementById('image-{{ $platform->id }}').click();">
                                    انتخاب فایل
                                </span>
                                <span id="image-file-name-{{ $platform->id }}" class="text-muted flex-grow-1 text-right">
                                    هیچ فایلی انتخاب نشده
                                </span>
                                <input type="file" id="image-{{ $platform->id }}" name="image" class="d-none" lang="fa">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn main f-main">ویرایش</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const imageInput_{{ $platform->id }} = document.getElementById('image-{{ $platform->id }}');
    const imageFileNameDisplay_{{ $platform->id }} = document.getElementById('image-file-name-{{ $platform->id }}');

    imageInput_{{ $platform->id }}.addEventListener('change', function () {
        imageFileNameDisplay_{{ $platform->id }}.textContent = this.files[0]?.name || 'هیچ فایلی انتخاب نشده';
    });

    @if($errors->update->any() && session('platform_id'))
    $(function() {
        $('#editPlatformModal-{{ session('platform_id') }}').modal({
            show: true
        });
    });
    @endif
</script>
