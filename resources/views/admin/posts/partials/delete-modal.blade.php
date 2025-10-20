<div class="modal w-lg fade justify rtl" id="deletePostModal-{{ $post->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حذف پست: {{ $post->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                آیا از این عملیات مطمئن هستید؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn outlined o-danger c-danger"
                        data-dismiss="modal">بستن</button>
                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn f-main">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
