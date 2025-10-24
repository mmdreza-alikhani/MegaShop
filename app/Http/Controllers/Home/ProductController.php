<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ShortLink;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function index(): View|Application|Factory
    {
        $products = Product::latest()->paginate(10);
        $platforms = Platform::pluck('title', 'id');
//        $filters = $category->filters()->with('filterValues')->get();
//        $variation = $category->variation()->with('variationValues')->first();

        return view('home.products.index', compact('products', 'platforms'));
    }
    public function show(Product $product): View|Application|Factory
    {
        $shortLink = ShortLink::where('type', 'product')->where('target_id', $product->id)->pluck('code')->first();
        $filters = $product->filters()
            ->with('attribute')
            ->get()
            ->map(function ($attr) {
                return [
                    'title' => $attr->attribute->title,
                    'value' => $attr->value,
                ];
            });

        $variationsRaw = $product->variations()
            ->available(4)
            ->with(['attribute:id,title'])
            ->select([
                'id',
                'attribute_id',
                'value',
                'price',
                'quantity',
                'sku',
                'sale_price',
                'date_on_sale_from',
                'date_on_sale_to',
            ])
            ->get();

        $mapped = $variationsRaw->map(function ($attr) {
            $now = now();

            $hasSalePrice = $attr->sale_price !== null;
            $hasStartDate = $attr->date_on_sale_from !== null;
            $hasEndDate = $attr->date_on_sale_to !== null;

            $isWithinRange = $hasStartDate && $hasEndDate &&
                $now->between($attr->date_on_sale_from, $attr->date_on_sale_to);

            $isExpired = ! $hasSalePrice || ! $hasStartDate || ! $hasEndDate || ! $isWithinRange;

            $effectivePrice = ! $isExpired ? $attr->sale_price : $attr->price;

            return [
                'id' => $attr->id,
                'title' => $attr->attribute->title,
                'value' => $attr->value,
                'price' => $attr->price,
                'quantity' => $attr->quantity,
                'sku' => $attr->sku,
                'sale_price' => $attr->sale_price,
                'date_on_sale_from' => $attr->date_on_sale_from,
                'date_on_sale_to' => $attr->date_on_sale_to,
                'is_expired' => $isExpired,
                'effective_price' => $effectivePrice,
            ];
        });

        $lowestEffective = $mapped->min('effective_price');

        $cheapest = $mapped
            ->filter(fn ($v) => $v['effective_price'] === $lowestEffective)
            ->sortByDesc('has_active_sale') // Sale gets priority
            ->first();

        $variations = $mapped->map(function ($v) use ($cheapest) {
            $v['is_cheapest'] = $v['id'] === $cheapest['id'];
            unset($v['effective_price'], $v['has_active_sale']);

            return $v;
        });

        $relatedProducts = Product::active()->category($product->category_id)->take(4)->get();

        return view('home.products.show', compact('product', 'filters', 'variations', 'relatedProducts', 'shortLink'));
    }
}
