<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store($attribute_ids , $product_id): void
    {
        foreach($attribute_ids as $key => $value){
            ProductAttribute::create([
                'product_id' => $product_id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }

    public function update($attribute_values): void
    {
        foreach ($attribute_values as $key => $value){
            $productAttribute = ProductAttribute::findOrFail($key);
            $productAttribute->update([
                'value' => $value
            ]);
        }
    }

    public function change($attribute_ids , $product_id): void
    {
        ProductAttribute::where('product_id' , $product_id)->delete();
        foreach($attribute_ids as $key => $value){
            ProductAttribute::create([
                'product_id' => $product_id,
                'attribute_id' => $key,
                'value' => $value,
            ]);
        }
    }
}
