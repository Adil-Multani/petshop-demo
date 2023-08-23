<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort_by' => 'sometimes|required_if:sort_by,null|string|in:created_at,last_login_at,first_name,is_marketing,email',
            'desc' => 'sometimes|boolean',
            'is_marketing' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'The page number must be an integer.',
            'page.min' => 'The page number must be at least 1.',
            'limit.integer' => 'The limit must be an integer.',
            'limit.min' => 'The limit must be at least 1.',
            'sort_by.required_if' => 'The sort_by field must be a string.',
            'sort_by.in' => 'Invalid sorting field. Allowed values: created_at, last_login_at, first_name, is_marketing, email.',
            'desc.boolean' => 'The desc filter must be a boolean value.',
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
