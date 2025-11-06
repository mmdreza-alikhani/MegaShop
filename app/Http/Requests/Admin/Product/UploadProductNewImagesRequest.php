<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadProductNewImagesRequest extends FormRequest
{
    protected $errorBag = 'UploadProductNewImages';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('products-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
