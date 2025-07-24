<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTagRequest extends FormRequest
{
    protected $errorBag = 'update';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-general');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:tags,title,' . $this->route('tag')->id,
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('tag_id', $this->route('tag')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
