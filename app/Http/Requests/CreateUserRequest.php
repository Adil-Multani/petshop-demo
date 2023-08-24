<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'first_name'   => 'required|string',
            'last_name'    => 'required|string',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8|confirmed',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'      => 'required|string',
            'phone_number' => 'required|string',
            'is_marketing' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'email.unique'         => 'This email address is already in use.',
            'password.min'         => 'Password must be at least 8 characters long.',
            'password.confirmed'   => 'Password confirmation does not match.',
            'is_marketing.boolean' => 'The marketing filter must be a boolean value.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            apiResponse(null, $validator->errors()->first(), 400, false)
        );
    }
}
