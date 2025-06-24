<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;

/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="user1"),
 *     @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="123123"),
 *     @OA\Property(property="image", type="string", example="/users/images/user1.jpg")
 * )
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:55',
            'email' => 'required|string|email|unique:users,email',
            'image' => [
                'nullable',
                File::image()
                    ->max(5 * 1024) // 5MB
                    ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
            ],
            'password' => [
                'required',
                'string',
                Password::min(6)
                    //->letters()
                    //->mixedCase()
                    ->numbers()
                    //->symbols()
                    //->uncompromised(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            //'password.uncompromised' => 'This password has appeared in a data breach. Please choose a different password.',
        ];
    }

}

