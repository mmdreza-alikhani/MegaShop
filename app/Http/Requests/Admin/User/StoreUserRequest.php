<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    protected $errorBag = 'store';

    public function prepareForValidation(): void
    {
        $this->merge([
            'username' => trim(strtolower((string) $this->input('username'))),
            'phone_number' => $this->input('phone_number') ? trim((string) $this->input('phone_number')) : null,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('users-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|regex:/^0?9\d{9}$/|unique:users,phone_number',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'شماره تلفن باید با 9 شروع شود و ۱۰ رقم باشد (مثال: 9121234567).',
        ];
    }
}
