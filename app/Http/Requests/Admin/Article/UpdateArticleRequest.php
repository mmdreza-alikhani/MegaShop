<?php

namespace App\Http\Requests\Admin\Article;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateArticleRequest extends FormRequest
{
    protected $errorBag = 'update';
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
            'title' => 'required|string|max:255|unique:articles,title,' . $this->route('article')->id,
            'is_active' => 'required|boolean',
            'tag_ids' => 'required|array|exists:tags,id',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('article_id', $this->route('article')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
