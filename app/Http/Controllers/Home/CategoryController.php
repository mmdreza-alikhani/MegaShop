<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Platform;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class CategoryController extends Controller
{
    public function show_category(Category $category): View|Application|Factory
    {
        $products = $category->products()->filter()->search()->paginate(10);
        $platforms = Platform::pluck('title', 'id');
        $filters = $category->filters()->with('filterValues')->get();
        $variation = $category->variation()->with('variationValues')->first();

        return view('home.categories.index', compact('products', 'category', 'filters', 'variation', 'platforms'));
    }
}
