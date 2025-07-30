<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class PlatformController extends Controller
{
    public function show_products(Platform $platform): View|Application|Factory
    {
        $products = $platform->products()->filter()->search()->discount()->paginate(10);

        return view('home.platforms.products', compact('products', 'platform'));
    }
}
