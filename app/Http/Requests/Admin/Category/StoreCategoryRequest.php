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
        return auth()->user()->can('manage-categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title',
            'is_active' => 'required|boolean',
            'parent_id' => 'required|integer|exists:categories,id',
            'attribute_ids' => 'required|array|exists:attributes,id',
            'attributes_ids.*' => 'required|integer|exists:attributes,id|distinct',
            'attribute_is_variation_id' => 'required|integer|exists:attributes,id|distinct',
            'attribute_is_filter_ids' => 'required|array|exists:attributes,id|distinct',
            'attribute_is_filter_ids.*' => 'required|integer|exists:attributes,id|distinct',
        ];
    }
}
