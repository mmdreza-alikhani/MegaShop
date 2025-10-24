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
        $posts = Post::active()->latest()->paginate(10);

        return view('home.posts.index', compact('posts'));
    }

    public function show(Post $post): View|Application|Factory
    {
        $shortLink = ShortLink::where('type', 'post')->where('target_id', $post->id)->pluck('code')->first();
        $related = Post::whereHas('tags', function ($q) use ($post) {
            return $q->whereIn('title', $post->tags->pluck('title'));
        })
            ->where('id', '!=', $post->id)
            ->get();

        return view('home.posts.show', compact('post', 'related', 'shortLink'));
    }
}
