<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function show_category(Category $category): View|Application|Factory
    {
        Cache::clear();
        // ✅ گرفتن ID تمام دسته‌بندی‌های فرزند
        $categoryIds = $category->getAllDescendantIds();

        // ✅ محصولات
        $products = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->filter()
            ->search('title', request('q'))
            ->with(['category:id,title', 'brand:id,title'])
            ->paginate(10);

        // ✅ Platforms
        $platforms = Cache::remember('platforms_list', now()->addHour(), fn() =>
            Platform::pluck('title', 'id')
        );

        $brands = Cache::remember('brands_list', now()->addHour(), fn() =>
            Brand::pluck('title', 'id')
        );

        // ✅ فیلترهای مرج شده (بدون تکرار)
//        $filters = $category->getMergedFilters();

        return view('home.categories.index', compact('products', 'category', 'platforms', 'brands'));
    }
}
