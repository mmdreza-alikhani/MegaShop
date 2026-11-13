<?php

namespace App\Http\Requests\Home\Cart;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'product_id' => 'required|integer|exists:products,id',
            'variation_id' => 'required|integer|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
