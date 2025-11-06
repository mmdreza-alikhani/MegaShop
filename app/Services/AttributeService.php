<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttributeService
{
    /**
     * Sync product filters (attributes)
     * @throws Throwable
     */
    public function syncFilters(int $productId, array $filterValues): void
    {
        DB::transaction(function () use ($productId, $filterValues) {
            // Delete old filters
            ProductFilter::where('product_id', $productId)->delete();

            // Create new filters
            $data = collect($filterValues)->map(function ($value, $attributeId) use ($productId) {
                return [
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'value' => $value,
                    'is_active' => 1,
                ];
            })->values()->toArray();

            if (!empty($data)) {
                ProductFilter::insert($data);
            }
        });
    }

    /**
     * Update existing filters
     * @throws Throwable
     */
    public function updateFilters(array $filterValues, Product $product, bool $is_category_modified): void
    {
        DB::transaction(function () use ($product, $is_category_modified, $filterValues) {
            if ($is_category_modified) {
                $product->filters()->delete();
                foreach ($filterValues as $filterId => $value) {
                    ProductFilter::create([
                        'attribute_id' => $filterId,
                        'product_id' => $product->id,
                        'value' => $value,
                    ]);
                }
            }else{
                foreach ($filterValues as $filterId => $value) {
                    ProductFilter::where('attribute_id', $filterId)->where('product_id', $product->id)->update([
                        'value' => $value,
                    ]);
                }
            }
        });
    }

    /**
     * Sync product variations
     * @throws Throwable
     */
    public function syncVariations(int $productId, int $attributeId, array $variations): void
    {
        DB::transaction(function () use ($productId, $attributeId, $variations) {
            // Delete old variations
            ProductVariation::where('product_id', $productId)->delete();

            // Create new variations
            $data = collect($variations)->map(function ($variation) use ($productId, $attributeId) {
                return [
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'value' => $variation['value'],
                    'price' => $variation['price'],
                    'quantity' => $variation['quantity'],
                    'sku' => $variation['sku'],
                    'sale_price' => $variation['sale_price'] ?? null,
                    'date_on_sale_from' => isset($variation['date_on_sale_from'])
                        ? convertToGregorianDate($variation['date_on_sale_from'])
                        : null,
                    'date_on_sale_to' => isset($variation['date_on_sale_to'])
                        ? convertToGregorianDate($variation['date_on_sale_to'])
                        : null
                ];
            })->toArray();

            if (!empty($data)) {
                ProductVariation::insert($data);
            }
        });
    }

    /**
     * Update existing variations
     * @throws Throwable
     */
    public function updateVariations(array $variationValues): void
    {
        DB::transaction(function () use ($variationValues) {
            foreach ($variationValues as $variationId => $values) {
                ProductVariation::where('id', $variationId)->update([
                    'price' => $values['price'],
                    'quantity' => $values['quantity'],
                    'sku' => $values['sku'],
                    'sale_price' => $values['sale_price'],
                    'date_on_sale_from' => $values['date_on_sale_from'] ?? null,
                    'date_on_sale_to' => $values['date_on_sale_to'] ?? null,
                ]);
            }
        });
    }

    /**
     * Handle variations update - updates existing and creates new ones
     * @throws Throwable
     */
    public function handleVariationsUpdate(
        int $productId,
        int $attributeId = 1,
        array $existingVariations = [],
        array $newVariations = []
    ): void {
        DB::transaction(function () use ($productId, $attributeId, $existingVariations, $newVariations) {
            // Update existing variations
            $product = Product::find($productId);
            $existingVariationsIds = $product->variations()->pluck('id')->toArray();
            $requestVariationsIds = array_keys($existingVariations);
            $removedVariations = array_diff($existingVariationsIds, $requestVariationsIds);
            if (!empty($removedVariations)) {
                $product->variations()->whereIn('id', $removedVariations)->delete();
            }
            if (!empty($existingVariations)) {
                $this->updateVariations($existingVariations);
            }

            // Create new variations
            if (!empty($newVariations)) {
                $data = collect($newVariations)->map(function ($variation) use ($productId, $attributeId) {
                    return [
                        'product_id' => $productId,
                        'attribute_id' => $attributeId,
                        'value' => $variation['value'],
                        'price' => $variation['price'],
                        'quantity' => $variation['quantity'],
                        'sku' => $variation['sku'],
                        'sale_price' => $variation['sale_price'],
                        'date_on_sale_from' => $variation['date_on_sale_from'],
                        'date_on_sale_to' => $variation['date_on_sale_to'],
                    ];
                })->toArray();

                if (!empty($data)) {
                    ProductVariation::insert($data);
                }
            }
        });
    }
}
