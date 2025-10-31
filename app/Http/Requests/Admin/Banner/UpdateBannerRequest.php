<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBannerRequest extends FormRequest
{
    protected $errorBag = 'update';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('banners-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:banners,title,'.$this->route('banner')->id,
            'is_active' => 'required',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
            'type' => 'required',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'button_icon' => 'required|string|max:255',
            'priority' => 'required|integer|between:1,100',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('banner_id', $this->route('banner')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
