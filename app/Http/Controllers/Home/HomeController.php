<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Platform;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class HomeController extends Controller
{
    public function index(): View|Application|Factory
    {
        $banners = Banner::active()->where('type', 'main/top-slider')->limit(4)->get();
        $articles = Post::active()->article()->take(3)->get();
        $news = Post::active()->news()->take(3)->get();
        $products = Product::query()
            ->active()
            ->whereHas('variations', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->withCardData()
            ->latest()
            ->limit(10)
            ->get();
        $platforms = Platform::active()->get();

        return view('home.index', compact('banners', 'news', 'articles', 'products', 'platforms'));
    }

    public function aboutUs(): View|Application|Factory
    {
        return view('home.about-us');
    }

    public function contact(): View|Application|Factory
    {
        return view('home.contact');
    }

    public function contactForm(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|min:3|max:255',
            'message' => 'required|string|min:3|max:2500',
        ];

        // در نسخه دمو reCAPTCHA غیرفعال است؛ فقط در صورت فعال بودن بررسی می‌شود
        if (config('demo.recaptcha_enabled')) {
            $rules['g-recaptcha-response'] = [new GoogleReCaptchaV3ValidationRule('contact_us')];
        }

        $validated = $request->validate($rules);

        ContactUs::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'text' => $validated['message'],
        ]);

        flash()->success('پیام شما با موفقیت ثبت شد. در اسرع وقت پاسخ می‌دهیم.');

        return redirect()->back();
    }
}
