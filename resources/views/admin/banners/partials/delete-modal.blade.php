<div class="modal w-lg fade justify rtl" id="deleteBannerModal-{{ $banner->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حذف بنر: {{ $banner->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                آیا از این عملیات مطمئن هستید؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn outlined o-danger c-danger"
                        data-dismiss="modal">بازگشت</button>
                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn f-main">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
