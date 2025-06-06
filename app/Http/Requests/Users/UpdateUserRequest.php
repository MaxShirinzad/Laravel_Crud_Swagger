<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Update User request",
 *      description="Update User request body data",
 * )
 */
class UpdateUserRequest extends FormRequest
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

//    /**
//     * @OA\Property(
//     *   title="last_name"
//     * )
//     *
//     * @var string
//     */
//    public $last_name;

    /**
     * @OA\Property(
     *   title="email",
     *   example="test23@test.com",
     * )
     *
     * @var string
     */
    public $email;

//    /**
//     * @OA\Property(
//     *   title="role_id"
//     * )
//     *
//     * @var int
//     */
//    public $role_id;

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
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:55',
            //'email' => 'email|unique:users,email,' .$this->id,
            'email' => 'email|unique:users,email,' .$this->id,
//            'email' => 'required|email|unique:users,email,',
            'password' => [
//                'confirmed',
                Password::min(4)
//                    ->letters()
//                    ->symbols(),
            ],

            'type_id' => '',
            'user_category_id' => '',
            'image' => 'nullable',
        ];
    }
}
