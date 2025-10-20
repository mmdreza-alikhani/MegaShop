<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    protected $errorBag = 'update';

    public function prepareForValidation(): void
    {
        $this->merge([
            'username' => trim(strtolower($this->input('username'))),
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
        return [
            'username' => 'required|string|max:255|unique:users,phone_number,'.auth()->id(),
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'phone_number' => 'nullable|regex:/^9\d{0,11}$/|unique:users,phone_number,'.auth()->id(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('user_id', $this->route('user')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }
}
