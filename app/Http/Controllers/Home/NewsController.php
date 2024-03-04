<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(){
        $allNews = News::where('is_active', 1)->latest()->paginate(10);
        return view('home.news.index', compact('allNews'));
    }

    public function show(News $news){

        $related = News::whereHas('tags', function ($q) use ($news) {
            return $q->whereIn('name', $news->tags->pluck('name'));
        })
            ->where('id', '!=', $news->id)
            ->get();

        return view('home.news.show', compact('news', 'related'));
    }
}
