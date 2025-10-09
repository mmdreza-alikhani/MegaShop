<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Platform;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class SearchController extends Controller
{
    public function search(): View|Application|Factory
    {
        $keyword = trim(request()->input('q'));
        $products = Product::search('title', $keyword)->get();
        $posts = Post::search('title', $keyword)->get();

        return view('home.search.index', compact('products', 'posts', 'keyword'));
    }
}
