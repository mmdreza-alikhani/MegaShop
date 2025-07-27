<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    protected $errorBag = 'update';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-posts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:posts,title,' . $this->route('post')->id,
            'is_active' => 'required|boolean',
            'tag_ids' => 'required|array|exists:tags,id',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('post_id', $this->route('post')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
