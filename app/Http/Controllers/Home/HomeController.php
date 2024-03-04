<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Attribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\News;
use App\Models\Platform;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class HomeController extends Controller
{
    public function index(){

        SEOTools::setTitle('خانه');
        SEOTools::setDescription('دری به سوی دنیای گیم');
        SEOTools::opengraph()->setUrl(route('home.index'));
        SEOTools::setCanonical('https://codecasts.com.br/lesson');
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::twitter()->setSite('@LuizVinicius73');
        SEOTools::jsonLd()->addImage('https://codecasts.com.br/img/logo.jpg');

        $banners = Banner::where('is_active' , 1)->where('type' , 'index/top-slider')->limit(4)->get();
        $allNews = News::where('is_active' , 1)->limit(6)->get();
        $articles = Article::where('is_active' , 1)->limit(6)->get();
        $products = Product::where('is_active', 1)->whereHas('variations', function ($query){$query->where('quantity', '>', '0');})->limit(10)->get();
        $platforms = Platform::where('is_active', 1)->where('id', '!=' , '0')->get();

        return view('home.index' , compact('banners', 'allNews', 'articles', 'products', 'platforms'));
    }

    public function generalSearch(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $products = Product::where('is_active', 1)->where('name', 'LIKE', '%'.trim($keyWord).'%')->get();
            $allNews = News::where('is_active', 1)->where('title', 'LIKE', '%'.trim($keyWord).'%')->get();
            $articles = Article::where('is_active', 1)->where('title', 'LIKE', '%'.trim($keyWord).'%')->get();
            return view('home.search.index', compact('products', 'allNews', 'articles', 'keyWord'));
        }
    }

    public function aboutUs(){
        return view('home.about-us');
    }

    public function contactForm(Request $request){
        $request->validate([
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|email',
            'message' => 'required|string|min:3|max:2500',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('contact_us')]
        ]);
        dd('aa');
    }

//    public function products(){
//        $products = Product::where('is_active', 1)->latest()->paginate(10);
//        return view('home.products', compact('products'));
//    }
//
//    public function platforms(Platform $platform){
//        $platformProducts = Product::where('is_active', 1)->where('platform_id', $platform->id)->latest()->paginate(10);
//        $platforms = Platform::where('is_active', 1)->get();
//        return view('home.products', compact('platformProducts', 'platforms'));
//    }

    public function test(){
        dd(session()->has('coupon') ? session()->get('coupon.id') : null);
    }
}
