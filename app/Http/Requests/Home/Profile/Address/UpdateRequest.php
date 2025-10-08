<?php

namespace App\Http\Requests\Home\Profile\Address;

use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    protected $errorBag = 'updateAddress';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3|unique:user_addresses,title',
            'postal_code' => 'required|string|max:255|min:5|regex:/^[1-9][0-9]{9}$/',
            'phone_number' => 'required|string|max:255|regex:/^9\d{9}$/',
            'province_id' => 'required|integer|exists:provinces,id',
            'city_id' => [
                'required',
                'exists:cities,id',
                // ✅ چک کن که city واقعاً متعلق به province انتخاب شده باشه
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('province_id', $this->province_id);
                }),
            ],
            'address' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان آدرس الزامی است',
            'phone_number.regex' => 'شماره تلفن باید 10 رقم و با 9 شروع شود',
            'postal_code.regex' => 'کد پستی باید 10 رقم باشد',
            'city_id.exists' => 'شهر انتخاب شده متعلق به استان انتخابی نیست',
            'address.min' => 'آدرس باید حداقل 10 کاراکتر باشد',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone_number' => $this->sanitizePhoneNumber($this->phone_number),
            'postal_code' => $this->sanitizePostalCode($this->postal_code),
        ]);
    }

    private function sanitizePhoneNumber(?string $phone): ?string
    {
        if (!$phone) return null;

        // حذف فاصله، خط تیره و کاراکترهای اضافی
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // حذف پیش شماره +98 یا 0098 یا 98
        return preg_replace('/^(0098|98|0)/', '', $phone);
    }

    private function sanitizePostalCode(?string $postalCode): ?string
    {
        if (!$postalCode) return null;

        return preg_replace('/[^0-9]/', '', $postalCode);
    }

}
