<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Attribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\News;
use App\Models\Platform;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class HomeController extends Controller
{
    public function index(): View|Application|Factory
    {
        $banners = Banner::where('is_active' , 1)->where('type' , 'index/top-slider')->limit(4)->get();
        $allNews = News::where('is_active' , 1)->limit(6)->get();
        $articles = Post::where('is_active' , 1)->limit(6)->get();
        $products = Product::where('is_active', 1)->whereHas('variations', function ($query){$query->where('quantity', '>', '0');})->limit(10)->get();
        $platforms = Platform::where('is_active', 1)->where('id', '!=' , '0')->get();

        return view('home.index' , compact('banners', 'allNews', 'articles', 'products', 'platforms'));
    }

    public function globalSearch(): View|Application|Factory
    {
        $products = Product::search('title', trim(request()->keyword))->get();
        $news = News::search('title', trim(request()->keyword))->get();
        $articles = Post::search('title', trim(request()->keyword))->get();
        return view('home.search.index', compact('products', 'allNews', 'articles', 'keyWord'));
    }

    public function aboutUs(): View|Application|Factory
    {
        return view('home.about-us');
    }

    public function contactForm(Request $request): void
    {
        $request->validate([
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|email',
            'message' => 'required|string|min:3|max:2500',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('contact_us')]
        ]);
    }


    public function test(){
        dd(session()->has('coupon') ? session()->get('coupon.id') : null);
    }
}
