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
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'لطفا حداقل یک تصویر انتخاب کنید',
            'images.*.image' => 'فایل باید تصویر باشد',
            'images.*.mimes' => 'فرمت تصویر باید jpeg, png, jpg, gif یا webp باشد',
            'images.*.max' => 'حجم هر تصویر نباید بیشتر از 2 مگابایت باشد',
        ];
    }
}
