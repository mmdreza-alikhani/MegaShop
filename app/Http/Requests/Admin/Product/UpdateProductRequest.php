<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    protected $errorBag = 'update';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('products-edit');
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'variation_values' => $this->convertDates($this->input('variation_values', [])),
            'new_variation_values' => $this->convertDates($this->input('new_variation_values', [])),
        ]);
    }

    private function convertDates(array $values): array
    {
        return collect($values)->map(function ($variation) {
            if (isset($variation['date_on_sale_from'])) {
                $variation['date_on_sale_from'] = str_replace('/', '-', convertToGregorianDate($variation['date_on_sale_from']));
            }
            if (isset($variation['date_on_sale_to'])) {
                $variation['date_on_sale_to'] = str_replace('/', '-', convertToGregorianDate($variation['date_on_sale_to']));
            }
            return $variation;
        })->toArray();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255|unique:products,title,'.$this->route('product')->id,
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
            'filters_value.*' => 'required|string',
            'variation_values' => 'required_without:new_variation_values|array',
            'variation_values.*.value' => 'required_without:new_variation_values|string|max:255',
            'variation_values.*.price' => 'required_without:new_variation_values|numeric|min:0',
            'variation_values.*.quantity' => 'required_without:new_variation_values|integer|min:0',
            'variation_values.*.sale_price' => 'nullable|numeric|min:0',
            'variation_values.*.date_on_sale_from' => 'nullable|date|after_or_equal:today',
            'variation_values.*.date_on_sale_to' => 'nullable|date|after_or_equal:today',
            'new_variation_values' => 'required_without:variation_values|array',
            'new_variation_values.*.value' => 'required_without:variation_values|string|max:255',
            'new_variation_values.*.price' => 'required_without:variation_values|numeric|min:0',
            'new_variation_values.*.quantity' => 'required_without:variation_values|integer|min:0',
            'new_variation_values.*.sku' => 'required_without:variation_values|string|max:255|unique:product_variations,sku',
            'new_variation_values.*.sale_price' => 'nullable|numeric|min:0',
            'new_variation_values.*.date_on_sale_from' => 'nullable|date|after_or_equal:today',
            'new_variation_values.*.date_on_sale_to' => 'nullable|date|after_or_equal:today',
            'newFaqs' => 'nullable|array',
            'newFaqs.*.question' => 'required|string|max:1250',
            'newFaqs.*.answer' => 'required|string|max:1250',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required|string|max:1250',
            'faqs.*.answer' => 'required|string|max:1250',
            'short_link' => 'nullable|max:12|unique:short_links,code|string',
        ];

        // Add unique rules for each existing variation SKU
        foreach ($this->input('variation_values', []) as $index => $variation) {
            $rules["variation_values.{$index}.sku"] = [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variations', 'sku')->ignore($index ?? null),
            ];
        }

        return $rules;
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('product_id', $this->route('product')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
