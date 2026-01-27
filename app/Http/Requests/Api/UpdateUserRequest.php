<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only administrator can update users.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'administrator' ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'user_id' => [
                'sometimes',
                'string',
                'regex:/^\d{5}$/',
                Rule::unique('users', 'user_id')->ignore($userId),
            ],
            'name' => ['sometimes', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId,
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:20',
                'unique:users,phone,' . $userId,
            ],
            'password' => [
                'sometimes',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required_with:password', 'string'],
            'role' => ['sometimes', 'in:trainer,user'],
            'notifications_enabled' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.regex' => 'The user id must be exactly 5 digits.',
            'user_id.unique' => 'The user id has already been taken.',
            'name.regex' => 'The name field may only contain letters and spaces.',
            'email.unique' => 'The email has already been taken.',
            'phone.unique' => 'The phone number has already been taken.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'password_confirmation.required_with' => 'The password confirmation is required when password is provided.',
            'role.in' => 'Invalid role selected. Role must be trainer or user.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }

        if ($this->has('user_id')) {
            $this->merge([
                'user_id' => trim($this->user_id),
            ]);
        }

        if ($this->has('phone')) {
            $this->merge([
                'phone' => trim($this->phone),
            ]);
        }

        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name),
            ]);
        }
    }
}
