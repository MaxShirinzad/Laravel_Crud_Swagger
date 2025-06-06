<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Signup User request",
 *      description="Signup User request body data",
 * )
 */
class SignupRequest extends FormRequest
{
    /**
     * @OA\Property(
     *   title="name",
     *   example="Test",
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *   title="email",
     *   example="test@test.com",
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *   title="password",
     *   example="123123"
     * )
     *
     * @var string
     */
    public $password;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
//                'confirmed',
                Password::min(4)
//                    ->letters()
//                    ->symbols()
//                    ->numbers()
            ]
        ];
    }
}
