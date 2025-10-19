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
        return auth()->user()->can('categories-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:categories,title,'.$this->route('category')->id,
            'is_active' => 'required|boolean',
            'parent_id' => 'required',
            'icon' => 'nullable|string|max:255',
            'filter_attribute_ids' => 'required|array',
            'filter_attribute_ids.*' => 'required|exists:attributes,id|distinct',
            'variation_attribute_id' => 'required|exists:attributes,id',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('category_id', $this->route('category')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
