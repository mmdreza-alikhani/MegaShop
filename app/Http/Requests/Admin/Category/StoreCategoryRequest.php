<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    protected $errorBag = 'store';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('categories-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:posts,title',
            'is_active' => 'required|boolean',
            'parent_id' => 'required',
            'icon' => 'nullable|string|max:255',
            'filter_attribute_ids' => 'required|array',
            'filter_attribute_ids.*' => 'required|exists:attributes,id|distinct',
            'variation_attribute_id' => 'required|exists:attributes,id',
        ];
    }
}
