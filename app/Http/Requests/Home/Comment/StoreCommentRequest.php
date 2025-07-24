<?php

namespace App\Http\Requests\Home\Comment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCommentRequest extends FormRequest
{
    protected $errorBag = 'store';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string|min:3',
            'rate' => 'sometimes|required|numeric|digits_between:1,5'
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(redirect()->to(url()->previous() . '#reviews')->withErrors($validator, $this->errorBag));
    }

}
