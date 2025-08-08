<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => array_merge(
                $this->passwordRules(),
                ['confirmed', 'min:8', 'max:32']
            ),
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray())->errorBag('register');
        }

        $user = User::create([
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'provider_name' => 'manual',
            'email_verified_at' => now(),
        ]);

        session()->flash('welcome', '! '.$user->username.' خوش اومدی');

        toastr()->success('ثبت نام با موفقیت انجام شد!');

        return $user;
    }
}
