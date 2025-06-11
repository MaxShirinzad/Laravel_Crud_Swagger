<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean'
        ];
    }

	public function messages(): array
    {
    return [
        'email.required' => 'A email is required',
        'password.required'  => 'A password is required',
		'email.exists'  => 'Provided email or password is incorrect',
    ];
	}

 protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $customErrorMessage = "Provided email or password is incorrect.";
        throw new HttpResponseException(response()->json([
            'message' => $customErrorMessage,
            'errors' => $errors,
        ], 422));
    }


}
