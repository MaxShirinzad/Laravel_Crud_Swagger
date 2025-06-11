<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    public string $name;
    public string $email;
    public string $password;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:120', 'unique:users'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(6)
                    //->letters()
                    //->mixedCase()
                    ->numbers()
                    //->symbols()
                    //->uncompromised(),
            ],
        ];
    }
}
