<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
    'fname' => 'required|string|max:255',
    'lname' => 'required|string|max:255',
    'username' => 'required|string|max:255|unique:users,username',
    'email' => 'required|email|unique:users,email',
    'phone' => 'required|string|max:20',
    'password' => 'required|string|min:6|confirmed',

        ];
    }
        public function messages(): array
{
    return [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please provide a valid email address.',
        'email.unique' => 'Email is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];
}
}
