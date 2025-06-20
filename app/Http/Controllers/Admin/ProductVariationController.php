<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    public function store($variations,  $attributeId, $product): void
    {
        $counter = count($variations['value']);
        for ($i = 0; $i < $counter; $i++) {
            ProductVariation::create([
                'attribute_id' => $attributeId,
                'product_id' => $product->id,
                'value' => $variations['value'][$i],
                'price' => $variations['price'][$i],
                'quantity' => $variations['quantity'][$i],
                'sku' => $variations['sku'][$i],
            ]);
        }
    }

    public function update($variation_values): void
    {
        foreach ($variation_values as $key => $value){
            $productVariation = ProductVariation::findOrFail($key);

            $productVariation->update([
                'price' => $value['price'],
                'quantity' => $value['quantity'],
                'sale_price' => $value['sale_price'],
                'sku' => $value['sku'],
                'date_on_sale_from' => convertToGregorianDate($value['date_on_sale_from']),
                'date_on_sale_to' => convertToGregorianDate($value['date_on_sale_to']),
            ]);
        }
    }

    public function change($variations,  $attributeId, $product): void
    {
        ProductVariation::where('product_id' , $product->id)->delete();
        $counter = count($variations['value']);
        for ($i = 0; $i < $counter; $i++) {
            ProductVariation::create([
                'attribute_id' => $attributeId,
                'product_id' => $product->id,
                'value' => $variations['value'][$i],
                'price' => $variations['price'][$i],
                'quantity' => $variations['quantity'][$i],
                'sku' => $variations['sku'][$i],
            ]);
        }
    }
}
?>
