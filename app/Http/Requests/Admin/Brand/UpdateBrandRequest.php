<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBrandRequest extends FormRequest
{
    protected $errorBag = 'update';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:brands,title,' . $this->route('brand')->id,
            'is_active' => 'required|boolean',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('brand_id', $this->route('brand')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
