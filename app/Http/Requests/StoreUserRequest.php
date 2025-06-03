<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Store User request",
 *      description="Store User request body data",
 * )
 */
class StoreUserRequest extends FormRequest
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
     *   example="test@test.com",
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
     * @OA\Property(
     *   title="image",
     *   example="/test/tt.jpeg"
     * )
     *
     * @var string
     */
    public $image;

//    /**
//     * @OA\Property(
//     *   title="type_id",
//     *   example="3"
//     * )
//     *
//     * @var string
//     */
//    public $type_id;


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
            'name' => 'required|string|max:55',
            'email' => 'email|unique:users,email',
            'password' => [
                '',
                Password::min(4)
//                    ->letters()
//                    ->symbols(),
            ],

//            'type_id' => 'required|integer|between:1,4',
//            'type_id' => '' ?? 3,
//            'type_id' => 'nullable',
            'user_category_id' => '',
            //'image' => 'required|string|max:100',
            'image' => 'nullable',
        ];
    }
}
