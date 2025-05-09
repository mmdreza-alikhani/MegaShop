@foreach($comments as $comment)
    <div class="nk-comment text-right" style="direction: rtl">
        <div class="nk-comment-meta text-right">
            <img src="{{ Str::contains($comment->user->avatar, 'https://') ? $comment->user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $comment->user->avatar }}" alt="{{ $comment->user->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover"> <span style="color: #dd163b">{{ $comment->user->username }} </span>
            در تاریخ
            {{ verta($comment->updated_at)->format('%d %B، %Y') }}
            گفته:
            {{-- @if($comment->user->hasPermissionTo('manage-general'))
                <span class="alert alert-info mx-2">
                    {{ $comment->user->roles->first()->display_name }}
                </span>
            @endif --}}
            <a class="btn nk-btn-color-main-2 replyBtn" data-id="{{ $comment->id }}" href="{{ \Illuminate\Support\Facades\Request::url() . '#addComment' }}">
                پاسخ
            </a>
            @can('manage-comments')
            <a class="btn nk-btn-color-main-5" href="{{ route('admin.comments.changeStatus', ['comment' => $comment->id]) }}">
                تعلیق
            </a>
            <form action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}" method="POST">
                @method('DELETE')
                @csrf
                <button class="btn nk-btn-color-main-1" type="submit">
                    حذف
                </button>
            </form>
            @endcan
            <div class="nk-review-rating"
                 data-rating-stars="5"
                 data-rating-value="{{ ceil($comment->user->rates->where('product_id', $comment->product_id)->avg('rate')) }}"
                 data-rating-color="#dd163b"
                 data-rating-readonly="true">
            </div>
        </div>
        <div class="nk-comment-text text-right" style="direction: rtl">
            <p>
                {{ $comment->text }}
            </p>
        </div>
        @include('home.sections.productComments' , ['comments' => $comment->child])
    </div>
@endforeach
