<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ShortLink;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View|Application|Factory
    {
        $products = Product::query()
            ->active()
            ->whereHas('variations')
            ->search('title', trim((string) $request->input('q')))
            ->filter()
            ->withCardData()
            ->paginate(12)
            ->withQueryString();

        $platforms = Platform::active()->orderBy('title')->pluck('title', 'id')->values();
        $categories = Category::query()->active()->parents()->orderBy('title')->get(['id', 'title', 'slug']);

        return view('home.products.index', compact('products', 'platforms', 'categories'));
    }

    public function show(Product $product): View|Application|Factory
    {
        abort_unless((bool) $product->getRawOriginal('is_active'), 404);

        $shortLink = ShortLink::where('type', 'product')->where('target_id', $product->id)->value('code');

        $filters = $product->filters()
            ->with('attribute:id,title')
            ->get()
            ->map(fn ($attr) => [
                'title' => $attr->attribute?->title,
                'value' => $attr->value,
            ])
            ->filter(fn ($filter) => $filter['title'] !== null)
            ->values();

        $mapped = $product->variations()
            ->available(1)
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
            ->get()
            ->map(function ($variation) {
                $now = now();

                $hasActiveSale = $variation->sale_price !== null
                    && $variation->date_on_sale_from !== null
                    && $variation->date_on_sale_to !== null
                    && $now->between($variation->date_on_sale_from, $variation->date_on_sale_to);

                return [
                    'id' => $variation->id,
                    'title' => $variation->attribute?->title,
                    'value' => $variation->value,
                    'price' => $variation->price,
                    'quantity' => $variation->quantity,
                    'sku' => $variation->sku,
                    'sale_price' => $variation->sale_price,
                    'date_on_sale_from' => $variation->date_on_sale_from,
                    'date_on_sale_to' => $variation->date_on_sale_to,
                    'is_expired' => ! $hasActiveSale,
                    'effective_price' => $hasActiveSale ? $variation->sale_price : $variation->price,
                ];
            });

        $lowestEffective = $mapped->min('effective_price');

        $cheapestId = $mapped
            ->filter(fn ($variation) => $lowestEffective !== null && $variation['effective_price'] === $lowestEffective)
            ->sortBy('effective_price')
            ->value('id');

        $variations = $mapped->map(function ($variation) use ($cheapestId) {
            $variation['is_cheapest'] = $variation['id'] === $cheapestId;
            unset($variation['effective_price']);

            return $variation;
        });

        $relatedProducts = Product::query()
            ->active()
            ->category($product->category_id)
            ->whereKeyNot($product->id)
            ->withCardData()
            ->take(4)
            ->get();

        return view('home.products.show', compact('product', 'filters', 'variations', 'relatedProducts', 'shortLink'));
    }
}
