<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class BrandController extends Controller
{
    public function show_products(Brand $brand): View|Application|Factory
    {
        $products = $brand->products()->filter()->search()->discount()->paginate(10);

        return view('home.brands.products', compact('products', 'brand'));
    }
}
