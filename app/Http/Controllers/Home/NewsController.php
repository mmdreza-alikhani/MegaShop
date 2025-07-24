<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(): View|Application|Factory
    {
        $news = News::IsActive()->latest()->paginate(10);
        return view('home.news.index', compact('news'));
    }

    public function show(News $news): View|Application|Factory
    {
        $related = News::whereHas('tags', function ($q) use ($news) {
            return $q->whereIn('name', $news->tags->pluck('name'));
        })
            ->where('id', '!=', $news->id)
            ->get();

        return view('home.news.show', compact('news', 'related'));
    }
}
