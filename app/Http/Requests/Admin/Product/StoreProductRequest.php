<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    protected $errorBag = 'store';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('products-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'brand_id' => 'required|exists:brands,id',
            'tag_ids' => 'array|exists:tags,id',
            'tag_ids.*' => 'exists:tags,id',
            'platform_id' => 'required|exists:platforms,id',
            'description' => 'required|string',
            'delivery_amount' => 'required|numeric|min:0',
            'delivery_amount_per_product' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'filters_value' => 'required|array',
            'filters_value.*' => 'nullable|string|max:255',
            'variation_values' => 'required|array|min:1',
            'variation_values.*.value' => 'required|string|max:255',
            'variation_values.*.price' => 'required|numeric|min:0',
            'variation_values.*.quantity' => 'required|integer|min:0',
            'variation_values.*.sku' => 'required|string|max:255|unique:product_variations,sku',
            'primary_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'other_images' => 'array',
            'other_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_link' => 'nullable|max:12|unique:short_links,code|string',
        ];
    }
}
