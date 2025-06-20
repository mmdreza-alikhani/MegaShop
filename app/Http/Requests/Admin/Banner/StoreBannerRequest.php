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
        return auth()->user()->can('manage-banners');
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
            'is_active' => 'required|boolean',
            'tag_ids' => 'required|array|exists:tags,id',
            'text' => 'required|string',
            'image' => 'required|max:2048|mimes:jpg,jpeg,png,svg',
            'type' => 'required',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'button_icon' => 'nullable|string|max:255',
            'priority' => 'required|integer|between:1,100',
        ];
    }
}
