<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(): View|Application|Factory
    {
        $articles = Post::where('is_active', 1)->latest()->paginate(10);
        return view('home.posts.index', compact('articles'));
    }

    public function show(Post $article): View|Application|Factory
    {
        $related = Post::whereHas('tags', function ($q) use ($article) {
            return $q->whereIn('name', $article->tags->pluck('name'));
        })
            ->where('id', '!=', $article->id)
            ->get();

        return view('home.posts.show', compact('article', 'related'));
    }
}
