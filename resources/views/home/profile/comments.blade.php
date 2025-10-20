@php use App\Models\Post;use App\Models\Product; @endphp
@extends('home.profile.master')

@section('section')
    <div class="info-box p-5 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6)">
        @forelse($comments as $comment)
            <div class="nk-comment w-100 m-5">
                <div class="nk-comment-meta text-right" style="direction: rtl">
                    <img src="{{ Storage::url(config('upload.user_avatar_path') . '/') . $comment->user->avatar }}" alt="{{ $comment->user->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                    در تاریخ
                    {{ verta($comment->updated_at)->format('%d %B، %Y') }}
                    برای
                    {{ $comment->getCommentableLabel() }}
                    @if($comment->commentable instanceof Product)
                        <a href="{{ route('home.products.show', ['product' => $comment->commentable->slug]) }}">
                            <span style="color: #dd163b">{{ $comment->commentable->title }}</span>
                        </a>
                    @elseif($comment->commentable instanceof Post)
                        <a href="{{ route('home.posts.show', ['post' => $comment->commentable->slug]) }}">
                            <span style="color: #dd163b">{{ $comment->commentable->title }}</span>
                        </a>
                    @else
                        <span style="color: #999">محتوای نامشخص</span>
                    @endif
                    نظر دادی:
                    <span class="badge {{ $comment->getRawOriginal('is_active') ? 'badge-success' : 'badge-secondary' }}">
                        {{ $comment->is_active }}
                    </span>
                    @if($comment->getRawOriginal('status') == 2)
                        <span class="badge badge-danger">
                            {{ $comment->statusCondition() }}
                        </span>
                    @endif
                    @if($comment->product_id != null)
                        <div class="nk-review-rating"
                             data-rating-stars="5"
                             data-rating-value="{{ ceil($comment->user->rates->where('product_id', $comment->product->id)->avg('rate')) }}"
                             data-rating-color="#dd163b"
                             data-rating-readonly="true">
                        </div>
                    @endif
                </div>
                <div class="nk-comment-text text-right" style="direction: rtl">
                    <p class="p-4">
                        {{ $comment->text }}
                    </p>
                </div>
            </div>
            @if(!$loop->last)
                <hr style="background-color: #293139;width: 98%;height: 1px;">
            @endif
        @empty
            <div class="alert alert-danger text-center w-100">
                شما نظری ثبت نکرده اید!
            </div>
        @endforelse
    </div>
@endsection
