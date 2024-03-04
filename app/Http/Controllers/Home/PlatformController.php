<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function show_products(Platform $platform){
        $products = $platform->products()->filter()->search()->discount()->paginate(10);
        return view('home.platforms.products', compact('products', 'platform'));
    }
}
