<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product): View|Application|Factory
    {
        $relatedProducts = Product::where('is_active', 1)->where('category_id', $product->category->id)->get();
        $variationName = Attribute::find($product->variations->first()->attribute_id)->name;
        $productAttributes = $product->attributes()->with('attribute')->get();

        return view('home.products.show', compact('product', 'productAttributes', 'variationName', 'relatedProducts'));
    }
}
