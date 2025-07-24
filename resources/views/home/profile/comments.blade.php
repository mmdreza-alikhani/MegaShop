@extends('home.profile.master')

@php
    $active = 'comments';
@endphp

@section('section')
    <div class="info-box p-5 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6)">

    @if($user->comments->isEmpty())
        <div class="alert alert-danger text-center w-100">
            شما نظری ثبت نکرده اید!
        </div>
    @endif

    @foreach($user->comments as $comment)
            <div class="nk-comment w-100 m-5">
                <div class="nk-comment-meta text-right" style="direction: rtl">
                    <img src="{{ Str::contains($comment->user->avatar, 'https://') ? $comment->user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $comment->user->avatar }}" alt="{{ $comment->user->username }}-image" class="rounded-circle" style="width: 50px;height: 50px;object-fit: cover">
                    در تاریخ
                    {{ verta($comment->updated_at)->format('%d %B، %Y') }}
                    @if($comment->product_id != null)
                        برای محصول
                        <a href="{{ route('home.products.show', ['product' => $comment->product->slug]) }}"><span style="color: #dd163b">{{ $comment->product->name }}</span></a>
                    @elseif($comment->article_id != null)
                        برای مقاله
                        <a href="{{ route('home.posts.show', ['article' => $comment->article->slug]) }}"><span style="color: #dd163b">{{ $comment->article->title }}</span></a>
                    @else
                        برای خبر
                        <a href="{{ route('home.news.show', ['news' => $comment->news->slug]) }}"><span style="color: #dd163b">{{ $comment->news->title }}</span></a>
                    @endif
                    نظر دادی:
                    <span class="badge {{ $comment->getRawOriginal('approved') ?  'badge-success' : 'badge-secondary' }}">
                            {{$comment->approved}}
                    </span>
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
                <hr style="background-color: #293139;width: 98%;height: .5px;">
            @endif
    @endforeach

    </div>
@endsection
