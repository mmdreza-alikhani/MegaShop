<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    protected $errorBag = 'store';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-coupons');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:coupons,title',
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|string|in:amount,percentage',
            'amount' => 'required_if:type,=,amount|numeric|min:0',
            'percentage' => 'required_if:type,=,percentage|numeric|min:0|max:100',
            'max_percentage_amount' => 'required_if:type,=,percentage|numeric',
            'expired_at' => 'required|date|after_or_equal:today',
        ];
    }
}
