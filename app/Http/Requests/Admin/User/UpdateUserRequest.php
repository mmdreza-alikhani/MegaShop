<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    protected $errorBag = 'update';

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
        return auth()->user()->can('users-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone_number' => [
                'nullable', 'string', 'regex:/^0?9\d{9}$/',
                Rule::unique('users', 'phone_number')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6|max:255',
            'is_active' => 'nullable|boolean',
            'role' => 'nullable|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'شماره تلفن باید با 9 شروع شود و ۱۰ رقم باشد (مثال: 9121234567).',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('user_id', $this->route('user')?->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
