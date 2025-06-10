<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePermissionRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:permissions,name,' . $this->route('permission')->id,
            'display_name' => 'required|string|max:255|unique:permissions,display_name,' . $this->route('permission')->id,
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        session()->flash('permission_id', $this->route('permission')->id);

        throw new HttpResponseException(back()->withErrors($validator, $this->errorBag));
    }

}
