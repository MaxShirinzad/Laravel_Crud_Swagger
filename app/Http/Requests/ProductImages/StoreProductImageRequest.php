<?php

namespace App\Http\Requests\ProductImages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Store Glass request",
 *      description="Store Glass request body data",
 * )
 */
class StoreProductImageRequest extends FormRequest
{
    /**
     * @OA\Property(
     *   title="product_id",
     *   example="1",
     * )
     *
     * @var string
     */
    public string $product_id;

    /**
     * @OA\Property(
     *   title="name",
     *   example="/images/products/test.jpg"
     * )
     *
     * @var string
     */
    public string $name;


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
            'product_id' => 'required',
            'name' => 'required|min:2|max:100',
        ];
    }

    public function messages(): array
    {
        return [
//            'email.unique' => "Email already exists in the database. Please select another email!",
        ];
    }


}
