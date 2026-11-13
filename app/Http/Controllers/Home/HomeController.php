<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Platform;
use App\Models\Post;
use App\Models\Product;
use App\Models\WishList;
use App\Services\StorefrontCache;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class HomeController extends Controller
{
    public function index(StorefrontCache $cache): View|Application|Factory
    {
        $data = $cache->rememberHome(function () {
            return [
                'banners' => Banner::query()
                    ->active()
                    ->where('type', 'main/top-slider')
                    ->limit(4)
                    ->get(),
                'articles' => Post::query()
                    ->active()
                    ->article()
                    ->with('author:id,username,avatar')
                    ->withCount('comments')
                    ->take(3)
                    ->get(),
                'news' => Post::query()
                    ->active()
                    ->news()
                    ->with('author:id,username,avatar')
                    ->withCount('comments')
                    ->take(3)
                    ->get(),
                'products' => Product::query()
                    ->active()
                    ->whereHas('variations', fn ($query) => $query->where('quantity', '>', 0))
                    ->with([
                        'variations' => fn ($query) => $query->orderBy('price'),
                        'brand:id,title',
                    ])
                    ->withAvg('rates as rates_avg_rate', 'rate')
                    ->latest()
                    ->limit(10)
                    ->get(),
                'platforms' => Platform::query()->active()->get(),
            ];
        });

        // وضعیت علاقه‌مندی هر کاربر مختص خودش است و در کش ذخیره نمی‌شود.
        $data['products'] = $this->withWishlistState($data['products']);

        return view('home.index', $data);
    }

    /**
     * یک کوئری برای همه محصولات کارت‌ها، به‌جای یک کوئری برای هر کارت.
     */
    private function withWishlistState(Collection $products): Collection
    {
        $wishlistIds = auth()->check()
            ? WishList::query()
                ->where('user_id', auth()->id())
                ->whereIn('product_id', $products->pluck('id'))
                ->pluck('product_id')
                ->all()
            : [];

        $products->each(function (Product $product) use ($wishlistIds) {
            $product->in_wishlist = in_array($product->id, $wishlistIds, true);
        });

        return $products;
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
