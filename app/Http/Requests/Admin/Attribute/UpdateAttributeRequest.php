<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAttributeRequest extends FormRequest
{
    protected $errorBag = 'update';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('attributes-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:attributes,title,'.$this->route('attribute')->id,
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

    public function failedValidation(Validator $validator): void
    {
        session()->flash('attribute_id', $this->route('attribute')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
