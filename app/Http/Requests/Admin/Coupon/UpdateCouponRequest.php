<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCouponRequest extends FormRequest
{
    protected $errorBag = 'update';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('coupons-edit');
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'expired_at' => convertToGregorianDate($this->input('expired_at')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:coupons,title,'.$this->route('coupon')->id,
            'code' => 'required|string|max:255|unique:coupons,code,'.$this->route('coupon')->id,
            'type' => 'required|string|in:amount,percentage',
            'amount' => 'required_if:type,=,amount|numeric|min:0',
            'percentage' => 'required_if:type,=,percentage|numeric|min:0|max:100',
            'max_percentage_amount' => 'required_if:type,=,percentage|numeric',
            'expired_at' => 'required|date|after_or_equal:today',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('coupon_id', $this->route('coupon')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
