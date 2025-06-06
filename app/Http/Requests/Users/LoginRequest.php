<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
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
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ];
    }

	public function messages()
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

//        if ($errors->hasAny()) {
            $customErrorMessage = "Provided email or password is incorrect.";
            throw new HttpResponseException(response()->json([
                'message' => $customErrorMessage,
                'errors' => $errors,
            ], 422));
//        }

//        parent::failedValidation($validator);
    }


}
