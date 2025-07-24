<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show_category(Category $category): View|Application|Factory
    {
        $products = $category->products()->filter()->search()->discount()->paginate(10);
        $attributes = $category->attributes()->where('is_filter' , 1)->with('values')->get();
        $variation = $category->attributes()->where('is_variation' , 1)->with('variationValues')->first();

        return view('home.categories.show', compact('products', 'category' , 'attributes', 'variation'));

    }
}
