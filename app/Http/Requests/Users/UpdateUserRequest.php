<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     @OA\Property(property="name", type="string", example="userOne"),
 *     @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="aa123123"),
 * )
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $requestUser = $this->user();
        $targetUser = $this->route('user');

        return $requestUser->id === $targetUser->id
            || $requestUser->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:55',
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user->id],
            'image' => [
                'nullable',
                File::image()
                    ->max(5 * 1024) // 5MB
                    ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
            ],
            'password' => [
                'sometimes',
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
