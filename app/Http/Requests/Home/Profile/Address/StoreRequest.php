<?php

namespace App\Http\Requests\Home\Profile\Address;

use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreRequest extends FormRequest
{
    protected $errorBag = 'storeAddress';

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
            'title' => 'required|string|max:255|min:3|unique:user_addresses,title',
            'postal_code' => 'required|string|max:255|min:5|regex:/^[1-9][0-9]{9}$/',
            'phone_number' => 'required|string|max:255|regex:/^9\d{9}$/',
            'province_id' => 'required|integer|exists:provinces,id',
            'city_id' => 'required|integer|exists:cities,id',
            'address' => 'required|string|max:1250',
        ];
    }

}
