<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    protected $errorBag = 'store';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('banners-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:banners,title',
            'is_active' => 'required',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
            'type' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'priority' => 'required|integer|between:1,100',
        ];
    }
}
