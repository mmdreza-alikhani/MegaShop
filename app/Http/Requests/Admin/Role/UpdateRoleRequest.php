<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRoleRequest extends FormRequest
{
    protected $errorBag = 'update';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->route('role')->id,
            'display_name' => 'required|string|max:255|unique:roles,display_name,' . $this->route('role')->id,
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('role_id', $this->route('role')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
