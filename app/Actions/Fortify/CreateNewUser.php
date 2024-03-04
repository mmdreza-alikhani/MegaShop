<?php

namespace App\Actions\Fortify;

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'registerEmail' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
            ],
            'registerPassword' => [$this->passwordRules(), 'same:password_confirmation', 'min:8', 'max:32'],
            'password_confirmation' => 'required|same:registerPassword',
        ])->validate();

        session()->flash('welcome',  '! ' . $input['username'] .' خوش اومدی');

        toastr()->success('ثبت نام با موفقیت انجام شد!');

        return User::create([
            'username' => $input['username'],
            'email' => $input['registerEmail'],
            'password' => Hash::make($input['registerPassword']),
        ]);
    }
}
