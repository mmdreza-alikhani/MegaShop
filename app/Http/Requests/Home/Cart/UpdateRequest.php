<?php

namespace App\Http\Requests\Home\Cart;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'products.*' => 'required|array|min:1',
            'products.*.*' => 'required|integer|min:1',
        ];
    }
}
