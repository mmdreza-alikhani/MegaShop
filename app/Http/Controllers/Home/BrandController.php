<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function show_products(Brand $brand){
        $products = $brand->products()->filter()->search()->discount()->paginate(10);
        return view('home.brands.products', compact('products', 'brand'));
    }
}
