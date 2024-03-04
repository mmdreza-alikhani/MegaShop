<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(){
        $articles = Article::where('is_active', 1)->latest()->paginate(10);
        return view('home.articles.index', compact('articles'));
    }

    public function show(Article $article){

        $related = Article::whereHas('tags', function ($q) use ($article) {
            return $q->whereIn('name', $article->tags->pluck('name'));
        })
            ->where('id', '!=', $article->id)
            ->get();

        return view('home.articles.show', compact('article', 'related'));
    }
}
