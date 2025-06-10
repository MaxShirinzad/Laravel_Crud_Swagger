<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Update Glass request",
 *      description="Update Glass request body data",
 * )
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * @OA\Property(
     *   title="Title",
     *   example="product title",
     * )
     *
     * @var string
     */
    public string $Title;

    /**
     * @OA\Property(
     *   title="Desc",
     *   example="description",
     * )
     *
     * @var string
     */
    public string $Desc;

//    /**
//     * @OA\Property(
//     *   title="user_id",
//     *   example="1"
//     * )
//     *
//     * @var string
//     */
//    public string $user_id;

    /**
     * @OA\Property(
     *   title="Price",
     *   example="21000",
     * )
     *
     * @var string
     */
    public string $Price;

//    public $user_id;

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
        $email = $this->request->get("email");

        return [
//            'user_id' => '',

            'Title' => 'required|min:2|max:100',
            'Desc' => '',
            //'group_id' => 'required',


            'Price' => '',
//            'slug' => '',
//            'status' => '',
//            'IsImage' => '',

            //'image' => '',
        ];
    }
}
