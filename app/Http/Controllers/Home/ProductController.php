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
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    public function show(Product $product): View|Application|Factory
    {
        $filters = $product->attributes()
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
            $saleValid = $attr->sale_price !== null &&
                now()->between($attr->date_on_sale_from, $attr->date_on_sale_to);

            $effectivePrice = $saleValid ? $attr->sale_price : $attr->price;

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
                'effective_price' => $effectivePrice,
                'has_valid_price' => $effectivePrice !== null,
            ];
        });

        $lowestEffective = $mapped
            ->where('has_valid_price', true)
            ->min('effective_price');

        $variations = $mapped->map(function ($v) use ($lowestEffective) {
            $v['is_cheapest'] = $v['effective_price'] === $lowestEffective;
            unset($v['has_valid_price']);
            return $v;
        });

        $relatedProducts = Product::active()->category($product->category_id)->take(4)->get();

        return view('home.products.show', compact('product', 'filters', 'variations', 'relatedProducts'));
    }
}
