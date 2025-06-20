<?php

namespace App\Http\Requests\Admin\Article;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    protected $errorBag = 'store';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-articles');
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
            'tag_ids' => 'required|array|exists:tags,id',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
        ];
    }
}
