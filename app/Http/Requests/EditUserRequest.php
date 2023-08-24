<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
        if ($this->route('uuid')) {
            $uuid = $this->route('uuid');
        } else {
            $uuid = $this->input('user_uuid');
        }

        return [
            'first_name'   => 'required|string',
            'last_name'    => 'required|string',
            'email'        => 'required|email|unique:users,email,' . $uuid . ',uuid',
            // Exclude the current user from unique validation
            'password'     => 'nullable|string|min:8|confirmed',
            // Allow password to be nullable when not updated
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

