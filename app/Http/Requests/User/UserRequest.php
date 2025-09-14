<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
       $id = $this->route('id'); 

        return [
            'fname'     => 'required|string|max:255',
            'lname'     => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $id,
            'email'     => 'required|email|max:255|unique:users,email,' . $id,
            'phone'     => 'nullable|string|max:20',
            'password'  => $id ? 'nullable|string|min:6' : 'required|string|min:6',
            'role'      => 'required|in:admin,customer',
            'is_verified' => 'boolean',
        ];
    }
}
