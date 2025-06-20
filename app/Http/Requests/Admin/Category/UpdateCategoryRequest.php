<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    protected $errorBag = 'update';
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
            'title' => 'required|string|max:255|unique:categories,title,' . $this->route('category')->id,
            'is_active' => 'required|boolean',
            'parent_id' => 'required|integer|exists:categories,id',
            'attribute_ids' => 'required|array|exists:attributes,id',
            'attributes_ids.*' => 'required|integer|exists:attributes,id|distinct',
            'attribute_is_variation_id' => 'required|integer|exists:attributes,id|distinct',
            'attribute_is_filter_ids' => 'required|array|exists:attributes,id|distinct',
            'attribute_is_filter_ids.*' => 'required|integer|exists:attributes,id|distinct',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('category_id', $this->route('category')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
