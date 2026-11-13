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
    public function index(Category $category): View|Application|Factory
    {
        // ✅ گرفتن ID تمام دسته‌بندی‌های فرزند
        $categoryIds = $category->getAllDescendantIds();

        // ✅ محصولات
        $products = Product::query()
            ->active()
            ->whereIn('category_id', $categoryIds)
            ->filter()
            ->search('title', request('q'))
            ->withCardData()
            ->paginate(10)
            ->withQueryString();

        // ✅ Platforms
        $platforms = Platform::query()->orderBy('title')->pluck('title', 'id');

        $brands = Brand::query()->orderBy('title')->pluck('title', 'id');

        // ✅ فیلترهای مرج شده (بدون تکرار)
//        $filters = $category->getMergedFilters();

        return view('home.categories.index', compact('products', 'category', 'platforms', 'brands'));
    }
}
