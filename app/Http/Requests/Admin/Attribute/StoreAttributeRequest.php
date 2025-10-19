<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    protected $errorBag = 'store';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('banners-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:attributes,title',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان ویژگی الزامی است',
            'title.unique' => 'این ویژگی قبلاً ثبت شده است',
            'title.max' => 'عنوان نباید بیشتر از 255 کاراکتر باشد',
        ];
    }
}
