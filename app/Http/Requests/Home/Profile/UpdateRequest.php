<?php

namespace App\Http\Requests\Home\Profile;

use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    protected $errorBag = 'updateProfile';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users,username,' . $this->user()->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|regex:/^9\d{9}$/|unique:users,phone_number,' . $this->user()->id,
            'avatar' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user()->id,
        ];
    }

}
