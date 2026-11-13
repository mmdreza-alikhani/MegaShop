@foreach($comments as $comment)
    <div class="nk-comment text-right" style="direction: rtl">
        <div class="nk-comment-meta text-right">
            <img src="{{ Storage::url(config('upload.user_avatar_path') . '/' . ($comment->user?->avatar ?? 'avatar.png')) }}"
                 alt="{{ $comment->user?->username }}" class="rounded-circle"
                 style="width: 50px;height: 50px;object-fit: cover" loading="lazy">
            <span style="color: #dd163b">{{ $comment->user?->username ?? 'کاربر' }}</span>
            در تاریخ
            {{ verta($comment->updated_at)->format('%d %B، %Y') }}
            گفته:
            <a class="btn nk-btn-color-main-1 replyBtn" data-id="{{ $comment->id }}"
               href="{{ url()->current() }}#addComment">
                پاسخ
            </a>
        </div>
        <div class="nk-comment-text text-right" style="direction: rtl">
            <p>{{ $comment->text }}</p>
        </div>
        @if($comment->child->isNotEmpty())
            @include('home.sections.comments', ['comments' => $comment->child])
        @endif
    </div>
@endforeach
