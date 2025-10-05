<?php

namespace App\Http\Requests\Home\Cart;

use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    protected $errorBag = 'updateCart';

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
            'products' => 'required|array|min:1',
            'products.*' => 'required|array|distinct|min:1',
            'products.*.*' => 'required|distinct|min:1'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(/**
         * @throws ValidationException
         */ function ($validator) {
            $variationId = $this->input('variation_id');
            $quantity = $this->integer('quantity');

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
