<?php

namespace App\Http\Requests\ProductImages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Schema(
 *      title="Update Glass request",
 *      description="Update Glass request body data",
 * )
 */
class UpdateProductImageRequest extends FormRequest
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
}
