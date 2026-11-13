<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ShortLink;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class PostController extends Controller
{
    public function index(): View|Application|Factory
    {
        $posts = Post::query()
            ->active()
            ->with('author:id,username,avatar')
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('home.posts.index', compact('posts'));
    }

    public function show(Post $post): View|Application|Factory
    {
        $post->loadMissing(['author:id,username,avatar', 'tags']);

        $shortLink = ShortLink::where('type', 'post')->where('target_id', $post->id)->value('code');

        $related = Post::query()
            ->active()
            ->whereHas('tags', fn ($q) => $q->whereIn('title', $post->tags->pluck('title')))
            ->whereKeyNot($post->id)
            ->with('author:id,username,avatar')
            ->withCount('comments')
            ->take(3)
            ->get();

        return view('home.posts.show', compact('post', 'related', 'shortLink'));
    }
}
