<nav class="main-header navbar navbar-expand bg-dark navbar-dark border-bottom">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <li class="nav-item d-sm-inline-block">
            <a href="{{ route('admin.panel') }}" class="nav-link">خانه</a>
        </li>
        <li class="nav-item d-sm-inline-block">
            <a href="{{ route('home.index') }}" class="nav-link">بازگشت به سایت</a>
        </li>
    </ul>

    @php
        $comments = \App\Models\Comment::latest()->where('approved', '0')->take(3)->get();
    @endphp

    <ul class="navbar-nav mr-auto">
        @can('manage-comments')
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-comments-o"></i>
                <span class="badge badge-danger navbar-badge">{{ $comments->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left bg-dark">
                @if($comments->count() == 0)
                    <p class="text-center my-2">
                        نظری موجود نیست!
                    </p>
                @else
                @foreach($comments as $comment)
                    <a href="{{ route('admin.comments.show' , [$comment->id]) }}" class="dropdown-item">
                        <div class="media">
                            <img src="{{ Str::contains($comment->user->avatar, 'https://') ? $comment->user->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . $comment->user->avatar }}" alt="{{ $comment->user->username }}-image" class="img-size-50 ml-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{ $comment->user->username }}
                                </h3>
                                <p class="text-sm">
                                    {{ substr(strip_tags($comment->text), 0 , 40) . ' ...' }}
                                </p>
                                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i>
                                {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach
                <a href="{{ route('admin.comments.index') }}" class="dropdown-item dropdown-footer">مشاهده همه نظرات</a>
                @endif
            </div>
        </li>
        @endcan
    </ul>
</nav>
