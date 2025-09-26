<?php

namespace App\Http\Requests\Home\Cart;

use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AddRequest extends FormRequest
{
    protected $errorBag = 'addToCart';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(/**
         * @throws ValidationException
         */ function ($validator) {
            $variationId = $this->input('variation_id');
            $quantity = $this->input('quantity');

            $productVariation = ProductVariation::find($variationId);

            if ($productVariation && $quantity > $productVariation->quantity) {
                flash()->warning('تعداد محصولات انتخابی بیش از حد مجاز است!');
                throw ValidationException::withMessages([
                    'quantity' => 'تعداد محصولات انتخابی بیش از حد مجاز است!',
                ]);
            }
        });
    }

}
